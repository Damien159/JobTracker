<div class="space-y-4">
    <form wire:submit="upload" class="space-y-3">
        <div>
            <label class="block text-sm font-medium mb-1">Dokumenttyp</label>
            <select wire:model="type" class="w-full rounded-lg border-gray-300 text-sm">
                <option value="lebenslauf">Lebenslauf</option>
                <option value="anschreiben">Anschreiben</option>
                <option value="sonstiges">Sonstiges</option>
            </select>
        </div>

        <div>
            <input type="file" wire:model="file" class="text-sm" accept=".pdf,.doc,.docx">
            @error('file') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div wire:loading wire:target="file" class="text-xs text-gray-500">Wird hochgeladen...</div>

        <button type="submit" class="bg-brand-accent text-white px-3 py-1.5 rounded-lg text-sm">
            Hochladen
        </button>
    </form>

    @if ($documents->isNotEmpty())
        <ul class="space-y-2">
            @foreach ($documents as $document)
                <li class="flex items-center justify-between text-sm border rounded-lg px-3 py-2">
                    <div>
                        <span class="font-medium">{{ $document->original_filename }}</span>
                        <span class="text-gray-500 text-xs">({{ ucfirst($document->type) }})</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('documents.download', $document->id) }}" class="text-brand-accent text-xs" target="_blank">
                            Download
                        </a>
                        <button wire:click="delete({{ $document->id }})" wire:confirm="Dokument wirklich löschen?" class="text-red-600 text-xs">
                            Löschen
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>