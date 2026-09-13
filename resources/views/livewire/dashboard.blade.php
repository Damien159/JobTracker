<div class="max-w-6xl mx-auto p-6 space-y-6">
    {{-- Begrüßungs-Header --}}
    <div class="rounded-2xl bg-gradient-to-br from-brand-dark to-neutral-800 dark:from-neutral-900 dark:to-black p-6 text-white relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-sm text-white/70">Guten Tag </p>
            <h1 class="text-2xl font-bold mt-1">{{ auth()->user()->name }}</h1>
            <p class="text-sm text-white/70 mt-2">
                {{ $total }} {{ $total === 1 ? 'Bewerbung' : 'Bewerbungen' }} insgesamt · {{ $statusCounts->get('interview', 0) }} im Interview-Prozess
            </p>
        </div>
        <div class="absolute top-6 right-6 z-10">
            <flux:modal.trigger name="create-application-dashboard">
                <flux:button variant="primary" class="bg-brand-accent hover:bg-brand-accent/90">
                    + Neue Bewerbung
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Kennzahlen-Kacheln --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-5">
            <div class="w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center mb-3">
                <flux:icon name="briefcase" class="size-4 text-blue-600 dark:text-blue-400" />
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Bewerbungen</p>
            <p class="text-2xl font-bold mt-1">{{ $total }}</p>
        </div>

        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-5">
            <div class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center mb-3">
                <flux:icon name="chat-bubble-left-right" class="size-4 text-amber-600 dark:text-amber-400" />
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Interviews</p>
            <p class="text-2xl font-bold mt-1">{{ $statusCounts->get('interview', 0) }}</p>
        </div>

        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-5">
            <div class="w-9 h-9 rounded-lg bg-brand-accent/10 flex items-center justify-center mb-3">
                <flux:icon name="check-circle" class="size-4 text-brand-accent" />
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Zusagen</p>
            <p class="text-2xl font-bold mt-1 text-brand-accent">{{ $statusCounts->get('zusage', 0) }}</p>
        </div>

        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-5">
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center mb-3">
                <flux:icon name="chart-bar" class="size-4 text-emerald-600 dark:text-emerald-400" />
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Interview-Rate</p>
            <p class="text-2xl font-bold mt-1">{{ $interviewRate }}%</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid md:grid-cols-2 gap-6">
        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
            <h2 class="font-bold mb-4">Bewerbungsaktivität</h2>
            <canvas id="monthlyChart" height="220"></canvas>
        </div>

        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
            <h2 class="font-bold mb-4">Status-Verteilung</h2>
            <canvas id="statusChart" height="220"></canvas>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Letzte Aktivitäten --}}
        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold">Letzte Aktivitäten</h2>
                <a href="{{ route('applications.index') }}" wire:navigate class="text-brand-accent text-sm">Alle →</a>
            </div>
            @if ($recentActivity->isEmpty())
                <p class="text-sm text-gray-500">Noch keine Aktivitäten.</p>
            @else
                <ul class="divide-y divide-gray-100 dark:divide-neutral-800">
                    @foreach ($recentActivity as $activity)
                        <li class="py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium">
                                    {{ ucfirst($activity->status) }} – {{ $activity->application->company->name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $activity->application->job_title }}</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $activity->changed_at->format('d.m.Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Schnellaktionen --}}
        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
            <h2 class="font-bold mb-4">Schnellaktionen</h2>
            <div class="space-y-2">
                <flux:modal.trigger name="create-application-dashboard">
                    <button class="w-full flex items-center justify-between px-4 py-3 rounded-lg bg-brand-accent/10 text-brand-accent hover:bg-brand-accent/20 transition-colors">
                        <span class="flex items-center gap-2 text-sm font-medium">
                            <flux:icon name="plus-circle" class="size-4" />
                            Neue Bewerbung erstellen
                        </span>
                        <flux:icon name="chevron-right" class="size-4" />
                    </button>
                </flux:modal.trigger>
                <a href="{{ route('applications.index') }}" wire:navigate
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                    <span class="flex items-center gap-2 text-sm">
                        <flux:icon name="list-bullet" class="size-4 text-gray-400" />
                        Alle Bewerbungen ansehen
                    </span>
                    <flux:icon name="chevron-right" class="size-4 text-gray-400" />
                </a>
            </div>
        </div>
    </div>

    {{-- Aktive Bewerbungen --}}
    <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between p-6 pb-0">
            <div>
                <h2 class="font-bold">Neueste Bewerbungen</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ $recentApplications->count() }} von {{ $total }}</p>
            </div>
            <a href="{{ route('applications.index') }}" wire:navigate class="text-brand-accent text-sm">Alle anzeigen →</a>
        </div>
        <table class="w-full text-sm text-left mt-4">
            <thead class="bg-gray-50 dark:bg-neutral-950 border-y border-gray-200 dark:border-neutral-800">
                <tr>
                    <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Unternehmen</th>
                    <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Position</th>
                    <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide">Beworben</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                @php
                    $statusColors = [
                        'beworben' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                        'interview' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                        'zusage' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                        'absage' => 'bg-red-500/10 text-red-600 dark:text-red-400',
                    ];
                @endphp
                @foreach ($recentApplications as $application)
                    @php $status = $application->statusHistories->first()?->status ?? 'beworben'; @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('applications.show', $application->id) }}" wire:navigate class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-dark dark:bg-neutral-700 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($application->company->name, 0, 1)) }}
                                </div>
                                <span class="font-medium">{{ $application->company->name }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $application->job_title }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$status] ?? 'bg-gray-500/10 text-gray-600' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $application->application_date->format('d.m.Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <flux:modal name="create-application-dashboard" class="md:w-[600px]">
        @livewire('applications.create')
    </flux:modal>
</div>

@script
<script>
    const statusData = @json($statusCounts);
    const monthlyLabels = @json($monthlyLabels);
    const monthlyValues = @json($monthlyValues);

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Bewerbungen',
                data: monthlyValues,
                borderColor: '#FD105E',
                backgroundColor: 'rgba(253,16,94,0.08)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#FD105E',
            }],
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(128,128,128,0.1)' } },
                x: { grid: { display: false } },
            },
            plugins: { legend: { display: false } },
        },
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#3B82F6', '#F59E0B', '#FD105E', '#EF4444'],
                borderWidth: 0,
            }],
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' } },
            },
        },
    });
</script>
@endscript