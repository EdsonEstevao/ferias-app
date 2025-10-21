<x-app-layout>
    <div class="container px-4 py-8 mx-auto sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes do Log</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Informações detalhadas da ação registrada
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
            <!-- Informações Básicas -->
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-medium text-gray-900 dark:text-white">Informações Básicas</h5>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Data/Hora:</span>
                        <span class="text-sm text-right text-gray-900 dark:text-white">
                            {{ $auditLog->created_at->format('d/m/Y H:i:s') }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuário:</span>
                        <span class="text-sm text-right text-gray-900 dark:text-white">
                            {{ $auditLog->user->name ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Ação:</span>
                        <span class="text-sm text-right text-gray-900 dark:text-white">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $auditLog->getActionColor() }}-100 text-{{ $auditLog->getActionColor() }}-800 dark:bg-{{ $auditLog->getActionColor() }}-900 dark:text-{{ $auditLog->getActionColor() }}-300">
                                {{ ucfirst($auditLog->action) }}
                            </span>
                        </span>
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Descrição:</span>
                        <span class="max-w-xs text-sm text-right text-gray-900 dark:text-white">
                            {{ $auditLog->description }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">IP:</span>
                        <span class="text-sm text-right text-gray-900 dark:text-white">
                            <code class="px-2 py-1 text-xs bg-gray-100 rounded dark:bg-gray-900">
                                {{ $auditLog->ip_address }}
                            </code>
                        </span>
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">User Agent:</span>
                        <span class="max-w-xs text-sm text-right text-gray-900 dark:text-white">
                            {{ $auditLog->user_agent }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">URL:</span>
                        <span class="max-w-xs text-sm text-right text-gray-900 break-words dark:text-white">
                            {{ $auditLog->url }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Alterações -->
            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-medium text-gray-900 dark:text-white">Alterações</h5>
                </div>
                <div class="p-6">
                    @if (count($auditLog->changes) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                            Campo
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                            Valor Antigo
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">
                                            Valor Novo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach ($auditLog->changes as $field => $change)
                                        {{-- @foreach ($auditLog->formatted_changes as $field => $change) --}}
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $field }}
                                            </td>
                                            <td
                                                class="max-w-xs px-4 py-3 text-sm text-red-600 break-words dark:text-red-400">
                                                {{-- {{ $change['old'] ?? 'N/A' }} --}}
                                                {{-- {{ isset($change['old']) ? $change['old'] : 'N/A' }} --}}
                                                {{-- {{ \App\Helpers\AuditHelper::formatValue($change['old'] ?? null) }} --}}
                                                @formatValue($change['old'] ?? null)
                                            </td>
                                            <td
                                                class="max-w-xs px-4 py-3 text-sm text-green-600 break-words dark:text-green-400">
                                                {{-- {{ $change['new'] ?? 'N/A' }} --}}
                                                {{-- {{ isset($change['new']) ? $change['new'] : 'N/A' }} --}}
                                                {{-- {{ \App\Helpers\AuditHelper::formatValue($change['new'] ?? null) }} --}}
                                                @formatValue($change['new'] ?? null)
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma alteração registrada.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botão Voltar -->
        <div class="flex justify-start">
            <a href="{{ route('admin.audit.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para a lista
            </a>
        </div>
    </div>
</x-app-layout>
