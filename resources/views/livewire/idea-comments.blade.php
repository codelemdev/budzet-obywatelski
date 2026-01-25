<div class="komentarze-container relative space-y-6 md:ml-22 pt-4 my-8 mt-1">



    @foreach ($comments as $comment)
        @if ($comment->is_status_update)
            <div
                class="is-admin komentarz-container relative bg-white rounded-xl border-solid border-2 border-red-700 flex mt-4">
                <div
                    class="absolute w-7 h-7 rounded-full border-4 border-white shadow-card -left-[54px] top-[43px] {{ $comment->getStatusClasses() }} border-solid z-10">
                </div>

                <div class="flex flex-col md:flex-row flex-1 min-w-0 px-4 py-6">
                    <div class="flex-none">
                        <a href="#">
                            <img src="{{ $comment->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl">
                        </a>
                        <div class="text-left ml-2 md:ml-0 md:text-center uppercase text-red-700 text-xxs font-bold mt-1">Admin
                        </div>
                    </div>
                    <div class="w-full md:mx-4">
                        <h4 class="text-xl font-semibold">
                            <a href="#" class="hover:underline">Status zmieniony na "{{ $comment->status->name }}"</a>
                        </h4>
                        <div class="text-gray-600 mt-3 break-all" style="overflow-wrap: anywhere;">
                            {{ $comment->body }}
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                                <div class="font-bold text-red-700">{{ $comment->user->name }}</div>
                                <div>&bull;</div>
                                <div>{{ $comment->created_at->diffForHumans() }}</div>
                            </div>

                            <div x-data="{ isOpen: false}" class="flex items-center space-x-2">
                                <button @click="isOpen = !isOpen"
                                    class="relative bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3">
                                    <svg fill="currentColor" width="24" height="6">
                                        <path
                                            d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z"
                                            style="color: rgba(163, 163, 163, .5)">
                                    </svg>
                                    <ul x-cloak x-show.transition.origin.top.left="isOpen" @click.away="isOpen = false"
                                        @keydown.escape.window="isOpen = false"
                                        class="absolute w-36 text-left font-semibold bg-white shadow-dialog rounded-xl py-3 z-10 right-0 top-8">
                                        <li><a href="#"
                                                class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Oznacz
                                                spam</a></li>
                                        @if (auth()->check() && auth()->user()->isAdmin())
                                            <li><a href="#" wire:click.prevent="deleteComment({{ $comment->id }})"
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
                        </div>
                    </div>
                </div>
            </div> <!-- Koniec komentarz-container -->
        @else
            <div
                class="komentarz-container relative rounded-xl flex mt-4 @if($comment->is_spam) bg-yellow-200 @elseif($comment->is_violation) bg-red-200 @else bg-white @endif">
                <div class="flex flex-col md:flex-row flex-1 min-w-0 px-4 py-6">
                    <div class="flex-none">
                        <a href="#">
                            <img src="{{ $comment->user->getAvatar() }}" alt="avatar" class="w-14 h-14 rounded-xl">
                        </a>
                    </div>
                    <div class="w-full md:mx-4">
                        {{-- <h4 class="text-xl font-semibold">
                            <a href="#" class="hover:underline">...</a>
                        </h4> --}}
                        <div class="text-gray-600 mt-3 break-all" style="overflow-wrap: anywhere;">
                            {{ $comment->body }}
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center text-xs text-gray-400 font-semibold space-x-2">
                                <div class="font-bold text-gray-900">{{ $comment->user->name }}</div>
                                <div>&bull;</div>
                                <div>{{ $comment->created_at->diffForHumans() }}</div>
                            </div>

                            <div x-data="{ isOpen: false}" class="flex items-center space-x-2">
                                <button @click="isOpen = !isOpen"
                                    class="relative bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3">
                                    <svg fill="currentColor" width="24" height="6">
                                        <path
                                            d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97 0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z"
                                            style="color: rgba(163, 163, 163, .5)">
                                    </svg>
                                    <ul x-cloak x-show.transition.origin.top.left="isOpen" @click.away="isOpen = false"
                                        @keydown.escape.window="isOpen = false"
                                        class="absolute w-36 text-left font-semibold bg-white shadow-dialog rounded-xl py-3 z-10 right-0 top-8">
                                        @if (auth()->check() && auth()->user()->role === \App\Enums\Role::Moderator)
                                            <li><a href="#" wire:click.prevent="markAsSpam({{ $comment->id }})"
                                                    class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Oznacz
                                                    spam</a></li>
                                            <li><a href="#" wire:click.prevent="markAsViolation({{ $comment->id }})"
                                                    class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Zgłoś</a>
                                            </li>
                                        @endif

                                        @if (auth()->check() && auth()->user()->isAdmin())
                                            <li><a href="#" wire:click.prevent="deleteComment({{ $comment->id }})"
                                                    class="hover:bg-gray-100 block transition duration-150 ease-in px-5 py-3">Usuń</a>
                                            </li>
                                        @endif
                                    </ul>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Koniec komentarz-container -->
        @endif
    @endforeach
</div>