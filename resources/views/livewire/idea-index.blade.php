<div>
    <div
        class="pomysl-container hover:shadow-card transition duration-150 ease-in bg-white rounded-xl flex cursor-pointer">
        <div class="hidden md:block border-r border-gray-100 px-5 py-8">
            <div class="text-center">
                <div class="font-semibold text-2xl @if ($hasVoted) text-red-700 @endif">{{ $votesCount }}</div>
                <div class="text-gray-500">Głosów</div>
            </div>

            <div class="mt-8">
                @if ($hasVoted)
                    <button type="button" wire:click.prevent="vote"
                        class="w-20 bg-red-700 border border-red-700 hover:border-red-800 font-bold text-xxs text-white uppercase rounded-xl transition duration-150 ease-in px-4 py-3">
                        Tak!
                    </button>
                @elseif(auth()->guest())
                    <a href="{{ route('register') }}"
                        class="w-20 bg-gray-200 border border-gray-200 hover:border-gray-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3 block text-center">
                        Głosuj
                    </a>
                @else
                    <button type="button" wire:click.prevent="vote"
                        class="w-20 bg-gray-200 border border-gray-200 hover:border-gray-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3">
                        Głosuj
                    </button>
                @endif
            </div>
        </div>
        <div class="flex flex-col md:flex-row flex-1 min-w-0 px-2 py-6">
            <div class="flex-none mx-2 md:mx-0">
                <a href="#">
                    <img src="{{ $idea->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl">
                </a>
            </div>
            <div class="w-full flex flex-col justify-between mx-2 md:mx-4">
                <h4 class="text-xl mt-2 md:mt-0 font-semibold break-all">
                    <a href="{{ route('idea.show', $idea) }}" class="hover:underline">{{ $idea->title }}</a>
                </h4>
                <div class="text-gray-600 mt-3 line-clamp-3 break-all">
                    {{ $idea->description }}
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between mt-6">
                    <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                        <div>{{ $idea->created_at->format('d.m.Y') }}</div>
                        <div>&bull;</div>
                        <div>{{ $idea->category->name }}</div>
                        <div>&bull;</div>
                        <div class="text-gray-900">{{ $commentsCount }} Komentarzy</div>
                    </div>
                    <div x-data="{ isOpen: false}" class="flex items-center space-x-2 mt-4 md:mt-0">
                        <div
                            class="{{ $idea->getStatusClasses() }} text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">
                            {{ $idea->status->name }}
                        </div>
                        <button @click="isOpen = !isOpen"
                            class="relative bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3">
                            <svg fill="currentColor" width="24" height="6">
                                <path
                                    d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z"
                                    style="color: rgba(163, 163, 163, .5)">
                            </svg>
                            <ul x-cloak x-show.transition.origin.top.left="isOpen" @click.away="isOpen = false"
                                @keydown.escape.window="isOpen = false"
                                class="absolute w-36 text-left font-semibold bg-white shadow-dialog rounded-xl py-3 md:ml-8 top-8 md:top-6 right-0 md:left-0">
                                <li><a href="#"
                                        class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Oznacz
                                        spam</a></li>
                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <li><a href="#" wire:click.prevent="deleteIdea"
                                            class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Usuń</a>
                                    </li>
                                @else
                                    <li><a href="#"
                                            class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Zgłoś</a>
                                    </li>
                                @endif
                            </ul>
                        </button>
                    </div>
                    <div class="flex items-center md:hidden mt-4 md:mt-0">
                        <div class="bg-gray-100 text-center rounded-xl h-10 px-4 py-2 pr-8">
                            <div class="text-sm font-bold leading-none @if ($hasVoted) text-red-700 @endif">
                                {{ $votesCount }}
                            </div>
                            <div class="text-xxs font-semibold leading-none text-gray-400">Głosów</div>
                        </div>
                        @if ($hasVoted)
                            <button type="button" wire:click.prevent="vote"
                                class="w-20 bg-red-700 border border-red-700 text-white font-bold text-xxs uppercase rounded-xl hover:border-red-800 transition duration-150 ease-in px-4 py-3 -mx-5">
                                Tak!
                            </button>
                        @elseif(auth()->guest())
                            <a href="{{ route('register') }}"
                                class="w-20 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase rounded-xl hover:border-gray-400 transition duration-150 ease-in px-4 py-3 -mx-5 block text-center">
                                Głosuj
                            </a>
                        @else
                            <button type="button" wire:click.prevent="vote"
                                class="w-20 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase rounded-xl hover:border-gray-400 transition duration-150 ease-in px-4 py-3 -mx-5">
                                Głosuj
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Koniec pomysl-container -->
</div>