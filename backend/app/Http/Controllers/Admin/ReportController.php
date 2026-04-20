<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->string('q')),
            'cause' => (string) $request->string('cause', 'all'),
            'status' => (string) $request->string('status', 'pending'),
        ];

        $reportsQuery = Report::query()->with('reporter:id,name,avatar')->latest();

        if ($filters['q'] !== '') {
            $ticketId = (int) trim($filters['q']);

            $reportsQuery->where(function ($query) use ($filters, $ticketId) {
                $query
                    ->where('cause', 'ilike', '%' . $filters['q'] . '%')
                    ->orWhere('description', 'ilike', '%' . $filters['q'] . '%')
                    ->orWhereHas('reporter', function ($reporterQuery) use ($filters) {
                        $reporterQuery->where('name', 'ilike', '%' . $filters['q'] . '%');
                    });

                if ($ticketId !== '') {
                    $query->orWhere('id', (int) $ticketId);
                }
            });
        }

        if ($filters['cause'] !== 'all') {
            $reportsQuery->where('cause', $filters['cause']);
        }

        if ($filters['status'] === 'cancelled') {
            $reportsQuery->where('isCancelled', true);
        } elseif (in_array($filters['status'], ['pending', 'resolved'], true)) {
            $reportsQuery
                ->where('isCancelled', false)
                ->where('status', $filters['status']);
        }

        $reports = $reportsQuery
            ->paginate(10)
            ->withQueryString();

        $causes = Report::query()
            ->select('cause')
            ->distinct()
            ->orderBy('cause')
            ->pluck('cause');

        $counts = [
            'total' => Report::query()->count(),
            'pending' => Report::query()->where('isCancelled', false)->where('status', 'pending')->count(),
            'resolved' => Report::query()->where('isCancelled', false)->where('status', 'resolved')->count(),
            'cancelled' => Report::query()->where('isCancelled', true)->count(),
        ];

        return view('admin.reports.index', compact('reports', 'filters', 'causes', 'counts'));
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,resolved'],
        ]);

        if ($report->isCancelled) {
            return back()->with('error', 'Cancelled reports cannot be updated.');
        }

        if ($report->status === $validated['status']) {
            return back()->with('success', 'Report status is already up to date.');
        }

        $report->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Report #' . $report->id . ' status updated successfully.');
    }
}
