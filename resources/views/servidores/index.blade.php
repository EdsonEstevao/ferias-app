<x-app-layout>
    <div x-data="filtroServidores()" x-init="carregar()" class="min-h-screen py-8 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Cabeçalho -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h1 class="flex items-center text-2xl font-bold text-gray-900">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            Gestão de Servidores
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">Gerencie todos os servidores do sistema</p>
                    </div>

                    <!-- Botão de adicionar -->
                    <a href="{{ route('servidores.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Novo Servidor
                    </a>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
                <!-- Barra de Busca e Filtros -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <!-- Campo de busca com botão de limpar -->
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" x-model="busca" @input="pesquisar()"
                                placeholder="Buscar por nome, CPF ou matrícula..."
                                class="block w-full pl-10 pr-12 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">

                            <!-- Botão de limpar busca -->
                            <button x-show="busca" @click="limparBusca()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Contador de resultados -->
                        <div class="text-sm text-gray-500" x-text="contadorServidores"></div>
                    </div>
                </div>

                <!-- Tabela -->
                <div class="overflow-hidden overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">
                                    Servidor
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">
                                    CPF
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">
                                    Matrícula
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">
                                    Cargo
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">
                                    Secretaria
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-700 uppercase">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="servidor in servidores" :key="servidor.id">
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <!-- Nome -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200">
                                                <span class="text-sm font-medium text-blue-700"
                                                    x-text="servidor.nome.split(' ').map(n => n[0]).join('').substring(0, 2)"></span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900" x-text="servidor.nome">
                                                </div>
                                                <div class="text-sm text-gray-500" x-text="servidor.email || '—'"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- CPF -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-sm text-gray-900" x-text="servidor.cpf"></div>
                                    </td>

                                    <!-- Matrícula -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                            x-text="servidor.matricula"></span>
                                    </td>

                                    <!-- Cargo -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="servidor.vinculos[0]?.cargo || '—'">
                                        </div>
                                    </td>

                                    <!-- Secretaria -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"
                                            x-text="servidor.vinculos[0]?.secretaria || '—'"></div>
                                    </td>

                                    <!-- Ações -->
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Botão Visualizar -->
                                            <a :href="`/servidores/${servidor.id}`"
                                                class="inline-flex items-center p-2 text-blue-400 transition-colors duration-200 rounded-lg hover:text-blue-600 hover:bg-blue-50"
                                                title="Ver detalhes do servidor">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <!-- Botão Editar -->
                                            <a :href="`/servidores/${servidor.id}/edit`"
                                                class="inline-flex items-center p-2 text-orange-400 transition-colors duration-200 rounded-lg hover:text-orange-600 hover:bg-orange-50"
                                                title="Editar servidor">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <!-- Botão Excluir -->
                                            <button @click="excluir(servidor.id)"
                                                class="inline-flex items-center p-2 text-red-800 transition-colors duration-200 rounded-lg hover:text-red-600 hover:bg-red-50"
                                                title="Excluir servidor">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>

                                            <!-- Botão Ver Férias -->
                                            <a :href="urlFerias(servidor.id)"
                                                class="inline-flex items-center p-2 text-green-400 transition-colors duration-200 rounded-lg hover:text-green-600 hover:bg-green-50"
                                                title="Lançar férias">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <!-- Botão de Exoneração -->
                                            <a :href="urlExoneracao(servidor.id)"
                                                class="inline-flex items-center p-2 text-red-400 transition-colors duration-200 rounded-lg hover:text-red-600 hover:bg-red-50"
                                                title="Efetivar exoneração">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Estado vazio -->
                    <div x-show="servidores.length === 0 && !carregando" class="py-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">Nenhum servidor encontrado</h3>
                        <p class="max-w-sm mx-auto text-gray-500" x-show="busca">
                            Não encontramos resultados para "<span x-text="busca" class="font-medium"></span>".
                            <br>Tente ajustar os termos da busca.
                        </p>
                        <p class="text-gray-500" x-show="!busca">
                            Não há servidores cadastrados no sistema.
                        </p>
                        <a href="{{ route('servidores.create') }}"
                            class="inline-flex items-center px-4 py-2 mt-4 font-medium text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Adicionar primeiro servidor
                        </a>
                    </div>

                    <!-- Loading -->
                    <div x-show="carregando" class="py-12 text-center">
                        <div class="flex justify-center">
                            <div
                                class="w-8 h-8 border-4 border-blue-600 rounded-full border-t-transparent animate-spin">
                            </div>
                        </div>
                        <p class="mt-2 text-gray-500">Carregando servidores...</p>
                    </div>
                </div>

                <!-- Paginação -->
                <div x-show="servidores.length > 0 && !carregando" class="px-6 py-4 border-t border-gray-100">
                    <div class="flex flex-col items-center justify-between sm:flex-row">
                        <!-- Info de paginação -->
                        <div class="text-sm text-gray-700">
                            Mostrando <span x-text="pagination.from"></span> a
                            <span x-text="pagination.to"></span> de
                            <span x-text="pagination.total"></span> resultados
                        </div>

                        <!-- Controles de paginação -->
                        <div class="flex items-center mt-4 space-x-1 sm:mt-0">
                            <!-- Primeira página -->
                            <button @click="mudarPagina(1)" :disabled="pagination.current_page === 1"
                                :class="pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-md">
                                Primeira
                            </button>

                            <!-- Página anterior -->
                            <button @click="mudarPagina(pagination.current_page - 1)"
                                :disabled="pagination.current_page === 1"
                                :class="pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-md">
                                Anterior
                            </button>

                            <!-- Páginas -->
                            <template x-for="page in paginasVisiveis()" :key="page">
                                <button @click="mudarPagina(page)"
                                    :class="page === pagination.current_page ?
                                        'bg-blue-600 text-white border-blue-600' :
                                        'text-gray-600 bg-white border-gray-300 hover:bg-gray-100'"
                                    class="px-3 py-1 text-sm border rounded-md" x-text="page">
                                </button>
                            </template>

                            <!-- Próxima página -->
                            <button @click="mudarPagina(pagination.current_page + 1)"
                                :disabled="pagination.current_page === pagination.last_page"
                                :class="pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100'"
                                class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-md">
                                Próxima
                            </button>

                            <!-- Última página -->
                            <button @click="mudarPagina(pagination.last_page)"
                                :disabled="pagination.current_page === pagination.last_page"
                                :class="pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100'"
                                class="px-3 py-1 text-sm text-gray-600 bg-white border border-gray-300 rounded-md">
                                Última
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensagem de feedback -->
            <template x-if="mensagem">
                <div class="fixed w-full max-w-sm p-4 transition-all duration-300 transform bg-white border border-green-200 rounded-lg shadow-lg bottom-4 right-4"
                    x-show="mensagem" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800" x-text="mensagem"></p>
                        </div>
                        <button @click="mensagem = ''"
                            class="flex-shrink-0 ml-auto text-green-600 hover:text-green-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Handler global para promises não tratadas
            window.addEventListener('unhandledrejection', event => {
                if (event.reason?.name === 'AbortError') {
                    // Silencia erros de play() interrompido
                    event.preventDefault();
                    console.log('Play interrompido - ignorando erro');
                }
            });
            Alpine.data('filtroServidores', () => ({
                servidores: [],
                busca: '',
                mensagem: '',
                carregando: false,
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 10,
                    total: 0,
                    from: 0,
                    to: 0
                },

                async carregar(pagina = 1) {
                    this.carregando = true;
                    try {
                        // Construir parâmetros da URL
                        const params = new URLSearchParams({
                            page: pagina
                        });

                        // Adicionar busca apenas se não estiver vazia
                        if (this.busca && this.busca.trim() !== '') {
                            params.append('search', this.busca.trim());
                        }

                        const response = await fetch(`/servidores?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Erro na requisição');
                        }

                        const data = await response.json();

                        this.servidores = data.servidores || [];
                        this.pagination = data.pagination || this.pagination;

                    } catch (error) {
                        console.error('Erro ao carregar servidores:', error);
                        this.mensagem = 'Erro ao carregar servidores';
                        setTimeout(() => this.mensagem = '', 5000);
                    } finally {
                        this.carregando = false;
                    }
                },

                // Debounce para busca - corrigido
                pesquisar: Alpine.debounce(function() {
                    this.carregar(1);
                }, 500),

                async mudarPagina(pagina) {
                    if (pagina >= 1 && pagina <= this.pagination.last_page) {
                        await this.carregar(pagina);
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

                    const delta = 2;
                    const range = [];

                    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current +
                            delta); i++) {
                        range.push(i);
                    }

                    if (current - delta > 2) {
                        range.unshift('...');
                    }
                    if (current + delta < last - 1) {
                        range.push('...');
                    }

                    range.unshift(1);
                    if (last > 1) range.push(last);

                    return range;
                },

                contadorServidores() {
                    if (this.pagination.total === 0) {
                        return 'Nenhum servidor encontrado';
                    }
                    return `Mostrando ${this.pagination.from || 0} - ${this.pagination.to || 0} de ${this.pagination.total || 0} servidores`;
                },

                urlFerias(servidorId) {
                    return '{{ route('ferias.create', ['servidor' => ':id']) }}'.replace(':id',
                        servidorId);
                },
                urlExoneracao(servidorId) {
                    return '{{ route('servidores.exoneracao.create', ['servidor' => ':id']) }}'
                        .replace(':id', servidorId);
                },

                async excluir(id) {
                    if (confirm(
                            'Tem certeza que deseja excluir este servidor?\nEsta ação não pode ser desfeita.'
                        )) {
                        try {
                            const response = await fetch(`/servidores/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                this.mensagem = data.message || 'Servidor excluído com sucesso!';
                                setTimeout(() => this.mensagem = '', 5000);
                                // Recarregar a página atual após exclusão
                                await this.carregar(this.pagination.current_page);
                            } else {
                                alert(data.message || 'Erro ao excluir servidor.');
                            }
                        } catch (error) {
                            console.error('Erro:', error);
                            alert('Erro ao excluir servidor. Tente novamente.');
                        }
                    }
                },

                // Limpar busca
                limparBusca() {
                    this.busca = '';
                    this.carregar(1);
                }
            }));
        });
    </script>
</x-app-layout>
