<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $recentPosts = Post::query()
            ->with(['user:id,name,avatar', 'community:id,title'])
            ->latest()
            ->take(3)
            ->get();

        $recentReports = Report::query()
            ->with('reporter:id,name')
            ->latest()
            ->take(2)
            ->get();

        $pendingReportsCount = Report::query()
            ->where('isCancelled', false)
            ->count();

        $recentUsers = User::query()
            ->with('role:id,title')
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'totalUsers' => User::query()->count(),
            'totalPosts' => Post::query()->count(),
            'activeCommunities' => Community::query()->count(),
            'coveredCities' => User::query()
                ->whereNotNull('localisation')
                ->where('localisation', '!=', '')
                ->distinct()
                ->count('localisation'),
        ];

        return view('admin.dashboard.index', compact('stats', 'recentUsers', 'recentReports', 'pendingReportsCount', 'recentPosts'));
    }
}
