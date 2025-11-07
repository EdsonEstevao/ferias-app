<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Detalhes do Período de Férias
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('ferias.index') }}"
                    class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    ← Voltar para Lista
                </a>
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 text-sm text-blue-600 bg-white border border-blue-300 rounded-md hover:bg-blue-50 dark:bg-gray-700 dark:text-blue-400 dark:border-blue-600 dark:hover:bg-gray-600">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="feriasManager()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-full dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Períodos</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $estatisticas['total_periodos'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-full dark:bg-green-900">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Dias</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $estatisticas['total_dias'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-full dark:bg-purple-900">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Servidor</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">1</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Informações do Período -->
                <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-gray-700">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Informações do Período
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600">
                        <dl>
                            <div class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Servidor</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    {{ $periodo->ferias->servidor->nome }}
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Matrícula</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    {{ $periodo->ferias->servidor->matricula }}
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Ano de Exercício</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    <span
                                        class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                        {{ $periodo->ferias->ano_exercicio }}
                                    </span>
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $periodo->tipo == 'Férias'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ $periodo->tipo }}
                                    </span>
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Situação</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $periodo->situacao == 'Planejado'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : ($periodo->situacao == 'Usufruído'
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                                : ($periodo->situacao == 'Interrompido'
                                                    ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200')) }}">
                                        {{ $periodo->situacao }}
                                    </span>
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Status Usufruto</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $periodo->usufruido
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ $periodo->usufruido ? 'Usufruído' : 'Pendente' }}
                                    </span>
                                    @if ($periodo->usufruido && $periodo->data_usufruto)
                                        <span class="ml-2 text-xs text-gray-500">
                                            em {{ $periodo->data_usufruto->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Período</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($periodo->inicio)->format('d/m/Y') }}
                                    a
                                    {{ \Carbon\Carbon::parse($periodo->fim)->format('d/m/Y') }}
                                </dd>
                            </div>
                            <div class="px-4 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Duração</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                    {{ $periodo->dias }} dias
                                </dd>
                            </div>
                            @if ($periodo->origem)
                                <div
                                    class="px-4 py-3 bg-yellow-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-yellow-900/20">
                                    <dt class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Período
                                        Original</dt>
                                    <dd class="mt-1 text-sm text-yellow-700 sm:mt-0 sm:col-span-2 dark:text-yellow-300">
                                        {{ \Carbon\Carbon::parse($periodo->origem->inicio)->format('d/m/Y') }}
                                        a
                                        {{ \Carbon\Carbon::parse($periodo->origem->fim)->format('d/m/Y') }}
                                        ({{ $periodo->origem->dias }} dias)
                                        <br>
                                        <span class="text-xs">
                                            Situação original: {{ $periodo->origem->situacao }}
                                        </span>
                                    </dd>
                                </div>
                            @endif
                            @if ($periodo->justificativa)
                                <div
                                    class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Justificativa</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                        {{ $periodo->justificativa }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Ações e Informações Adicionais -->
                <div class="space-y-6">
                    <!-- Ações Rápidas -->
                    <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-gray-700">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Ações
                            </h3>
                        </div>
                        <div class="px-4 py-5 border-t border-gray-200 sm:p-6 dark:border-gray-600">
                            <div class="grid grid-cols-1 gap-3">
                                @if (!$periodo->usufruido && $periodo->ativo && in_array($periodo->situacao, ['Planejado', 'Remarcado']))
                                    <form action="{{ url('/api/periodos-ferias/' . $periodo->id . '/usufruir') }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Marcar como Usufruído
                                        </button>
                                    </form>
                                @endif

                                @if ($periodo->usufruido)
                                    <form action="{{ url('/api/periodos-ferias/' . $periodo->id . '/desusufruir') }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 border border-yellow-300 rounded-md hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:bg-yellow-900 dark:text-yellow-200 dark:border-yellow-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Desfazer Usufruto
                                        </button>
                                    </form>
                                @endif

                                @if (!$periodo->usufruido && in_array($periodo->situacao, ['Planejado', 'Interrompido']))
                                    <button
                                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 border border-blue-300 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-200 dark:border-blue-700"
                                        @click="abrirModalRemarcacao({{ $periodo->id }}, {{ json_encode($periodo) }})">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        Remarcar Período
                                    </button>
                                @endif

                                @if ($periodo->usufruido)
                                    <button
                                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-purple-600 bg-purple-100 border border-purple-300 rounded-md hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-purple-900 dark:text-purple-200 dark:border-purple-700"
                                        onclick="alert('Funcionalidade de relatório em desenvolvimento')">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Gerar Relatório
                                    </button>
                                @endif

                                @if ($periodo->ativo)
                                    <form action="{{ url('/api/periodos-ferias/' . $periodo->id) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="ativo" value="0">
                                        <button type="submit"
                                            onclick="return confirm('Tem certeza que deseja inativar este período?')"
                                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-red-600 bg-red-100 border border-red-300 rounded-md hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900 dark:text-red-200 dark:border-red-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Inativar Período
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ url('/api/periodos-ferias/' . $periodo->id) }}" method="POST"
                                        class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="ativo" value="1">
                                        <button type="submit"
                                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-green-600 bg-green-100 border border-green-300 rounded-md hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-900 dark:text-green-200 dark:border-green-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Reativar Período
                                        </button>
                                    </form>
                                @endif
                                <!--Botão pra voltar -->
                                <a href="{{ url()->previous() }}"
                                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-green-600 bg-green-100 border border-green-300 rounded-md hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-900 dark:text-green-200 dark:border-green-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Sistema -->
                    <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-gray-700">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                Informações do Sistema
                            </h3>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600">
                            <dl>
                                <div
                                    class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Criado em</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                        {{ $periodo->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div
                                    class="px-4 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Atualizado em</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                        {{ $periodo->updated_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div
                                    class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-600">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $periodo->ativo
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $periodo->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </dd>
                                </div>
                                @if ($periodo->periodo_origem_id)
                                    <div
                                        class="px-4 py-3 bg-white sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Tipo de
                                            Período</dt>
                                        <dd
                                            class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-100">
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                                Remarcação
                                            </span>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Remarcar com Múltiplos Períodos - Corrigido -->
            <div x-show="modalAberto" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-white rounded-lg shadow-xl"
                    @click.away="fecharModalRemarcacao">
                    <!-- Cabeçalho do Modal -->
                    <div class="flex items-center justify-between p-6 border-b">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Remarcar Férias - <span x-text="filhos?.dias || 0" class="text-blue-600"></span> Dias
                        </h3>
                        <button @click="fecharModalRemarcacao" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Conteúdo do Modal -->
                    <div class="p-6">
                        <!-- Resumo de Dias -->
                        <div class="p-4 mb-6 rounded-lg bg-blue-50">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-700" x-text="filhos?.dias || 0"></div>
                                    <div class="text-sm text-blue-600">Dias Totais</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold"
                                        :class="totalDiasDistribuidos > (filhos?.dias || 0) ? 'text-red-600' : 'text-green-600'"
                                        x-text="totalDiasDistribuidos"></div>
                                    <div class="text-sm text-gray-600">Dias Distribuídos</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold"
                                        :class="(filhos?.dias || 0) - totalDiasDistribuidos < 0 ? 'text-red-600' :
                                            'text-blue-600'"
                                        x-text="(filhos?.dias || 0) - totalDiasDistribuidos"></div>
                                    <div class="text-sm text-gray-600">Dias Restantes</div>
                                </div>
                            </div>

                            <div x-show="totalDiasDistribuidos !== (filhos?.dias || 0)"
                                class="p-3 mt-3 border border-yellow-200 rounded bg-yellow-50">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-yellow-700">
                                        Restam <span x-text="(filhos?.dias || 0) - totalDiasDistribuidos"
                                            class="font-semibold"></span> dias para distribuir
                                    </span>
                                </div>
                            </div>

                            <div x-show="totalDiasDistribuidos === (filhos?.dias || 0)"
                                class="p-3 mt-3 border border-green-200 rounded bg-green-50">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-green-700">Todos os dias foram distribuídos
                                        corretamente</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Períodos -->
                        <div class="space-y-4">
                            <template x-for="(periodo, index) in periodosRemarcacao" :key="index">
                                <div class="p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-semibold text-gray-900">Período <span
                                                x-text="index + 1"></span></h4>
                                        <button type="button" @click="removerPeriodo(index)"
                                            x-show="periodosRemarcacao.length > 1"
                                            class="text-red-600 transition-colors hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Datas e Dias -->
                                    <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                Data Início *
                                            </label>
                                            <input type="date" x-model="periodo.inicio"
                                                @change="validarDatasPeriodo(index); calcularDiasPeriodo(index)"
                                                :min="obterMinDate(index)"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                Data Fim *
                                            </label>
                                            <input type="date" x-model="periodo.fim"
                                                @change="validarDatasPeriodo(index); calcularDiasPeriodo(index)"
                                                :min="periodo.inicio || ''"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                Dias *
                                            </label>
                                            <input type="number" x-model="periodo.dias"
                                                @input="atualizarFimPorDias(index)" min="1"
                                                :max="diasDisponiveis(index)"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                            <p class="mt-1 text-xs text-gray-500">
                                                Máximo: <span x-text="diasDisponiveis(index)"></span> dias
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Título e Link DIOF -->
                                    <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                Título do Período
                                            </label>
                                            <input type="text" x-model="periodo.titulo"
                                                placeholder="Ex: 1º período de férias 2024"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                                Link DIOF (Opcional)
                                            </label>
                                            <input type="url" x-model="periodo.linkDiof"
                                                placeholder="https://..."
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Observações -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-gray-700">
                                            Observações
                                        </label>
                                        <textarea x-model="periodo.observacoes" rows="2" placeholder="Observações sobre este período..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>

                                    <!-- Resumo do Período -->
                                    <div x-show="periodo.dias > 0" class="p-3 mt-3 border rounded bg-gray-50">
                                        <div class="text-sm text-gray-700">
                                            <span class="font-semibold" x-text="periodo.dias"></span> dias -
                                            <span
                                                x-text="periodo.inicio ? formatarData(periodo.inicio) : '...'"></span>
                                            a
                                            <span x-text="periodo.fim ? formatarData(periodo.fim) : '...'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Botão Adicionar Período -->
                        <div class="mt-6">
                            <button type="button" @click="adicionarPeriodo()"
                                :disabled="totalDiasDistribuidos >= (filhos?.dias || 0)"
                                :class="totalDiasDistribuidos >= (filhos?.dias || 0) ?
                                    'bg-gray-400 cursor-not-allowed' :
                                    'bg-blue-600 hover:bg-blue-700'"
                                class="flex items-center px-4 py-2 text-white transition-colors rounded-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Adicionar Período
                            </button>
                        </div>

                        <!-- Justificativa Geral -->
                        <div class="mt-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Justificativa da Remarcação *
                            </label>
                            <textarea x-model="justificativaGeral" rows="4" placeholder="Descreva o motivo da remarcação..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Campo obrigatório para registrar a remarcação.
                            </p>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex flex-col gap-3 mt-6 sm:flex-row sm:justify-end sm:gap-4">
                            <button @click="fecharModalRemarcacao()"
                                class="px-6 py-2 text-gray-700 transition-colors bg-gray-200 rounded-md hover:bg-gray-300">
                                Cancelar
                            </button>

                            <button @click="confirmarRemarcacaoMultiplosPeriodos()"
                                :disabled="!podeConfirmarRemarcacao()"
                                :class="podeConfirmarRemarcacao() ?
                                    'bg-green-600 hover:bg-green-700' :
                                    'bg-gray-400 cursor-not-allowed'"
                                class="flex items-center px-6 py-2 text-white transition-colors rounded-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Confirmar Remarcação
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- JavaScript para feedback das ações -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('feriasManager', () => ({
                    // Estados
                    modalAberto: false,
                    periodoSelecionado: null,
                    filhos: null,
                    periodosRemarcacao: [],
                    justificativaGeral: '',
                    totalDiasDistribuidos: 0,

                    // Método para abrir o modal
                    abrirModalRemarcacao(periodoId, periodoData) {
                        this.modalAberto = true;
                        this.periodoSelecionado = periodoId;
                        this.filhos = periodoData;

                        // Inicializar com um período vazio
                        this.periodosRemarcacao = [{
                            inicio: '',
                            fim: '',
                            dias: 0,
                            titulo: '',
                            linkDiof: '',
                            observacoes: ''
                        }];

                        this.justificativaGeral = '';
                        this.totalDiasDistribuidos = 0;
                    },

                    // Adicionar novo período
                    adicionarPeriodo() {
                        if (this.totalDiasDistribuidos >= (this.filhos?.dias || 0)) {
                            alert('Todos os dias já foram distribuídos!');
                            return;
                        }

                        this.periodosRemarcacao.push({
                            inicio: '',
                            fim: '',
                            dias: 0,
                            titulo: '',
                            linkDiof: '',
                            observacoes: ''
                        });
                    },

                    // Remover período
                    removerPeriodo(index) {
                        if (this.periodosRemarcacao.length > 1) {
                            const diasRemovidos = this.periodosRemarcacao[index].dias || 0;
                            this.periodosRemarcacao.splice(index, 1);
                            this.totalDiasDistribuidos -= diasRemovidos;
                        }
                    },

                    // Calcular dias de um período
                    calcularDiasPeriodo(index) {
                        const periodo = this.periodosRemarcacao[index];
                        if (periodo.inicio && periodo.fim) {
                            const inicio = new Date(periodo.inicio);
                            const fim = new Date(periodo.fim);

                            if (fim >= inicio) {
                                const diff = Math.floor((fim - inicio) / (1000 * 60 * 60 * 24)) + 1;
                                const diasAnteriores = periodo.dias || 0;

                                periodo.dias = diff;
                                this.totalDiasDistribuidos += (diff - diasAnteriores);
                            } else {
                                periodo.dias = 0;
                                alert('A data final não pode ser anterior à data inicial.');
                            }
                        }
                    },

                    // Atualizar data fim baseado nos dias
                    atualizarFimPorDias(index) {
                        const periodo = this.periodosRemarcacao[index];
                        if (periodo.inicio && periodo.dias > 0) {
                            const inicio = new Date(periodo.inicio);
                            const fim = new Date(inicio);
                            fim.setDate(fim.getDate() + periodo.dias - 1);
                            periodo.fim = fim.toISOString().split('T')[0];
                            this.calcularTotalDiasDistribuidos();
                        }
                    },

                    // Calcular total de dias distribuídos
                    calcularTotalDiasDistribuidos() {
                        this.totalDiasDistribuidos = this.periodosRemarcacao.reduce((total, periodo) => {
                            return total + (parseInt(periodo.dias) || 0);
                        }, 0);
                    },

                    // Validar datas do período
                    validarDatasPeriodo(index) {
                        const periodo = this.periodosRemarcacao[index];
                        if (periodo.inicio && periodo.fim) {
                            const inicio = new Date(periodo.inicio);
                            const fim = new Date(periodo.fim);

                            if (fim < inicio) {
                                alert('A data final não pode ser anterior à data inicial.');
                                periodo.fim = '';
                                periodo.dias = 0;
                            }
                        }
                    },

                    // Obter data mínima para um período
                    obterMinDate(index) {
                        if (index === 0) return '';

                        const periodoAnterior = this.periodosRemarcacao[index - 1];
                        if (periodoAnterior && periodoAnterior.fim) {
                            const minDate = new Date(periodoAnterior.fim);
                            minDate.setDate(minDate.getDate() + 1);
                            return minDate.toISOString().split('T')[0];
                        }
                        return '';
                    },

                    // Dias disponíveis para um período
                    diasDisponiveis(index) {
                        const diasUsados = this.periodosRemarcacao.reduce((total, periodo, i) => {
                            return i !== index ? total + (parseInt(periodo.dias) || 0) : total;
                        }, 0);

                        return Math.max(0, (this.filhos?.dias || 0) - diasUsados);
                    },

                    // Formatar data para exibição
                    formatarData(dataString) {
                        if (!dataString) return '';
                        const data = new Date(dataString);
                        return data.toLocaleDateString('pt-BR');
                    },

                    // Fechar modal
                    fecharModalRemarcacao() {
                        this.modalAberto = false;
                        this.periodosRemarcacao = [];
                        this.justificativaGeral = '';
                        this.totalDiasDistribuidos = 0;
                    },

                    // Verificar se pode confirmar
                    podeConfirmarRemarcacao() {
                        return this.totalDiasDistribuidos === (this.filhos?.dias || 0) &&
                            this.periodosRemarcacao.every(p => p.inicio && p.fim && p.dias > 0) &&
                            this.justificativaGeral.trim() !== '';
                    },

                    // Confirmar remarcação
                    async confirmarRemarcacaoMultiplosPeriodos() {
                        if (!this.podeConfirmarRemarcacao()) {
                            alert('Preencha todos os períodos corretamente e a justificativa!');
                            return;
                        }

                        try {
                            // Aqui você precisa implementar a chamada para sua API
                            console.log('Dados para remarcação:', {
                                periodo_id: this.periodoSelecionado,
                                periodos: this.periodosRemarcacao,
                                justificativa: this.justificativaGeral
                            });

                            // Exemplo de implementação:
                            const response = await fetch('/api/remarcar-ferias', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    periodo_id: this.periodoSelecionado,
                                    periodos: this.periodosRemarcacao,
                                    justificativa: this.justificativaGeral
                                })
                            });

                            if (response.ok) {
                                alert('Períodos remarcados com sucesso!');
                                this.fecharModalRemarcacao();
                                location.reload();
                            } else {
                                throw new Error('Erro ao remarcar períodos');
                            }
                        } catch (error) {
                            console.error('Erro:', error);
                            alert('Erro ao processar a remarcação: ' + error.message);
                        }
                    }
                }));
            });
        </script>
</x-app-layout>
