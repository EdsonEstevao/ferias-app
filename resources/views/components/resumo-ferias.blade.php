@props(['ferias', 'periodosOriginais'])

<div class="p-4 rounded-lg bg-blue-50">
    <h4 class="text-sm font-semibold text-blue-800 sm:text-base">📊 Resumo:</h4>
    <div class="grid grid-cols-1 gap-2 mt-2 text-xs sm:grid-cols-2 lg:grid-cols-4 sm:text-sm">
        <div class="break-words">
            <strong>Períodos Originais:</strong>
            {{ $periodosOriginais->count() }}
        </div>
        <div class="break-words">
            <strong>Períodos Ativos:</strong>
            {{ $ferias->periodos->where('ativo', true)->count() }}
        </div>
        <div class="break-words">
            <strong>Total de Alterações:</strong>
            {{ $ferias->periodos->whereNotNull('periodo_origem_id')->count() }}
        </div>
    </div>
</div>
