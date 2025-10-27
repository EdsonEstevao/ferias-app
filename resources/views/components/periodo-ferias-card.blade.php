@props(['periodo'])

<div class="p-4 mb-6 border border-gray-200 rounded-lg {{ !$periodo->ativo ? 'bg-gray-50 opacity-75' : 'bg-white' }}">
    <!-- Cabeçalho do Período Original -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            {{-- <div class="flex items-center gap-2 mb-2"> --}}
            <div class="flex flex-col gap-2 sm:flex-row">
                <p class="text-lg font-semibold">
                    {{ $periodo->ordem }}º Período
                    {{ $periodo->tipo == 'Abono' ? 'de Abono' : 'de Férias' }}
                </p>
                <x-status-badge :situacao="$periodo->situacao" :ativo="$periodo->ativo" />
            </div>

            <!-- Datas e Dias do Período Original -->
            <p class="text-sm text-gray-600">
                <strong>Período Original:</strong>
                {{ date('d/m/Y', strtotime($periodo->inicio)) }} -
                {{ date('d/m/Y', strtotime($periodo->fim)) }}
                ({{ $periodo->dias }} dias)
            </p>

            <!-- Link da Portaria -->
            @if ($periodo->title && $periodo->url)
                <p class="mt-1 text-sm text-gray-600">
                    <a href="{{ $periodo->url }}" target="_blank" class="text-blue-600 hover:underline">
                        📄 {{ $periodo->title }}
                    </a>
                </p>
            @endif

            <!-- Justificativa -->
            @if ($periodo->justificativa)
                <p class="mt-1 text-sm text-gray-600">
                    <strong>Justificativa:</strong> {{ $periodo->justificativa }}
                </p>
            @endif
        </div>
    </div>

    <!-- Períodos Filhos -->
    @php
        $todosFilhosRecursivos = $periodo->todosFilhosRecursivos;
    @endphp

    @if ($todosFilhosRecursivos->count() > 0)
        <div class="pl-4 mt-4 ml-4 border-l-2 border-blue-300">
            <p class="mb-3 text-sm font-medium text-gray-700">
                📋 Histórico de Alterações:
            </p>
            @foreach ($todosFilhosRecursivos->sortBy('created_at') as $filho)
                <x-periodo-filho-item :filho="$filho" />
            @endforeach
        </div>
    @else
        <p class="text-sm italic text-gray-500">
            Nenhuma alteração neste período.
        </p>
    @endif

    <!-- Eventos do período original -->
    @if ($periodo->eventos->count() > 0)
        <div class="mt-3">
            <p class="text-sm font-medium text-gray-700">Eventos do Período Original:</p>
            <x-eventos-list :eventos="$periodo->eventos" />
        </div>
    @endif
</div>
