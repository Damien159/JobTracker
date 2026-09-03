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
