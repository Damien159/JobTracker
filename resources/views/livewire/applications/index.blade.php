<div>
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex items-center justify-between mb-4 gap-3">
            <div class="flex items-center gap-2">
                <div class="relative w-64">
                    <flux:icon name="magnifying-glass"
                        class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Suchen..."
                        class="w-full h-10 pl-9 pr-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm focus:ring-2 focus:ring-brand-accent focus:border-transparent">
                </div>

                <select wire:model.live="statusFilter"
                    class="h-10 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm px-3 focus:ring-2 focus:ring-brand-accent">
                    <option value="">Alle Status</option>
                    <option value="beworben">Beworben</option>
                    <option value="interview">Interview</option>
                    <option value="zusage">Zusage</option>
                    <option value="absage">Absage</option>
                </select>

                <select wire:model.live="sortBy"
                    class="h-10 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm px-3 focus:ring-2 focus:ring-brand-accent">
                    <option value="date">Neueste zuerst</option>
                    <option value="company">Nach Firma</option>
                </select>
            </div>

            <flux:modal.trigger name="create-application">
                <flux:button variant="primary" class="bg-brand-accent hover:bg-brand-accent/90 whitespace-nowrap h-10">
                    + Neue Bewerbung
                </flux:button>
            </flux:modal.trigger>
        </div>

        <p class="text-sm text-gray-500 mb-4">
            {{ $applications->count() }} {{ $applications->count() === 1 ? 'Bewerbung' : 'Bewerbungen' }}
        </p>

        @if ($applications->isEmpty())
            <div class="text-center py-20 border-2 border-dashed rounded-xl border-gray-200 dark:border-neutral-800">
                <div class="w-12 h-12 rounded-full bg-brand-accent/10 flex items-center justify-center mx-auto mb-4">
                    <flux:icon name="briefcase" class="size-6 text-brand-accent" />
                </div>
                <p class="text-gray-500 mb-3">Noch keine Bewerbungen erfasst.</p>
                <flux:modal.trigger name="create-application">
                    <flux:button variant="ghost" class="text-brand-accent">
                        Erste Bewerbung anlegen
                    </flux:button>
                </flux:modal.trigger>
            </div>
        @else
            <div
                class="w-full border border-gray-200 dark:border-neutral-800 rounded-xl overflow-hidden bg-white dark:bg-neutral-900 shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-neutral-950 border-b border-gray-200 dark:border-neutral-800">
                        <tr>
                            <th
                                class="px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide whitespace-nowrap">
                                Unternehmen</th>
                            <th
                                class="px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide whitespace-nowrap">
                                Position</th>
                            <th
                                class="px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide whitespace-nowrap">
                                Status</th>
                            <th
                                class="px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide whitespace-nowrap">
                                Kontakt</th>
                            <th
                                class="px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wide whitespace-nowrap">
                                Beworben</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                        @foreach ($applications as $application)
                            @php
                                $statusColors = [
                                    'beworben' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                    'interview' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                    'zusage' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                    'absage' => 'bg-red-500/10 text-red-600 dark:text-red-400',
                                ];
                                $currentStatus = $application->statusHistories->first()?->status ?? 'beworben';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('applications.show', $application->id) }}" wire:navigate
                                        class="flex items-center gap-3 group">
                                        <div
                                            class="w-9 h-9 rounded-full bg-brand-dark dark:bg-neutral-700 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($application->company->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium group-hover:text-brand-accent transition-colors">
                                            {{ $application->company->name }}
                                        </span>
                                    </a>
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    <a href="{{ route('applications.show', $application->id) }}" wire:navigate
                                        class="text-gray-600 dark:text-gray-300 hover:text-brand-accent transition-colors line-clamp-1">
                                        {{ $application->job_title }}
                                    </a>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="relative inline-block">
                                        <select wire:change="updateStatus({{ $application->id }}, $event.target.value)"
                                            class="appearance-none text-xs font-medium rounded-full border-0 py-1.5 pl-3 pr-8 cursor-pointer focus:ring-2 focus:ring-brand-accent {{ $statusColors[$currentStatus] ?? 'bg-gray-500/10 text-gray-600' }}">
                                            @foreach (['beworben', 'interview', 'zusage', 'absage'] as $statusOption)
                                                <option value="{{ $statusOption }}" @selected($currentStatus === $statusOption)
                                                    class="bg-white dark:bg-neutral-900 text-gray-900 dark:text-white">
                                                    {{ ucfirst($statusOption) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <flux:icon name="chevron-down"
                                            class="absolute right-2.5 top-1/2 -translate-y-1/2 size-3 pointer-events-none" />
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                    {{ $application->contact?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                    {{ $application->application_date->format('d.m.Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <flux:modal name="create-application" class="md:w-[600px]">
        @livewire('applications.create')
    </flux:modal>
</div>
