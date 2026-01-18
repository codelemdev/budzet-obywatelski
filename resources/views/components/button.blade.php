<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-200 rounded-xl font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-red-700 hover:text-white transition duration-150 ease-in duration-150']) }}>
    {{ $slot }}
</button>


{{-- bg-gray-200 border border-gray-200 hover:border-gray-400 font-bold text-xxs uppercase rounded-xl transition duration-150 ease-in px-4 py-3 --}}