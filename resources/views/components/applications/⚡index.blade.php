<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $applications = Application::query()
            ->where('user_id', Auth::id())
            ->with(['company', 'statusHistories'])
            ->latest('application_date')
            ->get();

        return view('livewire.applications.index', [
            'applications' => $applications,
        ]);
    }
}
?>

<div>
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Meine Bewerbungen</h1>
            <a href="{{ route('applications.create') }}" wire:navigate
                class="bg-brand-accent text-white px-4 py-2 rounded-lg text-sm font-medium">
                + Neue Bewerbung
            </a>
        </div>

        @if ($applications->isEmpty())
            <div class="text-center py-16 border rounded-lg border-dashed">
                <p class="text-gray-500">Noch keine Bewerbungen erfasst.</p>
                <a href="{{ route('applications.create') }}" wire:navigate class="text-brand-accent font-medium">
                    Erste Bewerbung anlegen
                </a>
            </div>
        @else
            <div class="overflow-x-auto border rounded-lg">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-neutral-900 border-b">
                        <tr>
                            <th class="px-4 py-3 font-medium">Firma</th>
                            <th class="px-4 py-3 font-medium">Position</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Datum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                            <tr class="border-b last:border-0 hover:bg-gray-50 dark:hover:bg-neutral-900">
                                <td class="px-4 py-3">{{ $application->company->name }}</td>
                                <td class="px-4 py-3">{{ $application->job_title }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-brand-gray/20 text-brand-dark dark:text-white">
                                        {{ $application->statusHistories->first()?->status ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $application->application_date->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
