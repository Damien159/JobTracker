<div>
    <div class="max-w-5xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Meine Bewerbungen</h1>
            <flux:modal.trigger name="create-application">
                <flux:button variant="primary" class="bg-brand-accent">
                    + Neue Bewerbung
                </flux:button>
            </flux:modal.trigger>
        </div>
        @if ($applications->isEmpty())
            <div class="text-center py-16 border rounded-lg border-dashed">
                <p class="text-gray-500">Noch keine Bewerbungen erfasst.</p>
                <flux:modal.trigger name="create-application">
                    <flux:button variant="ghost" class="text-brand-accent">
                        Erste Bewerbung anlegen
                    </flux:button>
                </flux:modal.trigger>
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
                                <td class="px-4 py-3">
                                    <a href="{{ route('applications.show', $application->id) }}" wire:navigate
                                        class="hover:text-brand-accent">
                                        {{ $application->company->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('applications.show', $application->id) }}" wire:navigate
                                        class="hover:text-brand-accent">
                                        {{ $application->job_title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $application->company->name }}</td>
                                <td class="px-4 py-3">{{ $application->job_title }}</td>
                                <td class="px-4 py-3">
                                    <select wire:change="updateStatus({{ $application->id }}, $event.target.value)"
                                        class="text-xs rounded-full border-gray-300 bg-brand-gray/20 text-brand-dark dark:text-white px-2 py-1">
                                        @foreach (['beworben', 'interview', 'zusage', 'absage'] as $statusOption)
                                            <option value="{{ $statusOption }}" @selected($application->statusHistories->first()?->status === $statusOption)>
                                                {{ ucfirst($statusOption) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">{{ $application->application_date->format('d.m.Y') }}</td>
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
