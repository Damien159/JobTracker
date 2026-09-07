<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('application-created')]
    public function refreshList(): void {}
    public function updateStatus(int $applicationId, string $newStatus): void
    {
        $application = Application::where('user_id', Auth::id())
            ->findOrFail($applicationId);

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
            ->latest('application_date')
            ->get();

        return view('livewire.applications.index', [
            'applications' => $applications,
        ]);
    }
}
