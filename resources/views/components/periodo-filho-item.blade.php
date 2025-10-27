@props(['filho'])

<div
    class="p-3 mb-3 rounded border {{ $filho->ativo ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200 opacity-75' }}">
    {{-- <div class="flex items-center gap-2 mb-2"> --}}
    <div class="flex flex-col gap-2 sm:flex-row">
        <span class="text-sm font-medium">
            {{ $filho->situacao }}
        </span>
        <x-status-badge :situacao="$filho->situacao" :ativo="$filho->ativo" :tipo="$filho->tipo" />
    </div>

    <p class="text-sm text-gray-600">
        <strong>Período:</strong>
        {{ date('d/m/Y', strtotime($filho->inicio)) }} -
        {{ date('d/m/Y', strtotime($filho->fim)) }}
        ({{ $filho->dias }} dias)
    </p>

    @if ($filho->justificativa)
        <p class="mt-1 text-sm text-gray-600">
            <strong>Motivo:</strong> {{ $filho->justificativa }}
        </p>
    @endif

    <!-- Link da Portaria do Filho -->
    @if ($filho->title && $filho->url)
        <p class="mt-1 text-sm text-gray-600">
            <a href="{{ $filho->url }}" target="_blank" class="text-blue-600 hover:underline">
                📄 {{ $filho->title }}
            </a>
        </p>
    @endif

    <!-- Eventos do período filho -->
    @if ($filho->eventos->count() > 0)
        <div class="mt-2">
            <p class="text-xs font-medium text-gray-700">Eventos:</p>
            <x-eventos-list :eventos="$filho->eventos" />
        </div>
    @endif
</div>
