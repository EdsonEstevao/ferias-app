@props(['servidor', 'ferias'])

<div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg sm:p-6 sm:mb-6">
    <h3 class="mb-3 text-lg font-bold sm:mb-4">👤 Servidor</h3>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
        <div>
            <p class="text-sm text-gray-600">Nome</p>
            <p class="text-sm font-semibold sm:text-base">{{ $servidor->nome }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Matrícula</p>
            <p class="text-sm font-semibold sm:text-base">{{ $servidor->matricula }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Ano de Exercício</p>
            <p class="text-sm font-semibold sm:text-base">{{ $ferias->ano_exercicio }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Total de Períodos</p>
            <p class="text-sm font-semibold sm:text-base">{{ $ferias->periodos->where('ativo', true)->count() }}</p>
        </div>
    </div>
</div>
