<?php

namespace App\Livewire;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $applications = Application::where('user_id', Auth::id())
            ->with('statusHistories')
            ->get();

        $total = $applications->count();

        $statusCounts = $applications
            ->map(fn($app) => $app->statusHistories->first()?->status ?? 'beworben')
            ->countBy();

        $interviewOrBetter = $statusCounts->only(['interview', 'zusage'])->sum();
        $interviewRate = $total > 0 ? round(($interviewOrBetter / $total) * 100) : 0;

        // Bewerbungen pro Monat (letzte 6 Monate)
        $monthlyData = $applications
            ->groupBy(fn($app) => $app->application_date->format('Y-m'))
            ->map->count()
            ->sortKeys()
            ->slice(-6);

        return view('livewire.dashboard', [
            'total' => $total,
            'statusCounts' => $statusCounts,
            'interviewRate' => $interviewRate,
            'monthlyLabels' => $monthlyData->keys(),
            'monthlyValues' => $monthlyData->values(),
        ]);
    }
}
