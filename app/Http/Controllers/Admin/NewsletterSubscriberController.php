<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        /* ---------- Status filter ---------- */
        $status = $request->input('status', 'all');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        /* ---------- Sort ---------- */
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'email'  => $query->orderBy('email'),
            default  => $query->latest(),
        };

        $subscribers = $query->paginate(20)->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'          => NewsletterSubscriber::count(),
            'active'         => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive'       => NewsletterSubscriber::where('is_active', false)->count(),
            'this_month'     => NewsletterSubscriber::whereYear('created_at', now()->year)
                                  ->whereMonth('created_at', now()->month)
                                  ->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats', 'search', 'status', 'sort'));
    }

    /**
     * Toggle active status for a single subscriber.
     */
    public function toggle(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'is_active' => ! $subscriber->is_active,
            'unsubscribed_at' => $subscriber->is_active ? now() : null,
        ]);

        return back()->with('status', $subscriber->is_active
            ? "{$subscriber->email} has been reactivated."
            : "{$subscriber->email} has been unsubscribed.");
    }

    /**
     * Delete a subscriber.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();

        return back()->with('status', "{$email} has been removed from the list.");
    }

    /**
     * Handle bulk actions.
     */
    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:newsletter_subscribers,id',
        ]);

        $ids = $request->input('ids');
        $count = count($ids);

        switch ($request->input('action')) {
            case 'activate':
                NewsletterSubscriber::whereIn('id', $ids)->update([
                    'is_active' => true,
                    'unsubscribed_at' => null,
                ]);
                $msg = "{$count} subscriber(s) activated.";
                break;

            case 'deactivate':
                NewsletterSubscriber::whereIn('id', $ids)->update([
                    'is_active' => false,
                    'unsubscribed_at' => now(),
                ]);
                $msg = "{$count} subscriber(s) unsubscribed.";
                break;

            case 'delete':
            default:
                NewsletterSubscriber::whereIn('id', $ids)->delete();
                $msg = "{$count} subscriber(s) deleted.";
                break;
        }

        return back()->with('status', $msg);
    }

    /**
     * Export all subscribers as CSV.
     */
    public function export(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $rows = $query->orderBy('email')->get();

        $filename = 'newsletter-subscribers-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens it correctly
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID', 'Email', 'Name', 'Status',
                'Subscribed At', 'Unsubscribed At', 'IP Address', 'Created At',
            ]);

            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->id,
                    $row->email,
                    $row->name ?? '',
                    $row->is_active ? 'Active' : 'Inactive',
                    optional($row->subscribed_at)->format('Y-m-d H:i'),
                    optional($row->unsubscribed_at)->format('Y-m-d H:i'),
                    $row->ip_address ?? '',
                    optional($row->created_at)->format('Y-m-d H:i'),
                ]);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }
}