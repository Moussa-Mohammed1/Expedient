<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Equipment;
use App\Models\Service;
use App\Models\Speciality;
use App\Models\Sport;
use Illuminate\View\View;

class ManagementController extends Controller
{
    public function index(): View
    {
        $categoryLabels = [
            'cardio' => 'Cardio Machines',
            'free_weights' => 'Free Weights',
            'machines' => 'Resistance Machines',
            'accessories' => 'Accessories & Mats',
        ];

        return view('admin.management.index', [
            'sportsCount' => Sport::query()->count(),
            'servicesCount' => Service::query()->count(),
            'specialitiesCount' => Speciality::query()->count(),
            'equipmentsCount' => Equipment::query()->count(),
            'recentSports' => Sport::query()->latest()->limit(4)->get(['id', 'title', 'icon']),
            'recentServices' => Service::query()->latest()->limit(4)->get(['id', 'title']),
            'recentSpecialities' => Speciality::query()->latest()->limit(3)->get(['id', 'title']),
            'recentEquipments' => Equipment::query()->latest()->limit(3)->get(['id', 'name', 'image', 'category']),
            'categoryLabels' => $categoryLabels,
        ]);
    }
}
