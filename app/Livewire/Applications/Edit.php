<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    public ?int $applicationId = null;

    public string $companyName = '';
    public string $companyWebsite = '';
    public array $companySuggestions = [];
    public ?int $selectedCompanyId = null;

    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';
    public string $contactPosition = '';
    public array $contactSuggestions = [];
    public ?int $selectedContactId = null;

    public string $jobTitle = '';
    public string $applicationDate = '';
    public string $jobPostingUrl = '';
    public string $notes = '';
    public ?float $desiredSalary = null;
    public string $applicationType = 'ausschreibung';
    public string $source = 'linkedin';

    #[On('open-edit-modal')]
    public function loadApplication(int $applicationId): void
    {
        $application = Application::where('user_id', Auth::id())
            ->with(['company', 'contact'])
            ->findOrFail($applicationId);

        $this->applicationId = $application->id;
        $this->selectedCompanyId = $application->company_id;
        $this->companyName = $application->company->name;
        $this->companyWebsite = $application->company->website ?? '';

        $this->selectedContactId = $application->contact_id;
        $this->contactName = $application->contact?->name ?? '';
        $this->contactEmail = $application->contact?->email ?? '';
        $this->contactPhone = $application->contact?->phone ?? '';
        $this->contactPosition = $application->contact?->position ?? '';

        $this->jobTitle = $application->job_title;
        $this->applicationDate = $application->application_date->format('Y-m-d');
        $this->jobPostingUrl = $application->job_posting_url ?? '';
        $this->notes = $application->notes ?? '';
        $this->desiredSalary = $application->desired_salary;
        $this->applicationType = $application->application_type;
        $this->source = $application->source;

        $this->dispatch('open-modal', name: 'edit-application');
    }

    public function updatedCompanyName(): void
    {
        $this->selectedCompanyId = null;

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

        $application = Application::where('user_id', Auth::id())->findOrFail($this->applicationId);

        $company = $this->selectedCompanyId
            ? Company::findOrFail($this->selectedCompanyId)
            : Company::create(['name' => $this->companyName, 'website' => $this->companyWebsite ?: null]);

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

        $application->update([
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

        $this->dispatch('application-updated');
        $this->dispatch('close-modal', name: 'edit-application');
    }

    public function render()
    {
        return view('livewire.applications.edit');
    }
}