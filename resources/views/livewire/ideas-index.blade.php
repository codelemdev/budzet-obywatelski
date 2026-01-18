<div>
    <div class="filters flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-6">
        <div class="w-full md:w-1/3">
            <select wire:model.live="category" name="category" id="category"
                class="w-full border-none rounded-xl px-4 py-2">
                <option value="Wszystkie">Wszystkie</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <select wire:model.live="filter" name="other_filters" id="other_filters"
                class="w-full border-none rounded-xl px-4 py-2">
                <option value="Bez filtra">Bez filtra</option>
                <option value="Najlepsze">Najlepsze</option>
                <option value="Moje pomysły">Moje pomysły</option>
                @if ($status === 'Wszystkie')
                    <option value="Nowe">Nowe</option>
                @endif
            </select>
        </div>
        <div class="w-2/3 relative">
            <input wire:model.live.debounce.500ms="search" type="search" placeholder="Wyszukaj pomysł"
                class="w-full rounded-xl bg-white px-4 py-2 pl-8 border-none placeholder-gray-900">
            <div class="absolute top-0 flex items-center h-full ml-2">
                <svg class="w-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div> <!-- Koniec filtrów -->
    <div class="pomysly-container space-y-6 my-6">
        @forelse ($ideas as $idea)
            <livewire:idea-index :key="$idea->id" :idea="$idea" :votesCount="$idea->votes_count" />
        @empty
            <div class="mx-auto w-70 mt-12 text-gray-400 text-center font-bold">
                Nie znaleziono pomysłów o wskazanych kryteriach.
            </div>
        @endforelse
    </div> <!-- Koniec pomysly-container -->
    {{ $ideas->links('pagination.custom') }}
</div>
</div>