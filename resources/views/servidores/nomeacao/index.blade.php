<x-app-layout>
    <div class="container px-3 py-4 mx-auto sm:px-4 sm:py-8" x-data="servidoresManager()" x-init="init()">

        <!-- Cabeçalho Mobile -->
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between sm:mb-8">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">📋 Servidores Nomeados</h1>
                <p class="text-sm text-gray-600 sm:text-base">Gestão de nomeações e vínculos funcionais</p>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-3">
                <a href="{{ route('servidores.index') }}"
                    class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm text-gray-700 transition-colors border border-gray-300 rounded-lg sm:flex-none sm:px-4 sm:text-base hover:bg-gray-50">
                    <span class="sm:mr-1">←</span>
                    <span>Voltar</span>
                </a>
                <a href="#"
                    class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm text-white transition-colors bg-green-600 rounded-lg sm:flex-none sm:px-4 sm:text-base hover:bg-green-700">
                    <span class="mr-1">➕</span>
                    <span class="sm:hidden">Novo</span>
                    <span class="hidden sm:inline">Nova Nomeação</span>
                </a>
            </div>
        </div>

        <!-- Filtros e Busca Mobile -->
        <div class="p-4 mb-4 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-6 sm:mb-6">
            <h3 class="mb-3 text-lg font-semibold text-gray-900 sm:text-xl">🔍 Filtros e Busca</h3>

            <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">
                <!-- Busca por Nome/Matrícula -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Buscar</label>
                    <input type="text" x-model="filtros.search" @input.debounce.500ms="buscarServidores()"
                        placeholder="Nome ou matrícula..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filtro por Situação -->
                <div>
                    <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Situação</label>
                    <select x-model="filtros.status" @change="buscarServidores()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        <option value="ativo">Ativos</option>
                        <option value="inativo">Inativos</option>
                    </select>
                </div>

                <!-- Filtro por Departamento -->
                <div>
                    <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Departamento</label>
                    <select x-model="filtros.departamento" @change="buscarServidores()"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento }}">{{ $departamento }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botões de Ação -->
                <div class="flex gap-2 sm:items-end">
                    <button @click="limparFiltros()"
                        class="flex-1 px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 sm:flex-none">
                        <span class="sm:hidden">🗑️</span>
                        <span class="hidden sm:inline">Limpar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="carregando" class="p-8 text-center" x-cloak>
            <div class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-800 bg-blue-100 rounded-lg">
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Carregando servidores...
            </div>
        </div>

        <!-- Estatísticas -->
        <div x-show="!carregando && servidores.length > 0"
            class="grid grid-cols-2 gap-3 mb-6 sm:grid-cols-4 sm:gap-4 sm:mb-8">
            <div class="p-3 bg-white border border-blue-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-blue-600 sm:text-2xl" x-text="estatisticas.total"></div>
                <div class="text-xs font-medium text-blue-700 sm:text-sm">Total</div>
            </div>
            <div class="p-3 bg-white border border-green-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-green-600 sm:text-2xl" x-text="estatisticas.ativos"></div>
                <div class="text-xs font-medium text-green-700 sm:text-sm">Ativos</div>
            </div>
            <div class="p-3 bg-white border border-orange-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-orange-600 sm:text-2xl" x-text="estatisticas.inativos"></div>
                <div class="text-xs font-medium text-orange-700 sm:text-sm">Inativos</div>
            </div>
            <div class="p-3 bg-white border border-purple-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-purple-600 sm:text-2xl" x-text="estatisticas.semVinculo"></div>
                <div class="text-xs font-medium text-purple-700 sm:text-sm">Sem Vínculo</div>
            </div>
        </div>

        <!-- Lista de Servidores -->
        <div x-show="!carregando" class="bg-white border border-gray-200 shadow-sm rounded-xl">
            <!-- Cabeçalho da Tabela -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 sm:text-xl">
                    📊 Lista de Servidores
                    <span class="text-sm font-normal text-gray-500" x-text="`(${servidores.length})`"></span>
                </h3>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 sm:text-sm">Ordenar:</span>
                    <select x-model="filtros.sort" @change="buscarServidores()"
                        class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="nome">Nome A-Z</option>
                        <option value="nome_desc">Nome Z-A</option>
                        <option value="matricula">Matrícula</option>
                        <option value="departamento">Departamento</option>
                    </select>
                </div>
            </div>

            <!-- Conteúdo -->
            <template x-if="servidores.length > 0">
                <div>
                    <!-- Vista Mobile - Cards -->
                    <div class="sm:hidden">
                        <div class="divide-y divide-gray-200">
                            <template x-for="servidor in servidores" :key="servidor.uniqueId">
                                <div class="p-4 transition-colors duration-200 hover:bg-gray-50">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 truncate" x-text="servidor.nome"></h4>
                                            <p class="font-mono text-xs text-gray-500" x-text="servidor.matricula"></p>
                                        </div>
                                        <div class="flex gap-1 ml-2">
                                            <span x-show="servidor.vinculo_atual.status === 'Ativo'"
                                                class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                Ativo
                                            </span>
                                            <span x-show="servidor.vinculo_atual.status === 'Inativo'"
                                                class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                Inativo
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Informações do Vínculo -->
                                    <template x-if="servidor.vinculo_atual">
                                        <div class="space-y-2 text-sm text-gray-600">
                                            <div class="flex justify-between">
                                                <span>Cargo:</span>
                                                <span class="font-medium text-gray-900"
                                                    x-text="servidor.vinculo_atual.cargo || 'N/I'"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Depto:</span>
                                                <span class="font-medium text-gray-900"
                                                    x-text="servidor.vinculo_atual.departamento || 'N/I'"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Secretaria:</span>
                                                <span class="font-medium text-gray-900"
                                                    x-text="servidor.vinculo_atual.secretaria || 'N/I'"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="servidor.vinculo_atual?.status === null">
                                        <div class="p-2 text-xs text-center text-yellow-800 bg-yellow-100 rounded">
                                            Sem vínculo ativo
                                        </div>
                                    </template>

                                    <!-- Ações Mobile -->
                                    <div class="flex gap-2 mt-3">
                                        <a :href="`/servidores/${servidor.id}`"
                                            class="flex-1 px-2 py-1 text-xs text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                                            👁️ Ver
                                        </a>
                                        <a :href="`/servidores/${servidor.id}/edit`"
                                            class="flex-1 px-2 py-1 text-xs text-center text-white bg-green-600 rounded hover:bg-green-700">
                                            ✏️ Editar
                                        </a>
                                        <a :href="`/servidores/${servidor.id}/nomeacao/create`"
                                            class="flex-1 px-2 py-1 text-xs text-center text-white bg-purple-600 rounded hover:bg-purple-700">
                                            ➕ Vincular
                                        </a>
                                        <!-- Botão de Exoneração -->
                                        <!-- se o servidor estiver ativo, ele pode ser exonerado -->

                                        <template x-if="servidor.vinculo_atual?.status === 'Ativo'">
                                            <a :href="urlExoneracao(servidor.id)"
                                                class="text-red-600 hover:text-red-900" title="Exonerar servidor">
                                                🚨
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Vista Desktop - Tabela -->
                    <div class="hidden sm:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase cursor-pointer sortable"
                                            @click="ordenarPor('nome')">
                                            Nome
                                            <span x-show="filtros.sort === 'nome'" class="ml-1">↑</span>
                                            <span x-show="filtros.sort === 'nome_desc'" class="ml-1">↓</span>
                                        </th>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase cursor-pointer sortable"
                                            @click="ordenarPor('matricula')">
                                            Matrícula
                                            <span x-show="filtros.sort === 'matricula'" class="ml-1">↑</span>
                                        </th>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                            Cargo
                                        </th>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                            Departamento
                                        </th>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                            Secretaria
                                        </th>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                            Situação
                                        </th>
                                        <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="servidor in servidores" :key="servidor.uniqueId">
                                        <tr class="transition-colors duration-200 hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-semibold text-white rounded-full bg-gradient-to-r from-green-500 to-lime-500"
                                                        x-text="servidor.iniciais"></div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900"
                                                            x-text="servidor.nome"></div>
                                                        <div class="text-sm text-gray-500" x-text="servidor.email">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 font-mono text-sm text-gray-900"
                                                x-text="servidor.matricula"></td>
                                            <td class="px-4 py-3 text-sm text-gray-900"
                                                x-text="servidor.vinculo_atual?.cargo || 'N/I'"></td>
                                            <td class="px-4 py-3 text-sm text-gray-900"
                                                x-text="servidor.vinculo_atual?.departamento || 'N/I'"></td>
                                            <td class="px-4 py-3 text-sm text-gray-900"
                                                x-text="servidor.vinculo_atual?.secretaria || 'N/I'"></td>
                                            <td class="px-4 py-3 text-sm">
                                                <span x-show="servidor.vinculo_atual?.status === 'Ativo'"
                                                    class="inline-flex px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                    Ativo
                                                </span>
                                                <span x-show="servidor.vinculo_atual?.status === 'Inativo'"
                                                    class="inline-flex px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                    Inativo
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium">
                                                <div class="flex gap-2">
                                                    <a :href="`/servidores/${servidor.id}`"
                                                        class="text-blue-600 hover:text-blue-900"
                                                        title="Ver detalhes">
                                                        👁️
                                                    </a>
                                                    <a :href="`/servidores/${servidor.id}/edit`"
                                                        class="text-green-600 hover:text-green-900"
                                                        title="Editar servidor">
                                                        ✏️
                                                    </a>
                                                    <a :href="`/servidores/${servidor.id}/nomeacao/create`"
                                                        class="text-purple-600 hover:text-purple-900"
                                                        title="Criar vínculo">
                                                        ➕
                                                    </a>
                                                    <!-- Botão de Exoneração -->
                                                    <!-- se o servidor estiver ativo, ele pode ser exonerado -->

                                                    <template x-if="servidor.vinculo_atual?.status === 'Ativo'">
                                                        <a :href="urlExoneracao(servidor.id)"
                                                            class="text-red-400 hover:text-red-600"
                                                            title="Exonerar servidor">
                                                            <i class="w-4 mr-2 fas fa-user-slash"></i>
                                                        </a>
                                                    </template>
                                                    {{-- <a :href="urlExoneracao(servidor.id)"
                                                        class="text-red-400 hover:text-red-600"
                                                        title="Efetivar exoneração">
                                                        <i class="w-4 mr-2 fas fa-user-slash"></i>
                                                    </a> --}}

                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Paginação -->
            <div x-show="servidores.length > 0 && !carregando" class="px-6 py-4 border-t border-gray-100">
                <div class="flex flex-col items-center justify-between sm:flex-row">
                    <!-- Info de paginação -->
                    <div class="mb-4 text-sm text-gray-700 sm:mb-0">
                        <span x-text="contadorServidores()"></span>
                    </div>

                    <!-- Controles de paginação -->
                    <div class="flex flex-wrap items-center justify-center space-x-1">
                        <!-- Primeira página -->
                        <button @click="mudarPagina(1)" :disabled="pagination.current_page === 1"
                            :class="pagination.current_page === 1 ?
                                'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400' :
                                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'"
                            class="px-3 py-2 text-sm transition-colors border rounded-md">
                            «
                        </button>

                        <!-- Página anterior -->
                        <button @click="mudarPagina(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            :class="pagination.current_page === 1 ?
                                'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400' :
                                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'"
                            class="px-3 py-2 text-sm transition-colors border rounded-md">
                            ‹
                        </button>

                        <!-- Páginas -->
                        <template x-for="page in paginasVisiveis()" :key="page">
                            <button @click="if (page !== '...') mudarPagina(page)"
                                :class="page === pagination.current_page ?
                                    'bg-blue-600 text-white border-blue-600' :
                                    (page === '...' ?
                                        'bg-gray-100 text-gray-400 cursor-default' :
                                        'bg-white text-gray-700 hover:bg-gray-50 border-gray-300')"
                                class="px-3 py-2 text-sm border rounded-md min-w-[40px] transition-colors"
                                :disabled="page === '...'" x-text="page">
                            </button>
                        </template>

                        <!-- Próxima página -->
                        <button @click="mudarPagina(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            :class="pagination.current_page === pagination.last_page ?
                                'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400' :
                                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'"
                            class="px-3 py-2 text-sm transition-colors border rounded-md">
                            ›
                        </button>

                        <!-- Última página -->
                        <button @click="mudarPagina(pagination.last_page)"
                            :disabled="pagination.current_page === pagination.last_page"
                            :class="pagination.current_page === pagination.last_page ?
                                'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400' :
                                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'"
                            class="px-3 py-2 text-sm transition-colors border rounded-md">
                            »
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estado Vazio -->
            <template x-if="servidores.length === 0 && !carregando">
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 sm:w-16 sm:h-16" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-medium text-gray-900 sm:text-xl">Nenhum servidor encontrado</h3>
                    <p class="mb-4 text-sm text-gray-600 sm:text-base">Não há servidores nomeados com os filtros
                        aplicados.</p>
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <span class="mr-2">➕</span>
                        Cadastrar Primeira Nomeação
                    </a>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('servidoresManager', () => ({
                // Estados
                servidores: [],
                estatisticas: {
                    total: 0,
                    ativos: 0,
                    inativos: 0,
                    semVinculo: 0
                },
                filtros: {
                    search: '',
                    status: '',
                    departamento: '',
                    sort: 'nome'
                },
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 15,
                    total: 0,
                    from: 0,
                    to: 0
                },
                carregando: false,

                async init() {
                    console.log('Servidores Manager inicializado');
                    await this.carregarFiltrosDaURL();
                    await this.buscarServidores();
                },

                carregarFiltrosDaURL() {
                    const urlParams = new URLSearchParams(window.location.search);

                    ['search', 'status', 'departamento', 'sort'].forEach(filtro => {
                        if (urlParams.has(filtro)) {
                            this.filtros[filtro] = urlParams.get(filtro);
                        }
                    });

                    if (urlParams.has('page')) {
                        this.pagination.current_page = parseInt(urlParams.get('page')) || 1;
                    }
                },

                async buscarServidores() {
                    this.carregando = true;

                    try {
                        const params = new URLSearchParams({
                            ...this.filtros,
                            page: this.pagination.current_page
                        });

                        // Remove parâmetros vazios
                        for (let [key, value] of params.entries()) {
                            if (!value) params.delete(key);
                        }

                        // console.log('Buscando servidores com params:', params.toString());

                        const response = await fetch(
                            `/api/servidores-nomeados?${params.toString()}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                        if (!response.ok) throw new Error('Erro ao buscar servidores');

                        const data = await response.json();
                        // console.log('Dados recebidos da API:', data);

                        // Processar dados conforme a estrutura do controller
                        this.processarDadosAPI(data);

                        // Atualizar URL sem recarregar a página
                        const novaURL =
                            `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
                        window.history.replaceState({}, '', novaURL);

                    } catch (error) {
                        console.error('Erro:', error);
                        this.mostrarErro('Erro ao carregar servidores');
                    } finally {
                        this.carregando = false;
                    }
                },

                processarDadosAPI(data) {
                    // Estrutura do seu controller
                    if (data.servidores && Array.isArray(data.servidores)) {
                        this.servidores = data.servidores.map((servidor, index) => this
                            .formatarServidor(servidor, index));

                        // Usar a paginação do seu controller
                        if (data.pagination) {
                            this.pagination = {
                                current_page: data.pagination.current_page || 1,
                                last_page: data.pagination.last_page || 1,
                                per_page: data.pagination.per_page || 15,
                                total: data.pagination.total || 0,
                                from: data.pagination.from || 0,
                                to: data.pagination.to || 0
                            };
                        } else {
                            // Fallback se não tiver paginação
                            this.pagination = {
                                current_page: 1,
                                last_page: 1,
                                per_page: this.servidores.length,
                                total: this.servidores.length,
                                from: 1,
                                to: this.servidores.length
                            };
                        }
                    } else {
                        console.warn('Estrutura de dados inesperada:', data);
                        this.servidores = [];
                        this.pagination = {
                            current_page: 1,
                            last_page: 1,
                            per_page: 15,
                            total: 0,
                            from: 0,
                            to: 0
                        };
                    }

                    // Estatísticas
                    this.estatisticas = data.estatisticas || this.estatisticas;

                    // console.log('Servidores processados:', this.servidores.length);
                    // console.log('Paginação:', this.pagination);
                },

                formatarServidor(servidor, index) {
                    // Determinar se tem vínculo ativo baseado na relação vinculoAtual
                    const temVinculoAtivo = servidor.vinculo_atual &&
                        servidor.vinculo_atual.id !== null;

                    return {
                        ...servidor,
                        uniqueId: `servidor-${servidor.id}-${Date.now()}`,
                        iniciais: this.gerarIniciais(servidor.nome),
                        tem_vinculo_ativo: temVinculoAtivo
                    };
                },

                gerarIniciais(nome) {
                    if (!nome) return '??';
                    const partes = nome.trim().split(' ').filter(p => p.length > 0);
                    if (partes.length >= 2) {
                        return (partes[0][0] + partes[1][0]).toUpperCase();
                    }
                    return nome.substring(0, 2).toUpperCase();
                },

                limparFiltros() {
                    this.filtros = {
                        search: '',
                        status: '',
                        departamento: '',
                        sort: 'nome'
                    };
                    this.pagination.current_page = 1;
                    this.buscarServidores();
                },

                ordenarPor(campo) {
                    if (this.filtros.sort === campo) {
                        this.filtros.sort = campo + '_desc';
                    } else if (this.filtros.sort === campo + '_desc') {
                        this.filtros.sort = 'nome';
                    } else {
                        this.filtros.sort = campo;
                    }
                    this.pagination.current_page = 1;
                    this.buscarServidores();
                },

                async mudarPagina(pagina) {
                    if (pagina >= 1 && pagina <= this.pagination.last_page && pagina !== this
                        .pagination.current_page) {
                        this.pagination.current_page = pagina;
                        await this.buscarServidores();
                        // Rolagem suave para o topo
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                },

                paginasVisiveis() {
                    const current = this.pagination.current_page;
                    const last = this.pagination.last_page;

                    if (last <= 1) return [1];
                    if (last <= 7) {
                        return Array.from({
                            length: last
                        }, (_, i) => i + 1);
                    }

                    const pages = [];
                    const delta = 2;

                    // Sempre incluir primeira página
                    pages.push(1);

                    // Páginas ao redor da atual
                    let start = Math.max(2, current - delta);
                    let end = Math.min(last - 1, current + delta);

                    // Adicionar ellipsis no início se necessário
                    if (start > 2) {
                        pages.push('...');
                    }

                    // Adicionar páginas centrais
                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }

                    // Adicionar ellipsis no final se necessário
                    if (end < last - 1) {
                        pages.push('...');
                    }

                    // Sempre incluir última página
                    if (last > 1) {
                        pages.push(last);
                    }

                    return pages;
                },
                urlExoneracao(servidorId) {
                    return '{{ route('servidores.exoneracao.create', ['servidor' => ':id']) }}'
                        .replace(':id', servidorId);
                },
                contadorServidores() {
                    if (this.pagination.total === 0) {
                        return 'Nenhum servidor encontrado';
                    }

                    const from = this.pagination.from || 0;
                    const to = this.pagination.to || 0;
                    const total = this.pagination.total || 0;

                    return `Mostrando ${from} a ${to} de ${total} servidores`;
                },

                mostrarErro(mensagem) {
                    // Você pode substituir por um sistema de toast/notificação
                    const errorDiv = document.createElement('div');
                    errorDiv.className =
                        'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50';
                    errorDiv.textContent = mensagem;
                    document.body.appendChild(errorDiv);

                    setTimeout(() => {
                        document.body.removeChild(errorDiv);
                    }, 5000);
                }
            }));
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Melhorias para mobile */
        @media (max-width: 640px) {
            .sortable {
                padding: 0.5rem 0.75rem;
            }

            /* Paginação mobile */
            .flex.space-x-1>button {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        /* Animações suaves */
        .hover\\:bg-gray-50 {
            transition: background-color 0.2s ease-in-out;
        }

        /* Estilo para botões desabilitados */
        button:disabled {
            cursor: not-allowed;
        }
    </style>
</x-app-layout>
