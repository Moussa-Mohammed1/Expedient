<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CoachVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->string('q')),
            'status' => (string) $request->string('status', 'pending'),
        ];

        $verificationsQuery = CoachVerification::query()
            ->with(['coach.user:id,name,avatar'])
            ->latest('requested_at');

        if ($filters['q'] !== '') {
            $numericQuery = preg_replace('/\D+/', '', $filters['q']);

            $verificationsQuery->where(function ($query) use ($filters, $numericQuery) {
                $query->where('document_description', 'ilike', '%' . $filters['q'] . '%')
                    ->orWhereHas('coach.user', function ($userQuery) use ($filters) {
                        $userQuery->where('name', 'ilike', '%' . $filters['q'] . '%');
                    });

                    $query->orWhere('rejection_cause', 'ilike', '%' . $filters['q'] . '%');

                if ($numericQuery !== '') {
                    $query
                        ->orWhere('id', (int) $numericQuery)
                        ->orWhere('coach_id', (int) $numericQuery);
                }
            });
        }

        if ($filters['status'] !== 'all') {
            $verificationsQuery->where('status', $filters['status']);
        }

        $verifications = $verificationsQuery
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'total' => CoachVerification::query()->count(),
            'pending' => CoachVerification::query()->where('status', 'pending')->count(),
            'approved' => CoachVerification::query()->where('status', 'approved')->count(),
            'rejected' => CoachVerification::query()->where('status', 'rejected')->count(),
        ];

        return view('admin.verifications.index', compact('verifications', 'filters', 'counts'));
    }

    public function updateStatus(Request $request, CoachVerification $coachVerification): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'rejection_cause' => ['nullable', 'string', 'max:1000'],
        ]);

        $status = $validated['status'];
        $rejectionCause = trim((string) ($validated['rejection_cause'] ?? ''));

        if ($status === 'rejected' && $rejectionCause === '') {
            return back()->with('error', 'Please provide a rejection cause before rejecting this request.');
        }

        $updates = [
            'status' => $status,
        ];

        if ($status === 'approved') {
            $updates['reviewed_at'] = now();

            $updates['rejection_cause'] = null;

            $coachVerification->coach?->update(['hasBadge' => true]);
        } elseif ($status === 'rejected') {
            $updates['reviewed_at'] = now();
            $updates['rejection_cause'] = $rejectionCause;
            $coachVerification->coach?->update(['hasBadge' => false]);
        } else {
            $updates['reviewed_at'] = null;
            $updates['rejection_cause'] = null;
            $coachVerification->coach?->update(['hasBadge' => false]);
        }

        $coachVerification->update($updates);

        return back()->with('success', 'Verification #' . $coachVerification->id . ' updated successfully.');
    }
}
