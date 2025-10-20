<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Detalhes das Férias
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Informações do Servidor -->
                    <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">👤 Servidor</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-sm text-gray-600">Nome</p>
                                <p class="font-semibold">{{ $ferias->servidor->nome }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Matrícula</p>
                                <p class="font-semibold">{{ $ferias->servidor->matricula }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Ano de Exercício</p>
                                <p class="font-semibold">{{ $ferias->ano_exercicio }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Períodos de Férias -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">🗓️ Períodos de Férias</h3>

                        @forelse($ferias->periodos as $periodo)
                            <div class="p-4 mb-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold">
                                            {{ date('d/m/Y', strtotime($periodo->inicio)) }} -
                                            {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                            ({{ $periodo->dias }} dias)
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Situação:
                                            <span
                                                class="{{ $periodo->situacao == 'Planejado'
                                                    ? 'text-green-600'
                                                    : ($periodo->situacao == 'Interrompido'
                                                        ? 'text-red-600'
                                                        : 'text-yellow-600') }}">
                                                {{ $periodo->situacao }}
                                            </span>
                                        </p>
                                        @if ($periodo->observacao)
                                            <p class="text-sm text-gray-600">
                                                Observação: {{ $periodo->observacao }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Nenhum período de férias encontrado.</p>
                        @endforelse
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex gap-2 mt-6">
                        <a href="{{ route('ferias.index') }}"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                            ← Voltar
                        </a>
                        <a href="{{ route('ferias.edit', $ferias->id) }}"
                            class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                            ✏️ Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
