<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Filtro de Férias e Abonos
        </h2>
    </x-slot>

    <div class="py-12" x-data="feriasFiltro()" x-init="init()">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Filtros -->
                    <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-700">
                        <h3 class="mb-4 text-lg font-bold">🔍 Filtros</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <!-- Tipo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tipo
                                    </label>
                                    <select x-model="filtros.tipo" @change="aplicarFiltros"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-500 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Todos os tipos</option>
                                        <option value="ferias">Férias</option>
                                        <option value="abono">Abono</option>
                                    </select>
                                </div>

                                <!-- Exercício -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Ano de Exercício
                                    </label>
                                    <select x-model="filtros.ano_exercicio" @change="aplicarFiltros"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-500 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Todos os exercícios</option>
                                        @for ($ano = date('Y') + 1; $ano >= 2020; $ano--)
                                            <option value="{{ $ano }}">{{ $ano }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Mês -->
                                @php
                                    $meses = [
                                        1 => 'Janeiro',
                                        2 => 'Fevereiro',
                                        3 => 'Março',
                                        4 => 'Abril',
                                        5 => 'Maio',
                                        6 => 'Junho',
                                        7 => 'Julho',
                                        8 => 'Agosto',
                                        9 => 'Setembro',
                                        10 => 'Outubro',
                                        11 => 'Novembro',
                                        12 => 'Dezembro',
                                    ];
                                @endphp
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Mês
                                    </label>
                                    <select x-model="filtros.mes" @change="aplicarFiltros"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-500 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Todos os meses</option>

                                        @foreach ($meses as $numero => $nome)
                                            <option value="{{ $numero }}">{{ $nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Situação -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Situação
                                    </label>
                                    <select x-model="filtros.situacao" @change="aplicarFiltros"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-500 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Todas as situações</option>
                                        <option value="Planejado">Planejado</option>
                                        <option value="Usufruído">Usufruído</option>
                                        <option value="Interrompido">Interrompido</option>
                                        <option value="Remarcado">Remarcado</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Busca por servidor -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Buscar Servidor
                                    </label>
                                    <input type="text" x-model="filtros.busca" @input.debounce.500ms="aplicarFiltros"
                                        placeholder="Nome, matrícula ou CPF"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-500 focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="flex items-end space-x-2">
                                    <button @click="limparFiltros"
                                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                                        🗑️ Limpar
                                    </button>

                                    <!-- Botão de loading -->
                                    <div x-show="carregando" class="flex items-center px-4 py-2 text-gray-500">
                                        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Carregando...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div x-show="estatisticas.totalRegistros > 0" class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <div
                            class="p-4 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                                x-text="estatisticas.totalRegistros"></div>
                            <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total de Períodos</div>
                        </div>
                        <div
                            class="p-4 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400"
                                x-text="estatisticas.totalServidores"></div>
                            <div class="text-sm font-medium text-green-700 dark:text-green-300">Servidores</div>
                        </div>
                        <div
                            class="p-4 border border-purple-200 rounded-lg bg-purple-50 dark:bg-purple-900/20 dark:border-purple-800">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400"
                                x-text="estatisticas.totalDias"></div>
                            <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Dias Totais</div>
                        </div>
                        <div
                            class="p-4 border border-orange-200 rounded-lg bg-orange-50 dark:bg-orange-900/20 dark:border-orange-800">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400"
                                x-text="estatisticas.totalUsufruidos"></div>
                            <div class="text-sm font-medium text-orange-700 dark:text-orange-300">Dias Usufruídos</div>
                        </div>
                    </div>

                    <!-- Resultados -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-700">
                        <div
                            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-bold">
                                📋 Resultados
                                <span x-show="periodos.length > 0" class="text-sm font-normal text-gray-500">
                                    (<span x-text="periodos.length"></span> registros)
                                </span>
                            </h3>

                            <div x-show="periodos.length > 0" class="flex space-x-2">
                                <button @click="gerarPDF" :disabled="gerandoPDF"
                                    class="flex items-center px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!gerandoPDF">🖨️ PDF</span>
                                    <span x-show="gerandoPDF" class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Gerando...
                                    </span>
                                </button>

                                <button @click="gerarExcel" :disabled="gerandoExcel"
                                    class="flex items-center px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!gerandoExcel">📊 Excel</span>
                                    <span x-show="gerandoExcel" class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Gerando...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Loading -->
                        <div x-show="carregando && periodos.length === 0" class="p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">Carregando resultados...</p>
                        </div>

                        <!-- Resultados -->
                        <div x-show="!carregando && periodos.length > 0"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                                @click="ordenarPor('servidor')">
                                                Servidor <span x-text="obterIconeOrdenacao('servidor')"></span>
                                            </th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                                @click="ordenarPor('exercicio')">
                                                Exercício <span x-text="obterIconeOrdenacao('exercicio')"></span>
                                            </th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                                @click="ordenarPor('inicio')">
                                                Período <span x-text="obterIconeOrdenacao('inicio')"></span>
                                            </th>
                                            <th
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                                Tipo
                                            </th>
                                            <th
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                                Situação
                                            </th>
                                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                                @click="ordenarPor('dias')">
                                                Dias <span x-text="obterIconeOrdenacao('dias')"></span>
                                            </th>
                                            <th
                                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                                Ações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-700 dark:divide-gray-600">
                                        <template x-for="periodo in periodos" :key="periodo.id">
                                            <tr
                                                class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                                        x-text="periodo.servidor_nome"></div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                                        x-text="periodo.servidor_matricula"></div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200"
                                                        x-text="periodo.ano_exercicio"></span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        <span x-text="formatarData(periodo.inicio)"></span> a
                                                        <span x-text="formatarData(periodo.fim)"></span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span
                                                        :class="periodo.tipo == 'Abono' ?
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'"
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full"
                                                        x-text="periodo.tipo">
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span :class="obterClasseSituacao(periodo.situacao)"
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full"
                                                        x-text="periodo.situacao">
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-900 whitespace-nowrap dark:text-gray-100"
                                                    x-text="periodo.dias + ' dias'"></td>
                                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                    <a :href="'/ferias/' + periodo.ferias_id + '/show'"
                                                        class="text-blue-600 transition-colors duration-200 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        👁️ Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            <div x-show="paginacao.total > paginacao.per_page"
                                class="p-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        Mostrando <span x-text="paginacao.from"></span> a <span
                                            x-text="paginacao.to"></span> de <span x-text="paginacao.total"></span>
                                        resultados
                                    </div>
                                    <div class="flex space-x-1">
                                        <button @click="mudarPagina(paginacao.current_page - 1)"
                                            :disabled="paginacao.current_page === 1"
                                            class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Anterior
                                        </button>
                                        <template x-for="pagina in paginacao.links" :key="pagina.label">
                                            <button @click="mudarPagina(pagina.label)"
                                                :class="pagina.active ? 'bg-blue-600 text-white' :
                                                    'text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-600'"
                                                class="px-3 py-1 text-sm border border-gray-300 rounded-md dark:border-gray-600"
                                                x-text="pagina.label" :disabled="!pagina.url || pagina.active">
                                            </button>
                                        </template>
                                        <button @click="mudarPagina(paginacao.current_page + 1)"
                                            :disabled="paginacao.current_page === paginacao.last_page"
                                            class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Próxima
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sem resultados -->
                        <div x-show="!carregando && periodos.length === 0 && filtrosAplicados"
                            class="p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Nenhum período
                                encontrado</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Tente ajustar os filtros para encontrar o
                                que procura.</p>
                        </div>

                        <!-- Instruções iniciais -->
                        <div x-show="!carregando && periodos.length === 0 && !filtrosAplicados"
                            class="p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Use os filtros para
                                buscar</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Selecione os filtros acima para visualizar
                                os períodos de férias e abonos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function feriasFiltro() {
            return {
                carregando: false,
                gerandoPDF: false,
                gerandoExcel: false,
                filtros: {
                    tipo: '',
                    ano_exercicio: '',
                    mes: '',
                    situacao: '',
                    busca: ''
                },
                periodos: [],
                estatisticas: {
                    totalRegistros: 0,
                    totalServidores: 0,
                    totalDias: 0,
                    totalUsufruidos: 0
                },
                paginacao: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 20,
                    total: 0,
                    from: 0,
                    to: 0,
                    links: []
                },
                ordenacao: {
                    campo: 'inicio',
                    direcao: 'desc'
                },
                filtrosAplicados: false,

                init() {
                    // Carregar filtros da URL se existirem
                    this.carregarFiltrosDaURL();
                    // Aplicar filtros iniciais
                    if (this.filtrosAplicados) {
                        this.aplicarFiltros();
                    }
                },

                carregarFiltrosDaURL() {
                    const urlParams = new URLSearchParams(window.location.search);
                    let algumFiltro = false;

                    ['tipo', 'ano_exercicio', 'mes', 'situacao', 'busca'].forEach(filtro => {
                        if (urlParams.has(filtro)) {
                            this.filtros[filtro] = urlParams.get(filtro);
                            algumFiltro = true;
                        }
                    });

                    if (urlParams.has('page')) {
                        this.paginacao.current_page = parseInt(urlParams.get('page'));
                    }

                    if (urlParams.has('ordenar')) {
                        this.ordenacao.campo = urlParams.get('ordenar');
                    }

                    if (urlParams.has('direcao')) {
                        this.ordenacao.direcao = urlParams.get('direcao');
                    }

                    this.filtrosAplicados = algumFiltro;
                },

                async aplicarFiltros() {
                    this.carregando = true;
                    this.filtrosAplicados = Object.values(this.filtros).some(valor => valor !== '');

                    try {
                        const params = new URLSearchParams({
                            ...this.filtros,
                            page: this.paginacao.current_page,
                            ordenar: this.ordenacao.campo,
                            direcao: this.ordenacao.direcao
                        });

                        const response = await fetch(`/ferias/filtro/dados?${params}`);
                        const data = await response.json();

                        this.periodos = data.periodos.data || data.periodos;
                        this.estatisticas = data.estatisticas;
                        this.paginacao = data.paginacao || {
                            current_page: 1,
                            last_page: 1,
                            per_page: 20,
                            total: data.periodos.total || data.periodos.length,
                            from: 1,
                            to: data.periodos.length,
                            links: []
                        };

                        // Atualizar URL sem recarregar a página
                        window.history.replaceState({}, '', `${location.pathname}?${params}`);

                    } catch (error) {
                        console.error('Erro ao carregar dados:', error);
                    } finally {
                        this.carregando = false;
                    }
                },

                async mudarPagina(pagina) {
                    if (pagina < 1 || pagina > this.paginacao.last_page) return;

                    this.paginacao.current_page = pagina;
                    await this.aplicarFiltros();
                    // Rolagem suave para o topo
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },

                ordenarPor(campo) {
                    if (this.ordenacao.campo === campo) {
                        this.ordenacao.direcao = this.ordenacao.direcao === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.ordenacao.campo = campo;
                        this.ordenacao.direcao = 'asc';
                    }
                    this.aplicarFiltros();
                },

                obterIconeOrdenacao(campo) {
                    if (this.ordenacao.campo !== campo) return '↕️';
                    return this.ordenacao.direcao === 'asc' ? '↑' : '↓';
                },

                limparFiltros() {
                    this.filtros = {
                        tipo: '',
                        ano_exercicio: '',
                        mes: '',
                        situacao: '',
                        busca: ''
                    };
                    this.paginacao.current_page = 1;
                    this.ordenacao = {
                        campo: 'inicio',
                        direcao: 'desc'
                    };
                    this.filtrosAplicados = false;
                    this.aplicarFiltros();
                    window.history.replaceState({}, '', location.pathname);
                },

                // formatarData(dataString) {
                //     if (!dataString) return '';
                //     const data = new Date(dataString + 'T00:00:00');
                //     return data.toLocaleDateString('pt-BR');
                // },
                formatarData(dataString) {
                    if (!dataString) return '-';

                    // Se for string no formato YYYY-MM-DD
                    if (typeof dataString === 'string' && dataString.length === 10) {
                        const partes = dataString.split('-');
                        if (partes.length === 3) {
                            return `${partes[2]}/${partes[1]}/${partes[0]}`;
                        }
                    }

                    // Fallback
                    try {
                        const data = new Date(dataString);
                        return data.toLocaleDateString('pt-BR');
                    } catch {
                        return '-';
                    }
                },

                obterClasseSituacao(situacao) {
                    const classes = {
                        'Usufruído': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'Interrompido': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        'Planejado': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                        'Remarcado': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                    };
                    return classes[situacao] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                },

                async gerarPDF() {
                    this.gerandoPDF = true;
                    try {
                        const params = new URLSearchParams(this.filtros);
                        window.open(`/ferias/filtro-pdf?${params}`, '_blank');
                    } catch (error) {
                        console.error('Erro ao gerar PDF:', error);
                    } finally {
                        setTimeout(() => this.gerandoPDF = false, 1000);
                    }
                },

                async gerarExcel() {
                    this.gerandoExcel = true;
                    try {
                        const params = new URLSearchParams(this.filtros);
                        window.open(`/ferias/filtro/excel?${params}`, '_blank');
                    } catch (error) {
                        console.error('Erro ao gerar Excel:', error);
                    } finally {
                        setTimeout(() => this.gerandoExcel = false, 1000);
                    }
                }
            }
        }
    </script>
</x-app-layout>
