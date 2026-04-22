<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $editingEquipmentId = $request->integer('edit');

        $equipmentsQuery = Equipment::query()
            ->withCount('salles')
            ->latest();

        if ($search !== '') {
            $equipmentsQuery->where(function ($query) use ($search): void {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        $equipments = $equipmentsQuery->paginate(10)->withQueryString();

        $editingEquipment = $editingEquipmentId
            ? Equipment::query()->find($editingEquipmentId)
            : null;

        return view('admin.management.equipments.index', [
            'equipments' => $equipments,
            'search' => $search,
            'editingEquipment' => $editingEquipment,
            'categoryOptions' => $this->categoryOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEquipment($request);

        $validated['image'] = $request->file('image')->store('equipments', 'public');

        Equipment::create($validated);

        return redirect()->route('management.equipments.index')
            ->with('success', 'Equipment created successfully.');
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $this->validateEquipment($request, $equipment->id);

        if ($request->hasFile('image')) {
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }

            $validated['image'] = $request->file('image')->store('equipments', 'public');
        } else {
            unset($validated['image']);
        }

        $equipment->update($validated);

        return redirect()->route('management.equipments.index')
            ->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        if ($equipment->salles()->exists()) {
            return redirect()->route('management.equipments.index')
                ->with('error', 'This equipment is still used by one or more salles.');
        }

        if ($equipment->image) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('management.equipments.index')
            ->with('success', 'Equipment deleted successfully.');
    }

    private function validateEquipment(Request $request, ?int $ignoreId = null): array
    {
        $categoryKeys = array_keys($this->categoryOptions());

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', $categoryKeys)],
            'image' => [
                $ignoreId ? 'nullable' : 'required',
                'image',
                'max:4096',
            ],
        ]);
    }

    private function categoryOptions(): array
    {
        return [
            'cardio' => 'Cardio Machines',
            'free_weights' => 'Free Weights',
            'machines' => 'Resistance Machines',
            'accessories' => 'Accessories & Mats',
        ];
    }
}
