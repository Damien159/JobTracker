<div>
    <div class="max-w-5xl mx-auto p-6 space-y-6">
        <a href="{{ route('applications.index') }}" wire:navigate
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-brand-accent transition-colors">
            <flux:icon name="arrow-left" class="size-4" />
            Zurück zu Bewerbungen
        </a>

        @php
            $statusColors = [
                'beworben' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                'interview' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                'zusage' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                'absage' => 'bg-red-500/10 text-red-600 dark:text-red-400',
            ];
            $currentStatus = $application->statusHistories->first()?->status ?? 'beworben';
        @endphp

        {{-- Kopfbereich --}}
        <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-brand-dark dark:bg-neutral-700 text-white flex items-center justify-center text-base font-bold shrink-0">
                        {{ strtoupper(substr($application->company->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">{{ $application->job_title }}</h1>
                        <p class="text-gray-500 text-sm">{{ $application->company->name }}</p>
                        <span class="inline-flex items-center h-6 px-2.5 mt-2 rounded-full text-xs font-medium {{ $statusColors[$currentStatus] ?? 'bg-gray-500/10 text-gray-600' }}">
                            {{ ucfirst($currentStatus) }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <flux:modal.trigger name="edit-application">
                        <flux:button variant="ghost" class="h-9"
                            wire:click="$dispatch('open-edit-modal', { applicationId: {{ $application->id }} })">
                            Bearbeiten
                        </flux:button>
                    </flux:modal.trigger>
                    <flux:modal.trigger name="delete-confirm">
                        <flux:button variant="danger" class="h-9">Löschen</flux:button>
                    </flux:modal.trigger>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-neutral-800 text-sm">
                <div class="flex items-start gap-2">
                    <flux:icon name="calendar" class="size-4 text-gray-400 mt-0.5" />
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Beworben</p>
                        <p class="font-medium">{{ $application->application_date->format('d.m.Y') }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <flux:icon name="globe-alt" class="size-4 text-gray-400 mt-0.5" />
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Quelle</p>
                        <p class="font-medium">{{ ucfirst($application->source) }}</p>
                    </div>
                </div>
                @if ($application->desired_salary)
                    <div class="flex items-start gap-2">
                        <flux:icon name="currency-euro" class="size-4 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide">Gehalt</p>
                            <p class="font-medium">{{ number_format($application->desired_salary, 0, ',', '.') }} €</p>
                        </div>
                    </div>
                @endif
                @if ($application->contact)
                    <div class="flex items-start gap-2">
                        <flux:icon name="user" class="size-4 text-gray-400 mt-0.5" />
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wide">Kontakt</p>
                            <p class="font-medium">{{ $application->contact->name }}</p>
                        </div>
                    </div>
                @endif
            </div>

            @if ($application->job_posting_url)
                <a href="{{ $application->job_posting_url }}" target="_blank"
                    class="inline-flex items-center gap-1 text-brand-accent text-sm mt-4">
                    Zur Stellenausschreibung
                    <flux:icon name="arrow-top-right-on-square" class="size-3.5" />
                </a>
            @endif
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Hauptspalte --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Status-Verlauf --}}
                <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
                    <h2 class="font-bold mb-4">Status-Verlauf</h2>
                    <ul class="space-y-4">
                        @foreach ($application->statusHistories->sortBy('changed_at') as $history)
                            <li class="flex gap-3">
                                <div class="w-2 h-2 rounded-full bg-brand-accent mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="font-medium text-sm">{{ ucfirst($history->status) }}</p>
                                    <p class="text-xs text-gray-500">{{ $history->changed_at->format('d.m.Y') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Notizen --}}
                @if ($application->notes)
                    <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
                        <h2 class="font-bold mb-4">Notizen</h2>
                        <p class="text-sm whitespace-pre-line text-gray-600 dark:text-gray-300">{{ $application->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Seitenspalte --}}
            <div class="space-y-6">
                @if ($application->contact)
                    <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
                        <h2 class="font-bold mb-4">Ansprechpartner</h2>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gray/20 text-brand-dark dark:text-white flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($application->contact->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-sm">{{ $application->contact->name }}</p>
                                @if ($application->contact->position)
                                    <p class="text-xs text-gray-500">{{ $application->contact->position }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                            @if ($application->contact->email)
                                <p class="flex items-center gap-2">
                                    <flux:icon name="envelope" class="size-4 text-gray-400" />
                                    {{ $application->contact->email }}
                                </p>
                            @endif
                            @if ($application->contact->phone)
                                <p class="flex items-center gap-2">
                                    <flux:icon name="phone" class="size-4 text-gray-400" />
                                    {{ $application->contact->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Dokumente --}}
                <div class="border border-gray-200 dark:border-neutral-800 rounded-xl bg-white dark:bg-neutral-900 shadow-sm p-6">
                    <h2 class="font-bold mb-4">Dokumente</h2>
                    <livewire:applications.document-upload :application="$application" :key="'document-upload-' . $application->id" />
                </div>
            </div>
        </div>
    </div>

    <flux:modal name="edit-application" class="md:w-[600px]">
        @livewire('applications.edit')
    </flux:modal>

    <flux:modal name="delete-confirm" class="md:w-96">
        <div class="space-y-4">
            <flux:heading size="lg">Bewerbung löschen?</flux:heading>
            <p class="text-sm text-gray-500">
                Diese Aktion kann nicht rückgängig gemacht werden. Die Bewerbung und ihr Verlauf werden endgültig
                gelöscht.
            </p>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost">Abbrechen</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="delete">Endgültig löschen</flux:button>
            </div>
        </div>
    </flux:modal>
</div>