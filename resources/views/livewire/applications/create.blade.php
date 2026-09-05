<div>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Neue Bewerbung</h1>

        <form wire:submit="save" class="space-y-6">
            <div class="relative">
                <label class="block text-sm font-medium mb-1">Firma</label>
                <input type="text" wire:model.live.debounce.300ms="companyName"
                    class="w-full rounded-lg border-gray-300" placeholder="z. B. Google Austria GmbH">
                @error('companyName')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror

                @if (count($companySuggestions) > 0)
                    <ul class="absolute z-10 w-full bg-white border rounded-lg mt-1 shadow-lg">
                        @foreach ($companySuggestions as $suggestion)
                            <li wire:click="selectCompany({{ $suggestion['id'] }}, '{{ $suggestion['name'] }}')"
                                class="px-3 py-2 hover:bg-gray-100 cursor-pointer">
                                {{ $suggestion['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <fieldset class="border rounded-lg p-4">
                <legend class="text-sm font-medium px-2">Ansprechpartner (optional)</legend>

                <div class="relative mb-3">
                    <input type="text" wire:model.live.debounce.300ms="contactName"
                        class="w-full rounded-lg border-gray-300" placeholder="Name">

                    @if (count($contactSuggestions) > 0)
                        <ul class="absolute z-10 w-full bg-white border rounded-lg mt-1 shadow-lg">
                            @foreach ($contactSuggestions as $suggestion)
                                <li wire:click="selectContact({{ $suggestion['id'] }})"
                                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer">
                                    {{ $suggestion['name'] }} @if ($suggestion['email'])
                                        ({{ $suggestion['email'] }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <input type="email" wire:model="contactEmail" class="w-full rounded-lg border-gray-300 mb-3" placeholder="E-Mail">
                <input type="text" wire:model="contactPhone" class="w-full rounded-lg border-gray-300 mb-3" placeholder="Telefon">
                <input type="text" wire:model="contactPosition" class="w-full rounded-lg border-gray-300" placeholder="Position (z. B. HR)">
            </fieldset>

            <div>
                <label class="block text-sm font-medium mb-1">Position / Jobtitel</label>
                <input type="text" wire:model="jobTitle" class="w-full rounded-lg border-gray-300">
                @error('jobTitle')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Bewerbungsdatum</label>
                <input type="date" wire:model="applicationDate" class="w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Link zur Stellenausschreibung</label>
                <input type="url" wire:model="jobPostingUrl" class="w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Gehaltswunsch (€)</label>
                <input type="number" step="0.01" wire:model="desiredSalary" class="w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Bewerbungsart</label>
                <select wire:model="applicationType" class="w-full rounded-lg border-gray-300">
                    <option value="ausschreibung">Ausschreibung</option>
                    <option value="initiativ">Initiativ</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Quelle</label>
                <select wire:model="source" class="w-full rounded-lg border-gray-300">
                    <option value="linkedin">LinkedIn</option>
                    <option value="firmenwebsite">Firmenwebsite</option>
                    <option value="karriereportal">Karriereportal</option>
                    <option value="empfehlung">Empfehlung</option>
                    <option value="sonstiges">Sonstiges</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Notizen</label>
                <textarea wire:model="notes" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
            </div>

            <button type="submit" class="bg-brand-accent text-white px-4 py-2 rounded-lg">
                Bewerbung speichern
            </button>
        </form>
    </div>
</div>