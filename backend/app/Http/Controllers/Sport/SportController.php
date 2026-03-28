<?php

namespace App\Http\Controllers\Sport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sport\StoreSportRequest;
use App\Http\Requests\Sport\UpdateSportRequest;
use App\Models\sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SportController extends Controller
{
    public function index(): View
    {
        return view('sports.index', [
            'sports' => sport::latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('sports.create');
    }

    public function store(StoreSportRequest $request): RedirectResponse
    {
        sport::create($request->validated());
        return redirect()->route('sports.index')->with('success', 'Sport created successfully.');
    }

    public function show(sport $sport): View
    {
        return view('sports.show', compact('sport'));
    }

    public function edit(sport $sport): View
    {
        return view('sports.edit', compact('sport'));
    }

    public function update(UpdateSportRequest $request, sport $sport): RedirectResponse
    {
        $sport->update($request->validated());
        return redirect()->route('sports.index')->with('success', 'Sport updated successfully.');
    }

    public function destroy(sport $sport): RedirectResponse
    {
        if ($sport->salles()->exists()) {
            return redirect()->route('sports.index')->with('error', 'Sport cannot be deleted because it is assigned to salles.');
        }

        $sport->delete();
        return redirect()->route('sports.index')->with('success', 'Sport deleted successfully.');
    }
}
