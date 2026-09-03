<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    // Firma
    public string $companyName = '';
    public string $companyWebsite = '';
    public array $companySuggestions = [];
    public ?int $selectedCompanyId = null;

    // Kontakt
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';
    public string $contactPosition = '';
    public array $contactSuggestions = [];
    public ?int $selectedContactId = null;

    // Bewerbung
    public string $jobTitle = '';
    public string $applicationDate;
    public string $jobPostingUrl = '';
    public string $notes = '';
    public ?float $desiredSalary = null;
    public string $applicationType = 'ausschreibung';
    public string $source = 'linkedin';

    public function mount(): void
    {
        $this->applicationDate = now()->format('Y-m-d');
    }

    // Wird bei jedem Tastendruck im Firmenfeld ausgelöst
    public function updatedCompanyName(): void
    {
        $this->selectedCompanyId = null; // vorherige Auswahl verwerfen, falls Name geändert wird

        if (strlen($this->companyName) < 2) {
            $this->companySuggestions = [];
            return;
        }

        $this->companySuggestions = Company::where('name', 'like', '%' . $this->companyName . '%')
            ->limit(5)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function selectCompany(int $companyId, string $companyName): void
    {
        $this->selectedCompanyId = $companyId;
        $this->companyName = $companyName;
        $this->companySuggestions = [];

        // Kontakte dieser Firma laden für Kontakt-Autocomplete
        $this->loadContactSuggestionsForCompany($companyId);
    }

    public function updatedContactName(): void
    {
        $this->selectedContactId = null;

        if ($this->selectedCompanyId === null || strlen($this->contactName) < 2) {
            $this->contactSuggestions = [];
            return;
        }

        $this->contactSuggestions = Contact::where('company_id', $this->selectedCompanyId)
            ->where('name', 'like', '%' . $this->contactName . '%')
            ->limit(5)
            ->get(['id', 'name', 'email', 'phone', 'position'])
            ->toArray();
    }

    public function selectContact(int $contactId): void
    {
        $contact = Contact::findOrFail($contactId);

        $this->selectedContactId = $contact->id;
        $this->contactName = $contact->name;
        $this->contactEmail = $contact->email ?? '';
        $this->contactPhone = $contact->phone ?? '';
        $this->contactPosition = $contact->position ?? '';
        $this->contactSuggestions = [];
    }

    private function loadContactSuggestionsForCompany(int $companyId): void
    {
        $this->contactSuggestions = Contact::where('company_id', $companyId)
            ->limit(5)
            ->get(['id', 'name', 'email', 'phone', 'position'])
            ->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'companyName' => ['required', 'string', 'max:255'],
            'contactName' => ['nullable', 'string', 'max:255'],
            'contactEmail' => ['nullable', 'email', 'max:255'],
            'jobTitle' => ['required', 'string', 'max:255'],
            'applicationDate' => ['required', 'date'],
            'jobPostingUrl' => ['nullable', 'url', 'max:2048'],
            'desiredSalary' => ['nullable', 'numeric', 'min:0'],
            'applicationType' => ['required', 'in:initiativ,ausschreibung'],
            'source' => ['required', 'in:linkedin,firmenwebsite,karriereportal,empfehlung,sonstiges'],
        ]);

        // Firma: bestehende verwenden, oder neue anlegen (find-or-create)
        $company = $this->selectedCompanyId
            ? Company::findOrFail($this->selectedCompanyId)
            : Company::create([
                'name' => $this->companyName,
                'website' => $this->companyWebsite ?: null,
            ]);

        // Kontakt: nur wenn Name angegeben wurde
        $contact = null;
        if (!empty($this->contactName)) {
            $contact = $this->selectedContactId
                ? Contact::findOrFail($this->selectedContactId)
                : Contact::create([
                    'company_id' => $company->id,
                    'name' => $this->contactName,
                    'email' => $this->contactEmail ?: null,
                    'phone' => $this->contactPhone ?: null,
                    'position' => $this->contactPosition ?: null,
                ]);
        }

        $application = Application::create([
            'user_id' => Auth::id(),
            'company_id' => $company->id,
            'contact_id' => $contact?->id,
            'job_title' => $this->jobTitle,
            'application_date' => $this->applicationDate,
            'job_posting_url' => $this->jobPostingUrl ?: null,
            'notes' => $this->notes ?: null,
            'desired_salary' => $this->desiredSalary,
            'application_type' => $this->applicationType,
            'source' => $this->source,
        ]);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'status' => 'beworben',
            'changed_at' => now(),
        ]);

        session()->flash('success', 'Bewerbung wurde angelegt.');

        $this->redirect(route('applications.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.applications.create');
    }
}
?>
<div>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Neue Bewerbung</h1>

        <form wire:submit="save" class="space-y-6">

            {{-- Firma --}}
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

            {{-- Ansprechpartner --}}
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

                <input type="email" wire:model="contactEmail" class="w-full rounded-lg border-gray-300 mb-3"
                    placeholder="E-Mail">
                <input type="text" wire:model="contactPhone" class="w-full rounded-lg border-gray-300 mb-3"
                    placeholder="Telefon">
                <input type="text" wire:model="contactPosition" class="w-full rounded-lg border-gray-300"
                    placeholder="Position (z. B. HR)">
            </fieldset>

            {{-- Bewerbungsdetails --}}
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
                <input type="number" step="0.01" wire:model="desiredSalary"
                    class="w-full rounded-lg border-gray-300">
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

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Bewerbung speichern
            </button>
        </form>
    </div>
</div>