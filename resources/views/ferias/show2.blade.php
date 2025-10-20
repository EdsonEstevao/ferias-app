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
                            <div>
                                <p class="text-sm text-gray-600">Total de Períodos</p>
                                <p class="font-semibold">{{ $ferias->periodos->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Períodos de Férias -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">🗓️ Períodos de Férias</h3>

                        @php
                            $periodosOriginais = $ferias->periodos->whereNull('periodo_origem_id');
                        @endphp

                        @forelse($periodosOriginais as $periodo)
                            <div class="p-4 mb-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <!-- Cabeçalho do Período -->
                                        <div class="flex items-center gap-2 mb-2">
                                            <p class="font-semibold">
                                                {{ $periodo->ordem }}º Período
                                                {{ $periodo->tipo == 'Abono' ? 'de Abono' : 'de Férias' }}
                                            </p>
                                            <span
                                                class="px-2 py-1 text-xs rounded-full
                                                {{ $periodo->situacao == 'Planejado'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($periodo->situacao == 'Interrompido'
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $periodo->situacao }}
                                            </span>
                                        </div>

                                        <!-- Datas e Dias -->
                                        <p class="text-sm text-gray-600">
                                            {{ date('d/m/Y', strtotime($periodo->inicio)) }} -
                                            {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                            ({{ $periodo->dias }} dias)
                                        </p>

                                        <!-- Link da Portaria -->
                                        @if ($periodo->title && $periodo->url)
                                            <p class="mt-1 text-sm text-gray-600">
                                                <a href="{{ $periodo->url }}" target="_blank"
                                                    class="text-blue-600 hover:underline">
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

                                        <!-- Períodos Filhos (Remarcações/Interrupções) -->
                                        @if ($periodo->filhos->count() > 0)
                                            <div class="pl-4 mt-4 ml-4 border-l-2 border-gray-300">
                                                <p class="mb-2 text-sm font-medium text-gray-700">
                                                    📋 Histórico de Alterações:
                                                </p>
                                                @foreach ($periodo->filhos as $filho)
                                                    <div class="p-3 mb-2 border rounded bg-gray-50">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="text-sm font-medium">
                                                                {{ $filho->situacao }}
                                                            </span>
                                                            <span
                                                                class="px-2 py-1 text-xs rounded-full
                                                                {{ $filho->situacao == 'Planejado'
                                                                    ? 'bg-green-100 text-green-800'
                                                                    : ($filho->situacao == 'Interrompido'
                                                                        ? 'bg-red-100 text-red-800'
                                                                        : 'bg-yellow-100 text-yellow-800') }}">
                                                                {{ $filho->tipo }}
                                                            </span>
                                                        </div>

                                                        <p class="text-sm text-gray-600">
                                                            {{ date('d/m/Y', strtotime($filho->inicio)) }} -
                                                            {{ date('d/m/Y', strtotime($filho->fim)) }}
                                                            ({{ $filho->dias }} dias)
                                                        </p>

                                                        @if ($filho->justificativa)
                                                            <p class="mt-1 text-xs text-gray-500">
                                                                <strong>Motivo:</strong> {{ $filho->justificativa }}
                                                            </p>
                                                        @endif

                                                        <!-- Link da Portaria do Filho -->
                                                        @if ($filho->title && $filho->url)
                                                            <p class="mt-1 text-xs text-gray-500">
                                                                <a href="{{ $filho->url }}" target="_blank"
                                                                    class="text-blue-600 hover:underline">
                                                                    📄 {{ $filho->title }}
                                                                </a>
                                                            </p>
                                                        @endif
                                                        <x-periodo :periodo="$periodo" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Eventos (se houver) -->
                                        @if (isset($periodo->eventos) && $periodo->eventos->count() > 0)
                                            <div class="mt-3 ml-4">
                                                <p class="text-sm font-medium text-gray-700">Eventos:</p>
                                                @foreach ($periodo->eventos as $evento)
                                                    <div class="p-2 mt-1 rounded bg-blue-50">
                                                        <p class="text-xs">
                                                            {{ $evento->tipo }} -
                                                            {{ date('d/m/Y', strtotime($evento->data)) }}
                                                        </p>
                                                        @if ($evento->observacao)
                                                            <p class="text-xs text-gray-500">
                                                                {{ $evento->observacao }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Ações -->
                                    <div class="flex gap-2 ml-4">
                                        @if ($periodo->situacao == 'Planejado')
                                            <button
                                                class="px-3 py-1 text-xs text-green-600 bg-green-100 rounded hover:bg-green-200">
                                                🔁 Remarcar
                                            </button>
                                            <button
                                                class="px-3 py-1 text-xs text-red-600 bg-red-100 rounded hover:bg-red-200">
                                                ✋ Interromper
                                            </button>
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
                            ← Voltar para a Lista
                        </a>

                        @if (Route::has('ferias.edit'))
                            <a href="{{ route('ferias.edit', $ferias->id) }}"
                                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                                ✏️ Editar Férias
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
