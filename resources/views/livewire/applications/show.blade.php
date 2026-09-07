<div>
    <div class="max-w-5xl mx-auto p-6 space-y-6">
        <a href="{{ route('applications.index') }}" wire:navigate class="text-sm text-brand-accent">
            ← Zurück zu Bewerbungen
        </a>

        {{-- Kopfbereich --}}
        <div class="border rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ $application->job_title }}</h1>
                    <p class="text-gray-500">{{ $application->company->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs bg-brand-gray/20 text-brand-dark dark:text-white">
                        {{ ucfirst($application->statusHistories->first()?->status ?? '—') }}
                    </span>
                    <flux:button variant="ghost"
                        wire:click="$dispatch('open-edit-modal', { applicationId: {{ $application->id }} })">
                        Bearbeiten
                    </flux:button>
                    <flux:modal.trigger name="delete-confirm">
                        <flux:button variant="danger">Löschen</flux:button>
                    </flux:modal.trigger>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-sm">
                <div>
                    <p class="text-gray-500">Beworben</p>
                    <p class="font-medium">{{ $application->application_date->format('d.m.Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Bewerbungsart</p>
                    <p class="font-medium">{{ ucfirst($application->application_type) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Quelle</p>
                    <p class="font-medium">{{ ucfirst($application->source) }}</p>
                </div>
                @if ($application->desired_salary)
                    <div>
                        <p class="text-gray-500">Gehaltswunsch</p>
                        <p class="font-medium">{{ number_format($application->desired_salary, 0, ',', '.') }} €</p>
                    </div>
                @endif
            </div>

            @if ($application->job_posting_url)
                <a href="{{ $application->job_posting_url }}" target="_blank"
                    class="text-brand-accent text-sm mt-4 inline-block">
                    Zur Stellenausschreibung →
                </a>
            @endif
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                {{-- Status-Verlauf --}}
                <div class="border rounded-lg p-6">
                    <h2 class="font-bold mb-4">Status-Verlauf</h2>
                    <ul class="space-y-4">
                        @foreach ($application->statusHistories->sortBy('changed_at') as $history)
                            <li class="flex gap-3">
                                <div class="w-2 h-2 rounded-full bg-brand-accent mt-1.5 shrink-0"></div>
                                <div>
                                    <p class="font-medium">{{ ucfirst($history->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $history->changed_at->format('d.m.Y') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Notizen --}}
                @if ($application->notes)
                    <div class="border rounded-lg p-6">
                        <h2 class="font-bold mb-4">Notizen</h2>
                        <p class="text-sm whitespace-pre-line">{{ $application->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Ansprechpartner --}}
            <div class="space-y-6">
                @if ($application->contact)
                    <div class="border rounded-lg p-6">
                        <h2 class="font-bold mb-4">Ansprechpartner</h2>
                        <p class="font-medium">{{ $application->contact->name }}</p>
                        @if ($application->contact->position)
                            <p class="text-sm text-gray-500">{{ $application->contact->position }}</p>
                        @endif
                        @if ($application->contact->email)
                            <p class="text-sm mt-2">{{ $application->contact->email }}</p>
                        @endif
                        @if ($application->contact->phone)
                            <p class="text-sm">{{ $application->contact->phone }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
