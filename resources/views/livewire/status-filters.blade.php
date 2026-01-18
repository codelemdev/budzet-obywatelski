<nav class="hidden md:flex items-center justify-between text-gray-400 text-xs">
    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li><a wire:click.prevent="setStatus('Wszystkie')" href="#" class="transition duration-150 ease-in border-b-4 pb-3 hover:border-red-700 @if ($status === 'Wszystkie') border-red-700 text-gray-900 @endif">Wszystkie ({{ $statusCount['wszystkie_statuses'] }})</a></li>
        <li><a wire:click.prevent="setStatus('Rozważane')" href="#" class="transition duration-150 ease-in border-b-4 pb-3 hover:border-red-700 @if ($status === 'Rozważane') border-red-700 text-gray-900 @endif">Rozważane ({{ $statusCount['Rozważane'] }})</a></li>
        <li><a wire:click.prevent="setStatus('W realizacji')" href="#" class="transition duration-150 ease-in border-b-4 pb-3 hover:border-red-700 @if ($status === 'W realizacji') border-red-700 text-gray-900 @endif">W realizacji ({{ $statusCount['W realizacji'] }})</a></li>
    </ul>

    <ul class="flex uppercase font-semibold border-b-4 pb-3 space-x-10">
        <li><a wire:click.prevent="setStatus('Zrealizowane')" href="#" class="transition duration-150 ease-in border-b-4 pb-3 hover:border-red-700 @if ($status === 'Zrealizowane') border-red-700 text-gray-900 @endif">Zrealizowane ({{ $statusCount['Zrealizowane'] }})</a></li>
        <li><a wire:click.prevent="setStatus('Odrzucone')" href="#" class="transition duration-150 ease-in border-b-4 pb-3 hover:border-red-700 @if ($status === 'Odrzucone') border-red-700 text-gray-900 @endif">Odrzucone ({{ $statusCount['Odrzucone'] }})</a></li>
    </ul>
</nav>