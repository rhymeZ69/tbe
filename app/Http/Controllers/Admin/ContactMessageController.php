<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        /* ---------- Filter ---------- */
        switch ($request->input('filter', 'all')) {
            case 'unread':
                $query->where('is_read', false);
                break;
            case 'read':
                $query->where('is_read', true);
                break;
        }

        $messages = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total'     => ContactMessage::count(),
            'unread'    => ContactMessage::where('is_read', false)->count(),
            'read'      => ContactMessage::where('is_read', true)->count(),
            'this_week' => ContactMessage::where('created_at', '>=', now()->subWeek())->count(),
        ];

        return view('admin.messages.index', compact('messages', 'stats', 'search'));
    }

    public function show(ContactMessage $message)
    {
        // Mark as read when opened
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function toggle(ContactMessage $message)
    {
        $message->update(['is_read' => ! $message->is_read]);

        $state = $message->is_read ? 'read' : 'unread';

        return back()->with('status', "Message from {$message->name} marked as {$state}.");
    }

    public function destroy(ContactMessage $message)
    {
        $name = $message->name;
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('status', "Message from {$name} has been deleted.");
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:contact_messages,id',
        ]);

        $ids   = $request->input('ids');
        $count = count($ids);

        switch ($request->input('action')) {
            case 'mark_read':
                ContactMessage::whereIn('id', $ids)->update(['is_read' => true]);
                $msg = "{$count} " . \Str::plural('message', $count) . ' marked as read.';
                break;

            case 'mark_unread':
                ContactMessage::whereIn('id', $ids)->update(['is_read' => false]);
                $msg = "{$count} " . \Str::plural('message', $count) . ' marked as unread.';
                break;

            case 'delete':
            default:
                ContactMessage::whereIn('id', $ids)->delete();
                $msg = "{$count} " . \Str::plural('message', $count) . ' deleted.';
                break;
        }

        return back()->with('status', $msg);
    }

    public function export(Request $request)
    {
        $query = ContactMessage::query();

        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->input('filter') === 'unread') {
            $query->where('is_read', false);
        }

        $rows = $query->latest()->get();

        $filename = 'messages-' . now()->format('Y-m-d-His') . '.csv';

        return Response::stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Received']);

            foreach ($rows as $m) {
                fputcsv($out, [
                    $m->id,
                    $m->name,
                    $m->email,
                    $m->phone ?? '',
                    $m->subject ?? '',
                    $m->message ?? '',
                    $m->is_read ? 'Read' : 'Unread',
                    $m->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}