<?php

namespace App\Livewire;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $applications = Application::where('user_id', Auth::id())
            ->with('statusHistories')
            ->get();

        $total = $applications->count();

        $statusCounts = $applications
            ->map(fn ($app) => $app->statusHistories->first()?->status ?? 'beworben')
            ->countBy();

        $interviewOrBetter = $statusCounts->only(['interview', 'zusage'])->sum();
        $interviewRate = $total > 0 ? round(($interviewOrBetter / $total) * 100) : 0;

        $monthlyData = $applications
            ->groupBy(fn ($app) => $app->application_date->format('Y-m'))
            ->map->count()
            ->sortKeys()
            ->slice(-6);

        $recentActivity = ApplicationStatusHistory::query()
            ->whereHas('application', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['application.company'])
            ->latest('changed_at')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', [
            'total' => $total,
            'statusCounts' => $statusCounts,
            'interviewRate' => $interviewRate,
            'monthlyLabels' => $monthlyData->keys(),
            'monthlyValues' => $monthlyData->values(),
            'recentActivity' => $recentActivity,
            'recentApplications' => $applications->sortByDesc('application_date')->take(5),
        ]);
    }
}
