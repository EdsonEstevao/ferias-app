{{-- Recursivo para filhos --}}
<div class="p-3 mb-3 border border-green-200 rounded bg-green-50">
    <div class="flex items-center gap-2 mb-2">
        <span class="text-sm font-medium">{{ $periodo->situacao }}</span>
        @include('ferias.partials.badge-situacao', ['situacao' => $periodo->situacao])
    </div>

    <p class="text-sm text-gray-600">
        <strong>Período:</strong>
        {{ $periodo->inicio->format('d/m/Y') }} -
        {{ $periodo->fim->format('d/m/Y') }}
        ({{ $periodo->dias }} dias)
    </p>

    @if ($periodo->justificativa)
        <p class="mt-1 text-sm text-gray-600">
            <strong>Motivo:</strong> {{ $periodo->justificativa }}
        </p>
    @endif

    <!-- Eventos -->
    @if ($periodo->eventos->count() > 0)
        @include('ferias.partials.lista-eventos', [
            'eventos' => $periodo->eventos,
            'titulo' => 'Eventos',
        ])
    @endif

    <!-- Filhos recursivos -->
    @if ($periodo->filhos->count() > 0)
        <div class="pl-4 mt-3 ml-4 border-l-2 border-orange-300">
            <p class="mb-2 text-xs font-medium text-gray-700">
                ↪️ Sub-alterações:
            </p>
            @foreach ($periodo->filhos as $filho)
                @include('ferias.partials.periodo-filho', ['periodo' => $filho, 'nivel' => $nivel + 1])
            @endforeach
        </div>
    @endif
</div>
