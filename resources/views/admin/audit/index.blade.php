<x-app-layout>




    <div class="container px-4 py-8 mx-auto sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Logs de Auditoria</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Registro de todas as atividades do sistema
            </p>
        </div>

        <!-- Filtros -->
        <div class="p-4 mb-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="flex flex-col gap-4 sm:flex-row">
                <!-- Filtro por Ação -->
                <div class="w-full sm:w-auto">
                    <label for="action_filter" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Ação
                    </label>
                    <select id="action_filter"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todas as ações</option>
                        <option value="created">Criado</option>
                        <option value="updated">Atualizado</option>
                        <option value="deleted">Excluído</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                        <option value="access">Acesso</option>
                    </select>
                </div>

                <!-- Filtro por Usuário -->
                <div class="w-full sm:w-auto">
                    <label for="user_filter" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Usuário
                    </label>
                    <select id="user_filter"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todos os usuários</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Data -->
                <div class="w-full sm:w-auto">
                    <label for="date_filter" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Data
                    </label>
                    <input type="date" id="date_filter"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Botão Limpar -->
                <div class="flex items-end w-full sm:w-auto">
                    <button type="button" id="clear_filters"
                        class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md sm:w-auto hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        Limpar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-full dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total de Logs</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $logs->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-full dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Criações</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['created'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-full dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Atualizações</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['updated'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-full dark:bg-red-900">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Exclusões</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['deleted'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Logs -->
        <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Data/Hora
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Usuário
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Ação
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                Descrição
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                IP
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-300">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($log->user->name ?? 'N/A', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $log->user->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $log->user->email ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $log->getActionColor() }}-100 text-{{ $log->getActionColor() }}-800 dark:bg-{{ $log->getActionColor() }}-900 dark:text-{{ $log->getActionColor() }}-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $log->getActionIconPath() }}"></path>
                                        </svg>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="max-w-xs px-6 py-4 text-sm text-gray-900 truncate dark:text-white">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    <code class="px-2 py-1 text-xs bg-gray-100 rounded dark:bg-gray-900">
                                        {{ $log->ip_address }}
                                    </code>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <a href="{{ route('admin.audit.show', $log) }}"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium leading-4 text-blue-700 transition-colors duration-150 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Detalhes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="mb-1 text-lg font-medium text-gray-900 dark:text-white">Nenhum log
                                            encontrado</p>
                                        <p>Nenhuma atividade foi registrada ainda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if ($logs->hasPages())
                <div class="px-4 py-3 bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:px-6">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <!-- Botão Exportar -->
        <div class="flex justify-end mt-6">
            <button type="button"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Exportar Logs
            </button>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtros
            const actionFilter = document.getElementById('action_filter');
            const userFilter = document.getElementById('user_filter');
            const dateFilter = document.getElementById('date_filter');
            const clearFilters = document.getElementById('clear_filters');

            function applyFilters() {
                const params = new URLSearchParams();

                if (actionFilter.value) params.set('action', actionFilter.value);
                if (userFilter.value) params.set('user_id', userFilter.value);
                if (dateFilter.value) params.set('date', dateFilter.value);

                window.location.href = '{{ route('admin.audit.index') }}?' + params.toString();
            }

            [actionFilter, userFilter, dateFilter].forEach(filter => {
                filter.addEventListener('change', applyFilters);
            });

            clearFilters.addEventListener('click', function() {
                actionFilter.value = '';
                userFilter.value = '';
                dateFilter.value = '';
                window.location.href = '{{ route('admin.audit.index') }}';
            });

            // Aplicar filtros da URL
            const urlParams = new URLSearchParams(window.location.search);
            actionFilter.value = urlParams.get('action') || '';
            userFilter.value = urlParams.get('user_id') || '';
            dateFilter.value = urlParams.get('date') || '';
        });
    </script>
</x-app-layout>
