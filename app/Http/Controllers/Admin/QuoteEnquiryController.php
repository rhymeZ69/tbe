<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\QuoteEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class QuoteEnquiryController extends Controller
{
    /* Valid statuses for the pipeline */
    private const STATUSES = ['new', 'in_progress', 'quoted', 'won', 'lost', 'spam'];

    public function index(Request $request)
    {
        $query = QuoteEnquiry::with('country');

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /* ---------- Status filter ---------- */
        $status = $request->input('status', 'all');
        if ($status !== 'all' && in_array($status, self::STATUSES)) {
            $query->where('status', $status);
        }

        /* ---------- Country filter ---------- */
        if ($countryId = $request->input('country')) {
            $query->where('country_id', $countryId);
        }

        /* ---------- Sort ---------- */
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest'    => $query->oldest(),
            'reference' => $query->orderBy('reference'),
            default     => $query->latest(),
        };

        $enquiries = $query->paginate(20)->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'       => QuoteEnquiry::count(),
            'new'         => QuoteEnquiry::where('status', 'new')->count(),
            'in_progress' => QuoteEnquiry::where('status', 'in_progress')->count(),
            'won'         => QuoteEnquiry::where('status', 'won')->count(),
            'this_month'  => QuoteEnquiry::whereYear('created_at', now()->year)
                                    ->whereMonth('created_at', now()->month)
                                    ->count(),
        ];

        $countries = Country::orderBy('name')->get();

        return view('admin.enquiries.index', compact(
            'enquiries', 'stats', 'countries', 'search', 'status', 'sort'
        ));
    }

    public function show(QuoteEnquiry $enquiry)
    {
        $enquiry->load(['country', 'items.product', 'items.category']);

        return view('admin.enquiries.show', compact('enquiry'));
    }

    /**
     * Update status, admin notes, or manual timestamps.
     */
    public function update(Request $request, QuoteEnquiry $enquiry)
    {
        $validated = $request->validate([
            'status'      => ['required', 'in:new,in_progress,quoted,won,lost,spam'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldStatus = $enquiry->status;

        $enquiry->status      = $validated['status'];
        $enquiry->admin_notes = $validated['admin_notes'] ?? null;

        /* Auto-populate timestamps based on status */
        if ($oldStatus !== $validated['status']) {
            if (in_array($validated['status'], ['in_progress', 'quoted', 'won']) && ! $enquiry->contacted_at) {
                $enquiry->contacted_at = now();
            }

            if (in_array($validated['status'], ['quoted', 'won']) && ! $enquiry->quoted_at) {
                $enquiry->quoted_at = now();
            }
        }

        $enquiry->save();

        return back()->with('status', "Enquiry {$enquiry->reference} has been updated.");
    }

    /**
     * Quick inline status change (used by the list view).
     */
    public function updateStatus(Request $request, QuoteEnquiry $enquiry)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,in_progress,quoted,won,lost,spam'],
        ]);

        $old = $enquiry->status;
        $enquiry->status = $validated['status'];

        if ($old !== $validated['status']) {
            if (in_array($validated['status'], ['in_progress', 'quoted', 'won']) && ! $enquiry->contacted_at) {
                $enquiry->contacted_at = now();
            }
            if (in_array($validated['status'], ['quoted', 'won']) && ! $enquiry->quoted_at) {
                $enquiry->quoted_at = now();
            }
        }

        $enquiry->save();

        return back()->with('status', "Status updated for {$enquiry->reference}.");
    }

    public function destroy(QuoteEnquiry $enquiry)
    {
        $reference = $enquiry->reference;
        $enquiry->delete();

        return redirect()
            ->route('admin.enquiries.index')
            ->with('status', "Enquiry {$reference} has been deleted.");
    }

    /**
     * Bulk actions on multiple enquiries.
     */
    public function bulk(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_new,mark_in_progress,mark_quoted,mark_won,mark_lost,mark_spam,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:quote_enquiries,id',
        ]);

        $ids   = $request->input('ids');
        $count = count($ids);

        if ($request->input('action') === 'delete') {
            QuoteEnquiry::whereIn('id', $ids)->delete();
            return back()->with('status', "{$count} " . \Str::plural('enquiry', $count) . " deleted.");
        }

        $statusMap = [
            'mark_new'         => 'new',
            'mark_in_progress' => 'in_progress',
            'mark_quoted'      => 'quoted',
            'mark_won'         => 'won',
            'mark_lost'        => 'lost',
            'mark_spam'        => 'spam',
        ];

        $newStatus = $statusMap[$request->input('action')];

        $enquiries = QuoteEnquiry::whereIn('id', $ids)->get();

        foreach ($enquiries as $enquiry) {
            $old = $enquiry->status;
            $enquiry->status = $newStatus;

            if ($old !== $newStatus) {
                if (in_array($newStatus, ['in_progress', 'quoted', 'won']) && ! $enquiry->contacted_at) {
                    $enquiry->contacted_at = now();
                }
                if (in_array($newStatus, ['quoted', 'won']) && ! $enquiry->quoted_at) {
                    $enquiry->quoted_at = now();
                }
            }

            $enquiry->save();
        }

        $label = str_replace('mark_', '', $request->input('action'));
        $label = ucwords(str_replace('_', ' ', $label));

        return back()->with('status', "{$count} " . \Str::plural('enquiry', $count) . " marked as {$label}.");
    }

    /**
     * Export enquiries as CSV (respects current filters).
     */
    public function export(Request $request)
    {
        $query = QuoteEnquiry::with('country');

        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all' && in_array($status, self::STATUSES)) {
                $query->where('status', $status);
            }
        }

        if ($countryId = $request->input('country')) {
            $query->where('country_id', $countryId);
        }

        $rows = $query->latest()->get();

        $filename = 'enquiries-' . now()->format('Y-m-d-His') . '.csv';

        return Response::stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($out, [
                'Reference', 'Name', 'Company', 'Email', 'Phone',
                'Country', 'Product Interest', 'Quantity (MT)', 'Status',
                'Message', 'Admin Notes', 'Created At',
            ]);

            foreach ($rows as $e) {
                fputcsv($out, [
                    $e->reference,
                    $e->name,
                    $e->company ?? '',
                    $e->email,
                    $e->phone ?? '',
                    $e->country?->name ?? '',
                    $e->product_interest,
                    $e->quantity_mt,
                    $e->status,
                    $e->message ?? '',
                    $e->admin_notes ?? '',
                    $e->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}