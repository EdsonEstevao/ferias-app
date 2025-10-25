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
                        <!-- Campo de busca -->
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" x-model="busca" placeholder="Buscar por nome, CPF ou matrícula..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>

                        <!-- Contador de resultados -->
                        <div class="text-sm text-gray-500"
                            x-text="`${filtrados().length} de ${servidores.length} servidores`"></div>
                    </div>
                </div>

                <!-- Tabela responsivo -->
                <div class="overflow-hidden overflow-x-auto ">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">
                                    <div class="flex items-center space-x-1">
                                        <span>Servidor</span>
                                    </div>
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
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-right text-gray-700 uppercase">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="s in filtrados()" :key="s.id">
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <!-- Nome -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200">
                                                <span class="text-sm font-medium text-blue-700"
                                                    x-text="s.nome.split(' ').map(n => n[0]).join('').substring(0, 2)"></span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900" x-text="s.nome"></div>
                                                <div class="text-sm text-gray-500" x-text="s.email || '—'"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- CPF -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-sm text-gray-900" x-text="s.cpf"></div>
                                    </td>

                                    <!-- Matrícula -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                            x-text="s.matricula"></span>
                                    </td>

                                    <!-- Cargo -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="s.vinculos[0].cargo || '—'"></div>
                                    </td>

                                    <!-- Secretaria -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900" x-text="s.vinculos[0].secretaria || '—'">
                                        </div>
                                    </td>

                                    <!-- Ações -->
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Botão Editar -->
                                            <a :href="`/servidores/${s.id}/edit`"
                                                class="inline-flex items-center p-2 text-gray-400 transition-colors duration-200 rounded-lg hover:text-blue-600 hover:bg-blue-50"
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
                                            <button @click="excluir(s.id)"
                                                class="inline-flex items-center p-2 text-gray-400 transition-colors duration-200 rounded-lg hover:text-red-600 hover:bg-red-50"
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
                                            <a x-data="{ baseUrl: '{{ route('ferias.create', ['servidor' => ':id']) }}' }" x-bind:href="baseUrl.replace(':id', s.id)"
                                                {{-- <a  h re f ="`/ferias/create/$ {s.id}`" --}}
                                                class="inline-flex items-center p-2 text-gray-400 transition-colors duration-200 rounded-lg hover:text-green-600 hover:bg-green-50"
                                                title="Ver férias">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
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
                    <template x-if="filtrados().length === 0">
                        <div class="py-12 text-center">
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
                    </template>
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
            Alpine.data('filtroServidores', () => ({
                servidores: [],
                busca: '',
                mensagem: '',

                carregar() {
                    this.servidores = {{ Js::from($servidores) }};
                },

                filtrados() {
                    const termo = this.busca.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g,
                        "");
                    return this.servidores.filter(s =>
                        s.nome.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                        .includes(termo) ||
                        s.cpf.includes(termo) ||
                        s.matricula.includes(termo)
                    );
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
                                this.servidores = this.servidores.filter(s => s.id !== id);
                                this.mensagem = data.message;
                                setTimeout(() => this.mensagem = '', 5000);
                            } else {
                                alert(data.message || 'Erro ao excluir servidor.');
                            }
                        } catch (error) {
                            console.error('Erro:', error);
                            alert('Erro ao excluir servidor. Tente novamente.');
                        }
                    }
                }
            }));
        });
    </script>
</x-app-layout>
