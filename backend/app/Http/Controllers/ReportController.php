<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\StoreReportController;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Sport\StoreSportRequest;
use App\Models\Report;
use App\Support\CloudinaryStorage;
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
        if (!$request->filled('reason') || !$request->filled('note') || !$request->hasFile('proof')) {
            return back()
                ->withInput()
                ->with('error', 'Report rejected. Please complete all required fields and attach proof.');
        }

        if (!$request->file('proof')->isValid()) {
            return back()
                ->with('error', 'Report rejected due to an invalid proof upload. Please try again.');
        }

        $validated = $request->validated();
        $storedProofPath = CloudinaryStorage::upload(
            $request->file('proof'),
            'reports/proofs',
            'auto'
        );

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
