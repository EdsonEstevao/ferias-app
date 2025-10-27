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
                                {{-- <p class="font-semibold">{{ $ferias->periodos->count() }}</p> --}}
                                <p class="font-semibold">{{ $ferias->periodos->where('ativo', true)->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Períodos de Férias -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">🗓️ Histórico de Períodos de Férias</h3>

                        @php
                            // Separa períodos originais (sem pai) e organiza por ordem
                            $periodosOriginais = $ferias->periodos->whereNull('periodo_origem_id')->sortBy('ordem');
                        @endphp

                        @forelse($periodosOriginais as $periodo)
                            <div
                                class="p-4 mb-6 border border-gray-200 rounded-lg
                                {{ !$periodo->ativo ? 'bg-gray-50 opacity-75' : 'bg-white' }}">

                                <!-- Cabeçalho do Período Original -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <p class="text-lg font-semibold">
                                                {{ $periodo->ordem }}º Período
                                                {{ $periodo->tipo == 'Abono' ? 'de Abono' : 'de Férias' }}
                                            </p>
                                            <div class="flex gap-1">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full
                                                    {{ $periodo->situacao == 'Planejado'
                                                        ? 'bg-green-100 text-green-800'
                                                        : ($periodo->situacao == 'Interrompido'
                                                            ? 'bg-red-100 text-red-800'
                                                            : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ $periodo->situacao }}
                                                </span>
                                                @if (!$periodo->ativo)
                                                    <span
                                                        class="px-2 py-1 text-xs text-gray-700 bg-gray-200 rounded-full">
                                                        HISTÓRICO
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">
                                                        ATIVO
                                                    </span>
                                                @endif
                                            </div>
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
                                    </div>
                                </div>

                                <!-- Períodos Filhos (Remarcações/Interrupções) -->
                                @if ($periodo->filhos->count() > 0)
                                    <div class="pl-4 mt-4 ml-4 border-l-2 border-blue-300">
                                        <p class="mb-3 text-sm font-medium text-gray-700">
                                            📋 Histórico de Alterações:
                                        </p>
                                        @foreach ($periodo->filhos->sortBy('created_at') as $filho)
                                            <div
                                                class="p-3 mb-3 rounded border
                                                {{ $filho->ativo ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200 opacity-75' }}">

                                                <div class="flex items-center gap-2 mb-2">
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
                                                    @if ($filho->ativo)
                                                        <span
                                                            class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full">
                                                            ATUAL
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 text-xs text-gray-700 bg-gray-200 rounded-full">
                                                            HISTÓRICO
                                                        </span>
                                                    @endif
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
                                                        <a href="{{ $filho->url }}" target="_blank"
                                                            class="text-blue-600 hover:underline">
                                                            📄 {{ $filho->title }}
                                                        </a>
                                                    </p>
                                                @endif

                                                <!-- Eventos do período filho -->
                                                @if ($filho->eventos->count() > 0)
                                                    <div class="mt-2">
                                                        <p class="text-xs font-medium text-gray-700">Eventos:</p>
                                                        @foreach ($filho->eventos as $evento)
                                                            <div class="p-2 mt-1 text-xs rounded bg-blue-50">
                                                                {{ $evento->acao }} -
                                                                {{ date('d/m/Y', strtotime($evento->data_acao)) }}
                                                                @if ($evento->descricao)
                                                                    : {{ $evento->descricao }}
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
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
                                        @foreach ($periodo->eventos as $evento)
                                            <div class="p-2 mt-1 text-xs rounded bg-blue-50">
                                                {{ $evento->acao }} -
                                                {{ date('d/m/Y', strtotime($evento->data_acao)) }}
                                                @if ($evento->descricao)
                                                    : {{ $evento->descricao }}
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500">Nenhum período de férias encontrado.</p>
                        @endforelse
                    </div>

                    <!-- Resumo -->
                    <div class="p-4 rounded-lg bg-blue-50">
                        <h4 class="font-semibold text-blue-800">📊 Resumo:</h4>
                        <div class="grid grid-cols-1 gap-2 mt-2 text-sm md:grid-cols-3">
                            <div>
                                <strong>Períodos Originais:</strong>
                                {{ $periodosOriginais->count() }}
                            </div>
                            <div>
                                <strong>Períodos Ativos:</strong>
                                {{ $ferias->periodos->where('ativo', true)->count() }}
                            </div>
                            <div>
                                <strong>Total de Alterações:</strong>
                                {{ $ferias->periodos->whereNotNull('periodo_origem_id')->count() }}
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="flex gap-2 mt-6">
                        <a href="{{ route('ferias.index') }}"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                            ← Voltar para a Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
