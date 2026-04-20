<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $editingServiceId = $request->integer('edit');

        $servicesQuery = Service::query()
            ->withCount('salles')
            ->latest();

        if ($search !== '') {
            $servicesQuery->where('title', 'ilike', "%{$search}%");
        }

        $services = $servicesQuery
            ->paginate(10)
            ->withQueryString();

        $editingService = $editingServiceId
            ? Service::query()->withCount('salles')->find($editingServiceId)
            : null;

        return view('admin.management.services.index', [
            'services' => $services,
            'search' => $search,
            'editingService' => $editingService,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('services', 'title')],
        ]);

        Service::create($validated);

        return redirect()
            ->route('management.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('services', 'title')->ignore($service->id)],
        ]);

        $service->update($validated);

        return redirect()
            ->route('management.services.index', ['edit' => $service->id])
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->salles()->exists()) {
            return redirect()
                ->route('management.services.index', ['edit' => $service->id])
                ->with('error', 'This service is still assigned to some salles.');
        }

        $service->delete();

        return redirect()
            ->route('management.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
