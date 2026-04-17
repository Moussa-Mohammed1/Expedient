<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\StoreReportController;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Sport\StoreSportRequest;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reports = Report::query()
            ->where('reporter_id', $request->user()->id)
            ->latest()
            ->get();

        return view('trainee.reports.index', [
            'reports' => $reports,
        ]);
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $storedProofPath = $request->file('proof')->store('reports/proofs', 'public');

        Report::create([
            'reporter_id' => $request->user()->id,
            'cause' => $validated['reason'],
            'description' => isset($validated['opinion_id'])
                ? '[Opinion #' . $validated['opinion_id'] . '] ' . $validated['note']
                : $validated['note'],
            'proof' => $storedProofPath,
        ]);

        return back()->with('success', 'Report submitted successfully.');
    }

    public function cancel(Request $request, Report $report): RedirectResponse
    {
        if ($report->reporter_id !== $request->user()->id) {
            return redirect('/home');
        }
        if (!$report->isCancelled) {
            $report->update([
                'isCancelled' => true,
            ]);
        }

        return back()->with('success', 'Report cancelled successfully.');
    }
}
