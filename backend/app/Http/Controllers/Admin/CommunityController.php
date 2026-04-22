<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $localisation = trim((string) $request->query('localisation', 'all'));
        $editingCommunityId = $request->integer('edit');

        $communitiesQuery = Community::query()
            ->withCount([
                'memberships as active_members_count' => fn ($query) => $query->whereNull('left_at'),
                'posts',
            ])
            ->latest();

        if ($search !== '') {
            $communitiesQuery->where(function ($query) use ($search): void {
                $query->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        if ($localisation !== '' && strtolower($localisation) !== 'all') {
            $communitiesQuery->where('localisation', 'ilike', $localisation);
        }

        $communities = $communitiesQuery
            ->paginate(10)
            ->withQueryString();

        $editingCommunity = $editingCommunityId
            ? Community::query()->find($editingCommunityId)
            : null;

        $localisationOptions = Community::query()
            ->whereNotNull('localisation')
            ->where('localisation', '<>', '')
            ->selectRaw('MIN(localisation) as localisation')
            ->groupByRaw('LOWER(localisation)')
            ->orderBy('localisation')
            ->pluck('localisation');

        return view('admin.communities.index', [
            'communities' => $communities,
            'search' => $search,
            'localisation' => $localisation,
            'localisationOptions' => $localisationOptions,
            'editingCommunity' => $editingCommunity,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCommunity($request);

        if ($request->hasFile('backgroundImage')) {
            $validated['backgroundImage'] = $request->file('backgroundImage')->store('communities', 'public');
        }

        Community::create($validated);

        return redirect()
            ->route('admin.communities')
            ->with('success', 'Community created successfully.');
    }

    public function update(Request $request, Community $community): RedirectResponse
    {
        $validated = $this->validateCommunity($request, $community->id);

        if ($request->hasFile('backgroundImage')) {
            if ($community->backgroundImage) {
                Storage::disk('public')->delete($community->backgroundImage);
            }

            $validated['backgroundImage'] = $request->file('backgroundImage')->store('communities', 'public');
        } elseif ($request->boolean('remove_background')) {
            if ($community->backgroundImage) {
                Storage::disk('public')->delete($community->backgroundImage);
            }

            $validated['backgroundImage'] = null;
        }

        $community->update($validated);

        return redirect()
            ->route('admin.communities', ['edit' => $community->id])
            ->with('success', 'Community updated successfully.');
    }

    public function destroy(Community $community): RedirectResponse
    {
        if ($community->memberships()->whereNull('left_at')->exists()) {
            return redirect()
                ->route('admin.communities', ['edit' => $community->id])
                ->with('error', 'This community still has active members and cannot be deleted.');
        }

        if ($community->posts()->exists()) {
            return redirect()
                ->route('admin.communities', ['edit' => $community->id])
                ->with('error', 'This community still contains posts and cannot be deleted.');
        }

        if ($community->backgroundImage) {
            Storage::disk('public')->delete($community->backgroundImage);
        }

        $community->delete();

        return redirect()
            ->route('admin.communities')
            ->with('success', 'Community deleted successfully.');
    }

    private function validateCommunity(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('communities', 'title')->ignore($ignoreId),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'localisation' => ['nullable', 'string', 'max:255'],
            'backgroundImage' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
