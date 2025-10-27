@props(['situacao', 'ativo', 'tipo' => null])

<div class="flex gap-1">
    <span
        class="px-2 py-1 text-xs rounded-full {{ $situacao == 'Planejado' ? 'bg-green-100 text-green-800' : ($situacao == 'Interrompido' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
        {{ $situacao }}
    </span>

    @if ($tipo)
        <span
            class="px-2 py-1 text-xs rounded-full {{ $ativo ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
            {{ $tipo }}
        </span>
    @endif

    @if ($ativo)
        <span class="px-2 py-1 text-xs text-gray-700 bg-gray-200 rounded-full">
            HISTÓRICO
        </span>
    @else
        <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">
            ATIVO
        </span>
    @endif

</div>
