<div wire:key="document-upload-{{ $application->id }}" class="space-y-4">
    <form wire:submit="saveDocument" class="space-y-3">

        <div>
            <label class="block text-sm font-medium mb-1">
                Dokumenttyp
            </label>

            <select wire:model="type" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="lebenslauf">Lebenslauf</option>
                <option value="anschreiben">Anschreiben</option>
                <option value="sonstiges">Sonstiges</option>
            </select>
        </div>

        <div>
            <input type="file" wire:model="file" wire:key="file-input-{{ $application->id }}"
                accept=".pdf,.doc,.docx" class="text-sm">

            @error('file')
                <span class="text-red-600 text-xs">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div wire:loading wire:target="file" class="text-xs text-gray-500">
            Wird hochgeladen...
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="file,saveDocument"
            class="bg-brand-accent text-white px-3 py-1.5 rounded-lg text-sm">
            Hochladen
        </button>
    </form>

    @if ($documents->isNotEmpty())
        <ul class="space-y-2">
            @foreach ($documents as $document)
                <li
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg border border-gray-200 dark:border-neutral-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-red-500/10 flex items-center justify-center shrink-0">
                            <span class="text-red-600 text-[10px] font-bold">PDF</span>
                        </div>
                        <span class="text-sm font-medium">{{ $document->original_filename }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('documents.download', $document->id) }}" class="text-brand-accent text-xs"
                            target="_blank">
                            Download
                        </a>
                        <button wire:click="delete({{ $document->id }})" wire:confirm="Dokument wirklich löschen?"
                            class="text-red-600 text-xs">
                            Löschen
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
