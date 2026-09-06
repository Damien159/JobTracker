<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
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

    public function render()
    {
        return view('livewire.applications.show');
    }
}