<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-200">
                Gerenciamento de Usuários
            </h2>
            <a href="{{ route('admin.users.create') }}"
                class="px-4 py-2 text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Novo Usuário
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Alertas -->
            @if (session('success'))
                <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-md">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div x-data="userManager()" x-init="init()"
                class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Card de Filtros e Pesquisa -->
                    <div
                        class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input x-model="searchTerm" @input.debounce.300ms="filterUsers()" type="text"
                                        placeholder="Pesquisar por nome, e-mail ou perfil..."
                                        class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <!-- Filtro por Perfil -->
                                <select x-model="roleFilter" @change="filterUsers()"
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Todos os Perfis</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>

                                <!-- Botão Limpar Filtros -->
                                <button @click="clearFilters()"
                                    class="px-4 py-2 text-gray-700 transition duration-200 bg-gray-200 border border-gray-300 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    Limpar
                                </button>
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        {{-- <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600 dark:text-gray-400">
                            <span x-text="`Total: ${totalUsers} usuários`"></span>
                            <span x-text="`Filtrados: ${filteredUsers} usuários`"></span>
                            <span x-show="searchTerm || roleFilter"
                                x-text="`Mostrando: ${displayedUsers} usuários`"></span>
                        </div> --}}
                        <!-- Estatísticas -->
                        <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600 dark:text-gray-400">
                            <span x-text="`Total: ${totalUsers} usuários`"></span>
                            <span x-text="`Filtrados: ${filteredUsers} usuários`"></span>
                            <span x-show="searchTerm || roleFilter"
                                x-text="`Mostrando: ${displayedUsers} usuários`"></span>
                        </div>
                    </div>

                    <!-- Tabela de Usuários -->
                    <div class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                            @click="sortBy('name')">
                                            <div class="flex items-center space-x-1">
                                                <span>Usuário</span>
                                                <svg x-show="sortField === 'name' && sortDirection === 'asc'"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                                <svg x-show="sortField === 'name' && sortDirection === 'desc'"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                            @click="sortBy('email')">
                                            <div class="flex items-center space-x-1">
                                                <span>E-mail</span>
                                                <svg x-show="sortField === 'email' && sortDirection === 'asc'"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                                <svg x-show="sortField === 'email' && sortDirection === 'desc'"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                            Perfis
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer dark:text-gray-300"
                                            @click="sortBy('created_at')">
                                            <div class="flex items-center space-x-1">
                                                <span>Data de Cadastro</span>
                                                <svg x-show="sortField === 'created_at' && sortDirection === 'asc'"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                                <svg x-show="sortField === 'created_at' && sortDirection === 'desc'"
                                                    class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <template x-for="user in paginatedUsers" :key="user.id">
                                        <tr class="transition duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        <div
                                                            class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full dark:bg-blue-900">
                                                            <span
                                                                class="text-sm font-medium text-blue-800 dark:text-blue-200"
                                                                x-text="getInitials(user.name)"></span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                            x-text="user.name"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white"
                                                    x-text="user.email"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="role in user.roles" :key="role.name">
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                                            :class="getRoleBadgeClass(role.name)">
                                                            <span x-text="role.name"></span>
                                                        </span>
                                                    </template>
                                                    <span x-show="user.roles.length === 0"
                                                        class="inline-flex px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-400">
                                                        Sem perfil
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-400"
                                                    x-text="formatDate(user.created_at)"></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <a :href="`/admin/users/${user.id}/edit`"
                                                        class="text-blue-600 transition duration-200 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <button @click="deleteUser(user)"
                                                        class="text-red-600 transition duration-200 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Estado vazio -->
                                    <tr x-show="filteredUsers === 0">
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="text-gray-500 dark:text-gray-400">
                                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-lg font-medium">Nenhum usuário encontrado</p>
                                                <p class="mt-1">Tente ajustar os filtros de pesquisa</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div x-show="totalPages > 1"
                            class="flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-gray-700">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Mostrando <span x-text="startIndex + 1"></span> a <span
                                    x-text="Math.min(endIndex, filteredUsers)"></span> de <span
                                    x-text="filteredUsers"></span> resultados
                            </div>
                            <div class="flex space-x-2">
                                <button @click="previousPage()" :disabled="currentPage === 1"
                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500">
                                    Anterior
                                </button>
                                <div class="flex space-x-1">
                                    <template x-for="page in visiblePages" :key="page">
                                        <button @click="goToPage(page)"
                                            :class="{
                                                'bg-blue-600 text-white': currentPage ===
                                                    page,
                                                'text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500': currentPage !==
                                                    page
                                            }"
                                            class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-md dark:border-gray-500"
                                            x-text="page">
                                        </button>
                                    </template>
                                </div>
                                <button @click="nextPage()" :disabled="currentPage === totalPages"
                                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-500">
                                    Próxima
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function userManager() {
            return {
                // Dados
                users: {{ Js::from($users->items()) }},
                allUsers: {{ Js::from($users->items()) }},
                searchTerm: '',
                roleFilter: '',
                sortField: 'name',
                sortDirection: 'asc',

                // Estatísticas
                totalUsers: {{ $totalUsers }},
                filteredUsers: {{ $filteredUsers }},
                displayedUsers: {{ $displayedUsers }},

                // Paginação
                currentPage: {{ $users->currentPage() }},
                totalPages: {{ $users->lastPage() }},
                perPage: 10,

                init() {
                    // Inicializar com dados do backend
                    this.updateStats();
                },

                // Filtros e Busca
                async filterUsers() {
                    try {
                        const params = new URLSearchParams({
                            search: this.searchTerm,
                            role: this.roleFilter,
                            sort_field: this.sortField,
                            sort_direction: this.sortDirection,
                            page: this.currentPage
                        });

                        const response = await fetch(`/admin/users?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        // Atualizar dados
                        this.users = data.users.data || data.users;
                        this.allUsers = data.users.data || data.users;

                        // Atualizar estatísticas
                        this.totalUsers = data.totalUsers;
                        this.filteredUsers = data.filteredUsers;
                        this.displayedUsers = data.displayedUsers;

                        // Atualizar paginação
                        this.currentPage = data.users.current_page || 1;
                        this.totalPages = data.users.last_page || 1;

                    } catch (error) {
                        console.error('Erro ao filtrar usuários:', error);
                    }
                },

                // Ordenação
                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                    this.filterUsers();
                },

                // Limpar Filtros
                clearFilters() {
                    this.searchTerm = '';
                    this.roleFilter = '';
                    this.sortField = 'name';
                    this.sortDirection = 'asc';
                    this.currentPage = 1;
                    this.filterUsers();
                },

                // Paginação
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        this.filterUsers();
                    }
                },

                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.filterUsers();
                    }
                },

                // Utilitários
                getInitials(name) {
                    return name.split(' ').map(n => n[0]).join('').toUpperCase();
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('pt-BR');
                },

                getRoleBadgeClass(roleName) {
                    const classes = {
                        admin: 'text-red-800 bg-red-100 dark:text-red-300 dark:bg-red-900',
                        servidor: 'text-blue-800 bg-blue-100 dark:text-blue-300 dark:bg-blue-900',
                        gestor: 'text-green-800 bg-green-100 dark:text-green-300 dark:bg-green-900',
                        default: 'text-gray-800 bg-gray-100 dark:text-gray-300 dark:bg-gray-700'
                    };

                    return classes[roleName] || classes.default;
                },

                // Deletar usuário
                async deleteUser(user) {
                    if (confirm(`Tem certeza que deseja excluir o usuário ${user.name}?`)) {
                        try {
                            const response = await fetch(`/admin/users/${user.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (response.ok) {
                                this.filterUsers(); // Recarregar a lista
                            } else {
                                alert('Erro ao excluir usuário');
                            }
                        } catch (error) {
                            console.error('Erro:', error);
                            alert('Erro ao excluir usuário');
                        }
                    }
                },

                // Computed properties para Alpine.js
                get paginatedUsers() {
                    return this.users;
                }
            }
        }
    </script>
</x-app-layout>
