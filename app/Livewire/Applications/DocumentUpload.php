<?php

namespace App\Livewire\Applications;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;


class DocumentUpload extends Component
{
    use WithFileUploads;

    public Application $application;
    public $file = null;
    public string $type = 'lebenslauf';

    public function upload(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'type' => ['required', 'in:lebenslauf,anschreiben,sonstiges'],
        ]);

        $path = $this->file->store('application-documents/' . $this->application->id, 'local');

        ApplicationDocument::create([
            'application_id' => $this->application->id,
            'type' => $this->type,
            'original_filename' => $this->file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $this->file->getMimeType(),
            'size' => $this->file->getSize(),
            'uploaded_at' => now(),
        ]);

        $this->reset('file');
        $this->dispatch('document-uploaded');
    }

    public function delete(int $documentId): void
    {
        $document = ApplicationDocument::where('application_id', $this->application->id)->findOrFail($documentId);

        Storage::disk('local')->delete($document->path);
        $document->delete();

        $this->dispatch('document-uploaded');
    }

    public function render()
    {
        $documents = $this->application->documents()->latest('uploaded_at')->get();

        return view('livewire.applications.document-upload', [
            'documents' => $documents,
        ]);
    }
}