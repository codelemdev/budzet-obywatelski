<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Budżet Obywatelski</title>

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap">

    <!-- Scripts -->
    <livewire:styles />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-gray-100 text-gray-900 text-sm">
    <header class="flex flex-col md:flex-row items-center justify-between px-8 py-4">
        <a href="/"><img src="{{ asset('img/logo-budzet-obywatelski.svg') }}" alt="logo"></a>
        <div class="flex items-center mt-2 md:mt-0">
            @if (Route::has('login'))
                <div class="px-6 py-4">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                                    this.closest('form').submit();">
                                {{ __('Wyloguj się') }}
                            </a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Logowanie</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Rejestracja</a>
                        @endif
                    @endauth
                </div>
            @endif
            <a href="#">
                <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp" alt="avatar"
                    class="w-10 h-10 rounded-full">
            </a>
        </div>
    </header>

    <main class="container mx-auto max-w-custom flex flex-col md:flex-row">
        <div class="w-70 mx-auto md:mx-0 md:mr-5">
            <div class="border-2 md:sticky top-8 border-red-700 bg-white rounded-xl mt-16">
                <div class="text-center px-6 py-2 pt-6">
                    <img src="{{ asset('img/logo-budzet-obywatelski.svg') }}" alt="logo" class="w-48 mx-auto">
                    <p class="text-xs mt-4">
                        @auth
                            Przedstaw wszystkim swój pomysł na wykorzystanie środków dostępnych w ramach budżetu
                            obywatelskiego!
                        @else
                            Zaloguj się, aby dodać swój pomysł na wykorzystanie środków dostępnych w ramach budżetu
                            obywatelskiego!
                        @endauth
                    </p>
                </div>

                @auth
                    <livewire:create-idea />


                @else
                    <div class="my-6 text-center">
                        <a href="{{ route('login') }}"
                            class="inline-block justify-center w-1/2 h-11 text-xs bg-red-700 text-white font-semibold rounded-xl border border-red-700 hover:bg-red-900 transition duration-150 ease-in px-6 py-3">
                            <span class="ml-1">Logowanie</span>
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-block justify-center w-1/2 h-11 text-xs bg-gray-200 font-semibold rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3 mt-4">
                            Rejestracja
                        </a>
                    </div>
                @endauth

            </div>
        </div>
        <div class="w-full px-2 md:px-0 md:w-175">
            <livewire:status-filters />
            <div class="mt-8">
                {{ $slot }}
            </div>
        </div>
    </main>
    <livewire:scripts />
</body>

</html>