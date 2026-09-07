<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public Application $application;

    public function mount(int $applicationId): void
    {
        $this->application = Application::query()
            ->where('user_id', Auth::id())
            ->with(['company', 'contact', 'statusHistories'])
            ->findOrFail($applicationId);
    }

    #[On('application-updated')]
    public function refresh(): void
    {
        $this->application->refresh();
        $this->application->load(['company', 'contact', 'statusHistories']);
    }

    public function delete(): void
    {
        $this->application->delete();

        session()->flash('success', 'Bewerbung wurde gelöscht.');
        $this->redirect(route('applications.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.applications.show');
    }
}