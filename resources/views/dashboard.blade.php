<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Cards de Estatísticas -->
                    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
                        <div class="p-4 bg-white rounded shadow dark:bg-gray-700">
                            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Servidores com férias
                                lançadas</h3>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalComFerias }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Ano {{ date('Y') }}</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow dark:bg-gray-700">
                            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Férias interrompidas</h3>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $totalInterrompidas }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Períodos ativos</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow dark:bg-gray-700">
                            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Férias Remarcadas</h3>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalRemarcacoes }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Ativos</p>
                        </div>
                    </div>

                    <!-- Calendário de Férias com Alpine.js -->
                    <div x-data="dashboardCalendar()">
                        <div class="p-6 mb-6 bg-white rounded shadow dark:bg-gray-700">
                            <div class="flex flex-col justify-between mb-4 md:flex-row md:items-center">
                                <h3 class="mb-2 text-lg font-bold md:mb-0">📅 Calendário de Férias</h3>

                                <!-- Navegação do Calendário -->
                                <div class="flex items-center space-x-4">
                                    <button @click="previousMonth()" :disabled="loading"
                                        class="p-2 text-gray-500 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div class="flex items-center space-x-2">
                                        <h4 x-text="currentMonthName + ' ' + currentYear"
                                            class="text-lg font-medium text-gray-800 dark:text-gray-200"></h4>
                                        <div x-show="loading"
                                            class="w-4 h-4 border-2 border-blue-500 rounded-full border-t-transparent animate-spin">
                                        </div>
                                    </div>

                                    <button @click="nextMonth()" :disabled="loading"
                                        class="p-2 text-gray-500 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Grid do Calendário -->
                            <!-- suavisação de carregamento -->

                            <div class="border border-gray-200 rounded-lg dark:border-gray-600" x-show="!loading"
                                x-cloak x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <!-- Dias da semana -->
                                <div class="grid grid-cols-7 gap-1 p-1 bg-gray-100 rounded-t-lg dark:bg-gray-600">
                                    <template x-for="dia in ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']"
                                        :key="dia">
                                        <div class="p-2 text-xs font-semibold text-center text-gray-600 dark:text-gray-400"
                                            x-text="dia"></div>
                                    </template>
                                </div>

                                <!-- Dias do mês -->
                                <div
                                    class="grid grid-cols-7 gap-1 p-1 transition-colors duration-200 bg-white rounded-b-lg dark:bg-gray-700">
                                    <template x-for="(dia, index) in calendarDays" :key="index">
                                        <div class="p-1 transition-colors duration-200 rounded min-h-20"
                                            :class="{
                                                'bg-gray-50 dark:bg-gray-600 text-gray-400': !dia.isCurrentMonth,
                                                'bg-white dark:bg-gray-700': dia.isCurrentMonth && !dia.isToday,
                                                'bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-300 dark:border-blue-600': dia
                                                    .isToday
                                            }">
                                            <!-- Número do dia -->
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="px-1 text-sm font-medium rounded"
                                                    :class="{
                                                        'text-blue-600 dark:text-blue-400': dia.isToday,
                                                        'text-gray-900 dark:text-gray-200': dia.isCurrentMonth && !dia
                                                            .isToday,
                                                        'text-gray-400': !dia.isCurrentMonth
                                                    }"
                                                    x-text="dia.number"></span>

                                                <!-- Badge de quantidade de períodos -->
                                                <span x-show="dia.periodos.length > 0"
                                                    class="px-2 py-0.5 text-xs text-white rounded-full"
                                                    :class="dia.periodos[0]?.cor?.badge || 'bg-gray-500'"
                                                    x-text="dia.periodos.length"></span>

                                            </div>

                                            <!-- Períodos de férias -->
                                            <div class="space-y-1">
                                                <template x-for="periodo in dia.periodos.slice(0, 2)"
                                                    :key="periodo.id">
                                                    <div class="p-1 text-xs transition-all duration-200 rounded cursor-pointer hover:shadow-md"
                                                        :class="periodo.cor.bg + ' ' + periodo.cor.border + ' ' + periodo.cor
                                                            .text"
                                                        @click="openModal(periodo)"
                                                        :title="periodo.servidor + ' - ' + periodo.tipo + ' (' + periodo
                                                            .situacao + ')'"
                                                        x-text="periodo.servidor.split(' ')[0] + ' - ' + periodo.tipo">
                                                    </div>
                                                </template>

                                                <!-- Indicador de mais períodos -->
                                                {{-- <div x -show="dia.periodos.length>
                                                        2"
                                                    class="p-1 text-xs text-center text-gray-500 bg-gray-100 rounded cursor-pointer dark:bg-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-500"
                                                    @ click="openDayModal(dia)"
                                                    x -text="'+' + (dia.periodos.length - 2) + ' mais'"></div> --}}
                                                <div x-show="dia.periodos.length > 2"
                                                    class="p-1 text-xs text-center text-gray-500 transition-colors bg-gray-100 rounded cursor-pointer dark:bg-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-500"
                                                    @click="openDayModal(dia)"
                                                    x-text="'+' + (dia.periodos.length - 2) + ' mais'">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Legenda -->
                            <div class="flex flex-wrap gap-4 mt-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-4 h-4 mr-2 bg-green-100 border-l-4 border-green-500 dark:bg-green-900">
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Férias</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 mr-2 bg-blue-100 border-l-4 border-blue-500 dark:bg-blue-900">
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Abono</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 mr-2 bg-red-100 border-l-4 border-red-500 dark:bg-red-900">
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Interrompido</span>
                                </div>
                            </div>

                            <!-- Estatísticas do mês -->
                            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3">
                                <div class="p-3 text-center rounded bg-gray-50 dark:bg-gray-600">
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                                        x-text="totalPeriodosMes"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Períodos este mês</div>
                                </div>
                                <div class="p-3 text-center rounded bg-gray-50 dark:bg-gray-600">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400"
                                        x-text="totalServidoresMes"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Servidores</div>
                                </div>
                                <div class="p-3 text-center rounded bg-gray-50 dark:bg-gray-600">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400"
                                        x-text="totalDiasMes"></div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Dias de férias</div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de Detalhes do Período - FORA do card principal -->
                        <div x-show="showModal" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            <div class="w-full max-w-md mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-800">
                                <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200"
                                        x-text="selectedPeriodo?.servidor || 'Detalhes do Período'"></h3>
                                    <button @click="closeModal()"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="p-6 space-y-4" x-show="selectedPeriodo">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-600 dark:text-gray-400">Matrícula:</span>
                                        <span class="text-gray-800 dark:text-gray-200"
                                            x-text="selectedPeriodo?.matricula"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-600 dark:text-gray-400">Período:</span>
                                        <span class="text-gray-800 dark:text-gray-200"
                                            x-text="formatDate(selectedPeriodo?.inicio) + ' a ' + formatDate(selectedPeriodo?.fim)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-600 dark:text-gray-400">Duração:</span>
                                        <span class="text-gray-800 dark:text-gray-200"
                                            x-text="selectedPeriodo?.dias + ' dias'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-600 dark:text-gray-400">Tipo:</span>
                                        <span class="text-gray-800 dark:text-gray-200"
                                            x-text="selectedPeriodo?.tipo"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-600 dark:text-gray-400">Situação:</span>
                                        <span class="text-gray-800 dark:text-gray-200"
                                            x-text="selectedPeriodo?.situacao"></span>
                                    </div>
                                </div>

                                <div class="flex justify-end p-6 space-x-3 border-t dark:border-gray-700">
                                    <button @click="closeModal()"
                                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                        Fechar
                                    </button>
                                    <a :href="selectedPeriodo ? '/ferias/periodo/' + selectedPeriodo.id + '/detalhes' : '#'"
                                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700"
                                        x-show="selectedPeriodo">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>



                        <!-- Modal de Períodos do Dia - CORRIGIDO -->

                        <div x-show="modalDayAberto" x-cloak x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                            @click="modalDayAberto = false"> <!-- CORREÇÃO: evento de clique direto -->

                            <div class="w-full max-w-2xl bg-white rounded-lg shadow-xl dark:bg-gray-800 max-h-[90vh] overflow-hidden"
                                @click.stop> <!-- CORREÇÃO: prevenir propagação do clique -->

                                <!-- Cabeçalho do Modal -->
                                <div
                                    class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                                            x-text="`Períodos do dia ${modalDayData.formattedDate}`"></h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400"
                                            x-text="`${modalDayData.periodos?.length || 0} período(s) encontrado(s)`">
                                        </p>
                                    </div>
                                    <button @click="modalDayAberto = false"
                                        class="p-2 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:bg-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Conteúdo do Modal -->
                                <div class="p-6 overflow-y-auto max-h-[60vh]">
                                    <template x-if="modalDayData.periodos && modalDayData.periodos.length > 0">
                                        <div class="space-y-3">
                                            <template x-for="periodo in modalDayData.periodos" :key="periodo.id">
                                                <div class="p-4 transition-all duration-200 border rounded-lg cursor-pointer hover:shadow-md group"
                                                    :class="periodo.cor.border + ' ' + periodo.cor.bg + ' hover:scale-[1.02]'"
                                                    @click="openModal(periodo); modalDayAberto = false">
                                                    <!-- CORREÇÃO: fecha modal ao clicar no período -->

                                                    <!-- Cabeçalho do Período -->
                                                    <div class="flex items-start justify-between mb-2">
                                                        <div class="flex-1">
                                                            <h4 class="font-semibold" :class="periodo.cor.text"
                                                                x-text="periodo.servidor"></h4>
                                                            <p class="text-sm opacity-80" :class="periodo.cor.text"
                                                                x-text="periodo.matricula"></p>
                                                        </div>
                                                        <div class="flex flex-col items-end space-y-1">
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full"
                                                                :class="periodo.cor.badge"
                                                                x-text="periodo.tipo"></span>
                                                            <span
                                                                class="px-2 py-1 text-xs text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300"
                                                                x-text="periodo.situacao"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Detalhes do Período -->
                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <p class="font-medium text-gray-700 dark:text-gray-300">
                                                                Período</p>
                                                            <p class="text-gray-600 dark:text-gray-400"
                                                                x-text="`${formatDate(periodo.inicio)} a ${formatDate(periodo.fim)}`">
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-700 dark:text-gray-300">
                                                                Duração</p>
                                                            <p class="text-gray-600 dark:text-gray-400"
                                                                x-text="`${periodo.dias} dias`">
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Observações (se houver) -->
                                                    <div x-show="periodo.observacoes" class="mt-2">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400"
                                                            x-text="periodo.observacoes">
                                                        </p>
                                                    </div>

                                                    <!-- Ícone de clique -->
                                                    <div class="flex justify-end mt-2">
                                                        <span
                                                            class="text-xs text-gray-400 transition-opacity opacity-0 group-hover:opacity-100">
                                                            Clique para ver detalhes completos →
                                                        </span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Estado vazio -->
                                    <template x-if="!modalDayData.periodos || modalDayData.periodos.length === 0">
                                        <div class="py-8 text-center">
                                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="mb-2 text-lg font-medium text-gray-500 dark:text-gray-400">
                                                Nenhum período encontrado
                                            </h4>
                                            <p class="text-gray-400 dark:text-gray-500">Não há períodos de férias para
                                                esta data.</p>
                                        </div>
                                    </template>
                                </div>

                                <!-- Rodapé do Modal -->
                                <div class="flex justify-end p-6 border-t border-gray-200 dark:border-gray-700">
                                    <button @click="modalDayAberto = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    {{-- <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                        <div class="p-6 bg-white rounded shadow dark:bg-gray-700">
                            <h3 class="mb-4 text-lg font-bold">📊 Distribuição de Férias por Mês - {{ date('Y') }}
                            </h3>
                            <canvas id="graficoFeriasPorMes" height="200"></canvas>
                        </div>

                        <div class="p-6 bg-white rounded shadow dark:bg-gray-700">
                            <h3 class="mb-4 text-lg font-bold">📈 Situação dos Períodos</h3>
                            <canvas id="graficoSituacoes" height="200"></canvas>
                        </div>
                    </div> --}}

                    <!-- Últimos lançamentos -->
                    <div class="p-6 bg-white rounded shadow dark:bg-gray-700">
                        <h3 class="mb-4 text-lg font-bold">🕒 Últimos Lançamentos</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm bg-white rounded shadow dark:bg-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-500">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Servidor</th>
                                        <th class="px-4 py-2 text-left">Ano</th>
                                        <th class="px-4 py-2 text-left">Períodos</th>
                                        <th class="px-4 py-2 text-left">Situação</th>
                                        <th class="px-4 py-2 text-left">Períodos</th>
                                        <th class="px-4 py-2 text-left">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ultimos as $ferias)
                                        <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-500">
                                            <td class="px-4 py-2">
                                                <div class="font-medium dark:text-gray-200">
                                                    {{ $ferias->servidor->nome }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $ferias->servidor->matricula }}</div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    class="px-2 py-1 text-xs bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $ferias->ano_exercicio }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    class="font-semibold dark:text-gray-200">{{ $ferias->periodos->where('ativo', true)->count() }}</span>
                                            </td>
                                            <td class="px-4 py-2">
                                                @foreach ($ferias->periodos->where('ativo', true)->take(2) as $periodo)
                                                    <span
                                                        class="inline-block px-2 py-1 mb-1 text-xs rounded-full
                                                        {{ $periodo->situacao == 'Planejado'
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                            : ($periodo->situacao == 'Interrompido'
                                                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                                        {{ $periodo->situacao }}
                                                    </span>
                                                    @if (!$loop->last)
                                                        <br>
                                                    @endif
                                                @endforeach
                                                @if ($ferias->periodos->where('ativo', true)->count() > 2)
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400">+{{ $ferias->periodos->where('ativo', true)->count() - 2 }}
                                                        mais</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex flex-col gap-2">
                                                    @foreach ($ferias->periodos->where('ativo', true)->take(2) as $periodo)
                                                        <div
                                                            class="text-xs {{ $periodo->tipo === 'Abono' ? 'bg-red-200 px-2 py-1 rounded-md w-max dark:bg-red-900 dark:text-red-200' : 'dark:text-gray-300' }}">
                                                            {{ date('d/m/Y', strtotime($periodo->inicio)) }} -
                                                            {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                                            ({{ $periodo->dias }} dias - {{ $periodo->tipo }})
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('ferias.show', $ferias->id) }}"
                                                        class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded hover:bg-blue-200 text-nowrap dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800">
                                                        👁️ Ver
                                                    </a>
                                                    @if ($ferias->periodos->where('ativo', true)->whereIn('situacao', ['Planejado', 'Interrompido', 'Remarcado'])->count() > 0)
                                                        <button
                                                            class="px-3 py-1 text-xs text-green-600 bg-green-100 rounded hover:bg-green-200 text-nowrap dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800">
                                                            🔁 Remarcar
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                Nenhum lançamento de férias encontrado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($ultimos->hasPages())
                            <div class="mt-4">
                                {{ $ultimos->withQueryString()->onEachSide(3)->links('pagination::tailwind') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function dashboardCalendar() {
            return {
                currentYear: 2025,
                currentMonth: new Date().getMonth() + 1,
                currentMonthName: '',
                calendarDays: [],
                periodos: [],
                showModal: false,
                selectedPeriodo: null,
                loading: false,
                modalDayAberto: false,
                modalDayData: {
                    date: null,
                    formattedDate: '',
                    periodos: []
                },

                closeDayModal() {
                    this.modalDayAberto = false;
                },

                // Computed properties para estatísticas
                get totalPeriodosMes() {
                    return this.periodos.length;
                },

                get totalServidoresMes() {
                    const servidoresUnicos = new Set(this.periodos.map(p => p.matricula));
                    return servidoresUnicos.size;
                },

                get totalDiasMes() {
                    return this.periodos.reduce((total, periodo) => total + periodo.dias, 0);
                },

                init() {
                    this.generateCalendar();
                },

                generateCalendar() {
                    this.loading = true;

                    fetch(`/dashboard/calendario?mes=${this.currentMonth}&ano=${this.currentYear}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro na requisição');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // console.log('Dados recebidos:', data);
                            // CORREÇÃO: data.periodos em vez de data
                            this.periodos = data || [];

                            // Aplicar cores baseadas na situação
                            this.periodos = this.periodos.map(periodo => {
                                // Se for interrompido, força cor vermelha
                                if (periodo.situacao === 'Interrompido') {
                                    periodo.cor = {
                                        bg: 'bg-red-100 dark:bg-red-900',
                                        border: 'border-l-red-500',
                                        text: 'text-red-800 dark:text-red-200',
                                        badge: 'bg-red-500'
                                    };
                                }
                                return periodo;
                            });

                            this.currentMonthName = data.mes_nome || this.getMonthName(this.currentMonth);
                            this.currentYear = data.ano || this.currentYear;
                            this.buildCalendarGrid();
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Erro ao carregar calendário:', error);
                            this.loading = false;
                            // Fallback: gerar calendário vazio
                            this.buildCalendarGrid();
                        });
                },

                getMonthName(month) {
                    const months = [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ];
                    return months[month - 1] || '';
                },

                buildCalendarGrid() {
                    const firstDay = new Date(this.currentYear, this.currentMonth - 1, 1);
                    const lastDay = new Date(this.currentYear, this.currentMonth, 0);
                    const firstDayOfWeek = firstDay.getDay();
                    const today = new Date();


                    // CORREÇÃO: Ajustar para o fuso horário brasileiro
                    const adjustTimezone = (date) => {
                        return new Date(date.getTime() + date.getTimezoneOffset() * 60000);
                    };

                    const days = [];

                    // Dias do mês anterior
                    const prevMonthLastDay = new Date(this.currentYear, this.currentMonth - 1, 0).getDate();
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        days.push({
                            number: prevMonthLastDay - i,
                            isCurrentMonth: false,
                            isToday: false,
                            periodos: []
                        });
                    }

                    // Dias do mês atual
                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const date = new Date(this.currentYear, this.currentMonth - 1, day);
                        const isToday = date.toDateString() === today.toDateString();

                        // CORREÇÃO: Normalizar datas considerando o fuso horário
                        const dayPeriodos = this.periodos.filter(periodo => {
                            try {
                                // Ajustar o fuso horário para as datas do período
                                const inicio = new Date(periodo.inicio +
                                    'T00:00:00-03:00'); // Forçar horário de Brasília
                                const fim = new Date(periodo.fim + 'T23:59:59-03:00'); // Forçar horário de Brasília

                                // Normalizar a data atual também
                                const currentDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                                const currentDateStart = new Date(currentDate.getTime() - (currentDate
                                    .getTimezoneOffset() * 60000));

                                const periodoInicio = new Date(inicio.getFullYear(), inicio.getMonth(), inicio
                                    .getDate());
                                const periodoFim = new Date(fim.getFullYear(), fim.getMonth(), fim.getDate());
                                const currentDateNormalized = new Date(currentDateStart.getFullYear(),
                                    currentDateStart.getMonth(), currentDateStart.getDate());

                                return currentDateNormalized >= periodoInicio && currentDateNormalized <=
                                    periodoFim;
                            } catch (e) {
                                console.error('Erro ao processar período:', periodo, e);
                                return false;
                            }
                        });

                        // console.log(`Dia ${day}:`, dayPeriodos.length, 'períodos');

                        days.push({
                            number: day,
                            isCurrentMonth: true,
                            isToday: isToday,
                            date: date,
                            periodos: dayPeriodos
                        });
                    }

                    // Dias do próximo mês
                    const totalCells = 42; // 6 semanas
                    const remainingCells = totalCells - days.length;
                    for (let day = 1; day <= remainingCells; day++) {
                        days.push({
                            number: day,
                            isCurrentMonth: false,
                            isToday: false,
                            periodos: []
                        });
                    }

                    this.calendarDays = days;
                    // console.log('Calendar days:', this.calendarDays); // Debug
                },

                previousMonth() {
                    if (this.loading) return;

                    this.currentMonth--;
                    if (this.currentMonth < 1) {
                        this.currentMonth = 12;
                        this.currentYear--;
                    }
                    this.generateCalendar();
                },

                nextMonth() {
                    if (this.loading) return;

                    this.currentMonth++;
                    if (this.currentMonth > 12) {
                        this.currentMonth = 1;
                        this.currentYear++;
                    }
                    this.generateCalendar();
                },

                openModal(periodo) {
                    this.selectedPeriodo = periodo;
                    this.showModal = true;
                    // console.log('Abrindo modal para:', periodo, this.selectedPeriodo);
                },

                // openDayModal(dia) {
                //     if (dia.periodos.length > 0) {
                //         const servidores = dia.periodos.map(p =>
                //             `• ${p.servidor} - ${p.tipo} (${p.situacao})`
                //         ).join('\n');

                //         alert(`Períodos em ${this.formatDate(dia.date)}:\n\n${servidores}`);
                //     }
                // },
                // Substitua a função openDayModal por esta:
                openDayModal(dia) {
                    if (dia.periodos.length > 0) {
                        // console.log('Abrindo modal do dia:', dia);
                        // console.log('Função openDayModal chamada para o dia:', dia.number);
                        // console.log('Períodos disponíveis:', dia.periodos.length);

                        // Fechar o modal de período individual se estiver aberto
                        this.showModal = false;
                        this.selectedPeriodo = null;

                        // Configurar os dados do modal do dia
                        this.modalDayData = {
                            date: dia.date,
                            formattedDate: this.formatDate(dia.date),
                            periodos: dia.periodos.map(periodo => ({
                                ...periodo,
                                cor: periodo.cor || this.getPeriodoColor(periodo)
                            }))
                        };

                        // Abrir o modal do dia
                        this.modalDayAberto = true;
                        // console.log('Modal do dia aberto:', this.modalDayAberto);
                    }
                },
                // Função auxiliar para obter cores (se não existir)
                getPeriodoColor(periodo) {
                    const cores = {
                        'Abono': {
                            bg: 'bg-yellow-100 dark:bg-yellow-900/20',
                            border: 'border-yellow-300 dark:border-yellow-600',
                            text: 'text-yellow-800 dark:text-yellow-300',
                            badge: 'bg-yellow-500 text-white'
                        },
                        'Férias': {
                            bg: 'bg-blue-100 dark:bg-blue-900/20',
                            border: 'border-blue-300 dark:border-blue-600',
                            text: 'text-blue-800 dark:text-blue-300',
                            badge: 'bg-blue-500 text-white'
                        },
                        'Usufruído': {
                            bg: 'bg-green-100 dark:bg-green-900/20',
                            border: 'border-green-300 dark:border-green-600',
                            text: 'text-green-800 dark:text-green-300',
                            badge: 'bg-green-500 text-white'
                        }
                    };

                    return cores[periodo.tipo] || {
                        bg: 'bg-gray-100 dark:bg-gray-700',
                        border: 'border-gray-300 dark:border-gray-600',
                        text: 'text-gray-800 dark:text-gray-300',
                        badge: 'bg-gray-500 text-white'
                    };
                },

                closeModal() {
                    this.showModal = false;
                    this.selectedPeriodo = null;
                },

                // POR ESTA:
                formatDate(dateString) {
                    if (!dateString) return '';

                    // Método mais seguro: processar manualmente a string
                    if (typeof dateString === 'string' && dateString.includes('-')) {
                        const [year, month, day] = dateString.split('-');
                        return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
                    }

                    // Fallback para objetos Date
                    try {
                        const date = new Date(dateString);
                        if (isNaN(date.getTime())) {
                            return 'Data inválida';
                        }
                        return date.toLocaleDateString('pt-BR');
                    } catch (e) {
                        return 'Data inválida';
                    }
                }
            }
        }


        // Inicializar Alpine.js quando o DOM estiver pronto
        document.addEventListener('DOMContentLoaded', function() {
            // console.log('DOM carregado, Alpine.js deve estar funcionando');

            // Seus gráficos existentes...
            const ctxMes = document.getElementById('graficoFeriasPorMes');
            if (ctxMes) {
                new Chart(ctxMes.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($meses),
                        datasets: [{
                            label: 'Períodos de Férias',
                            data: @json($dadosGrafico),
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Quantidade de Períodos'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Meses'
                                }
                            }
                        }
                    }
                });
            }

            const ctxSituacao = document.getElementById('graficoSituacoes');
            if (ctxSituacao) {
                new Chart(ctxSituacao.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Planejado', 'Interrompido', 'Remarcado', 'Outros'],
                        datasets: [{
                            data: [
                                {{ $totalFeriasPlanejadas - $totalRemarcacoes }},
                                {{ $totalInterrompidas }},
                                {{ $totalRemarcacoes }},
                                0
                            ],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.6)',
                                'rgba(239, 68, 68, 0.6)',
                                'rgba(245, 158, 11, 0.6)',
                                'rgba(156, 163, 175, 0.6)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Garantir que o modal tenha z-index alto */
        .fixed.inset-0.z-50 {
            z-index: 9999;
        }
    </style>
</x-app-layout>
