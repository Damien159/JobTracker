<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $statusFilter = '';
    public string $sortBy = 'date';

    #[On('application-created')]
    public function refreshList(): void
    {
        //
    }

    public function updateStatus(int $applicationId, string $newStatus): void
    {
        $application = Application::where('user_id', Auth::id())->findOrFail($applicationId);

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'status' => $newStatus,
            'changed_at' => now(),
        ]);
    }

    public function render()
    {
        $applications = Application::query()
            ->where('user_id', Auth::id())
            ->with(['company', 'statusHistories'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('job_title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->whereHas('statusHistories', function ($q) {
                    $q->where('status', $this->statusFilter)
                        ->whereIn('id', function ($sub) {
                            $sub->selectRaw('MAX(id)')
                                ->from('application_status_histories')
                                ->groupBy('application_id');
                        });
                });
            })
            ->when($this->sortBy === 'company', fn($q) => $q->join('companies', 'companies.id', '=', 'applications.company_id')->orderBy('companies.name')->select('applications.*'))
            ->when($this->sortBy === 'date', fn($q) => $q->latest('application_date'))
            ->get();

        return view('livewire.applications.index', [
            'applications' => $applications,
        ]);
    }
}
