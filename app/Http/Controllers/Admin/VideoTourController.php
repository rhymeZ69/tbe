<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoTourController extends Controller
{
    public function index(Request $request)
    {
        $query = VideoTour::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('stage_tag', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        /* ---------- Filter ---------- */
        match ($request->input('filter', 'all')) {
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            default    => null,
        };

        $tours = $query->orderBy('stage_number')->paginate(15)->withQueryString();

        $stats = [
            'total'    => VideoTour::count(),
            'active'   => VideoTour::where('is_active', true)->count(),
            'youtube'  => VideoTour::where('video_type', 'youtube')->count(),
            'local'    => VideoTour::whereIn('video_type', ['mp4', 'webm'])->count(),
        ];

        return view('admin.video-tours.index', compact('tours', 'stats', 'search'));
    }

    public function create()
    {
        $nextStage = (int) (VideoTour::max('stage_number') ?? 0) + 1;

        return view('admin.video-tours.create', compact('nextStage'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTour($request);

        // Handle uploaded video file
        if ($request->input('video_source_type') === 'upload' && $request->hasFile('video_file')) {
            $uploaded = $this->storeVideoFile($request->file('video_file'));

            $validated['video_source'] = $uploaded['path'];
            $validated['video_type']   = $uploaded['type'];
        }

        unset($validated['video_file'], $validated['video_source_type']);

        // Handle poster upload
        if ($request->hasFile('poster_file')) {
            $validated['poster_image'] = $this->storePoster($request->file('poster_file'));
        }
        unset($validated['poster_file']);

        VideoTour::create($validated);

        return redirect()
            ->route('admin.video-tours.index')
            ->with('status', "Stage {$validated['stage_number']} — \"{$validated['title']}\" has been created.");
    }

    public function edit(VideoTour $videoTour)
    {
        return view('admin.video-tours.edit', compact('videoTour'));
    }

    public function update(Request $request, VideoTour $videoTour)
    {
        $validated = $this->validateTour($request, $videoTour);

        // Handle uploaded video file
        if ($request->input('video_source_type') === 'upload' && $request->hasFile('video_file')) {
            // Delete the previous video if it was a local upload
            $this->deleteLocalVideo($videoTour->video_source);

            $uploaded = $this->storeVideoFile($request->file('video_file'));

            $validated['video_source'] = $uploaded['path'];
            $validated['video_type']   = $uploaded['type'];
        }

        unset($validated['video_file'], $validated['video_source_type']);

        // Handle poster upload
        if ($request->hasFile('poster_file')) {
            if ($videoTour->poster_image && Storage::disk('public_uploads')->exists($videoTour->poster_image)) {
                Storage::disk('public_uploads')->delete($videoTour->poster_image);
            }
            $validated['poster_image'] = $this->storePoster($request->file('poster_file'));
        }
        unset($validated['poster_file']);

        // Allow removing the poster
        if ($request->boolean('remove_poster') && $videoTour->poster_image) {
            if (Storage::disk('public_uploads')->exists($videoTour->poster_image)) {
                Storage::disk('public_uploads')->delete($videoTour->poster_image);
            }
            $validated['poster_image'] = null;
        }

        $videoTour->update($validated);

        return redirect()
            ->route('admin.video-tours.index')
            ->with('status', "Stage {$videoTour->stage_number} — \"{$videoTour->title}\" has been updated.");
    }

    public function destroy(VideoTour $videoTour)
    {
        $label = "Stage {$videoTour->stage_number} — {$videoTour->title}";

        // Delete poster
        if ($videoTour->poster_image && Storage::disk('public_uploads')->exists($videoTour->poster_image)) {
            Storage::disk('public_uploads')->delete($videoTour->poster_image);
        }

        // Delete local video file (if it's an uploaded one, not a URL)
        $this->deleteLocalVideo($videoTour->video_source);

        $videoTour->delete();

        return back()->with('status', "\"{$label}\" has been deleted.");
    }

    public function toggle(Request $request, VideoTour $videoTour)
    {
        $videoTour->update(['is_active' => ! $videoTour->is_active]);

        $state = $videoTour->is_active ? 'activated' : 'hidden';

        return back()->with('status', "Stage {$videoTour->stage_number} \"{$videoTour->title}\" has been {$state}.");
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    private function validateTour(Request $request, ?VideoTour $tour = null): array
    {
        $sourceType = $request->input('video_source_type', 'link');
        $isUpload   = $sourceType === 'upload';

        return $request->validate([
            'stage_number'      => ['required', 'integer', 'min:1', 'max:255'],
            'title'             => ['required', 'string', 'max:120'],
            'stage_tag'         => ['nullable', 'string', 'max:40'],
            'description'       => ['nullable', 'string', 'max:2000'],
            'video_source_type' => ['required', 'in:link,upload'],

            // Required only when using a link; not required for uploads
            'video_source'      => [$isUpload ? 'nullable' : 'required', 'string', 'max:500'],

            // Required only when uploading a new file; not required on edit if a file already exists
            'video_file'        => [$isUpload ? 'required' : 'nullable', 'file', 'mimes:mp4,webm,ogg,mov,m4v', 'max:102400'], // 100 MB

            'video_type'        => ['nullable', 'in:youtube,vimeo,mp4,webm,other'],
            'poster_file'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'         => ['boolean'],
        ]);
    }

    private function storePoster($file): string
    {
        $filename = time() . '-' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
        $path     = 'images/video-tours/' . $filename;

        Storage::disk('public_uploads')->put($path, file_get_contents($file));

        return $path;
    }

    private function storeVideoFile($file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Map extension to a supported video_type
        $typeMap = [
            'mp4'  => 'mp4',
            'm4v'  => 'mp4',
            'mov'  => 'mp4',
            'webm' => 'webm',
            'ogg'  => 'webm',
        ];

        $type = $typeMap[$extension] ?? 'other';

        // Build a safe, unique filename
        $slug     = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $slug . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
        $path     = 'videos/' . $filename;

        // Ensure the folder exists
        $disk = Storage::disk('public_uploads');
        if (! $disk->exists('videos')) {
            $disk->makeDirectory('videos');
        }

        $disk->put($path, file_get_contents($file));

        return ['path' => $path, 'type' => $type];
    }

    private function deleteLocalVideo(?string $source): void
    {
        if (! $source) return;

        // Only delete if it's a local path (no scheme, no leading domain)
        if (preg_match('#^https?://#i', $source)) return;

        $disk = Storage::disk('public_uploads');
        if ($disk->exists($source)) {
            $disk->delete($source);
        }
    }
}