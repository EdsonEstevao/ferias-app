<x-app-layout>
    <div class="container px-3 py-4 mx-auto sm:px-4 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Cabeçalho Mobile -->
            <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between sm:mb-6">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Detalhes do Servidor</h1>
                    <p class="text-sm text-gray-600 sm:text-base">Informações completas do servidor</p>
                </div>
                <div class="flex flex-wrap gap-2 sm:gap-2">
                    <a href="{{ route('servidores.edit', $servidor) }}"
                        class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm text-white transition-colors bg-blue-600 rounded-lg sm:flex-none sm:px-4 sm:text-base hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="sm:hidden">Editar</span>
                        <span class="hidden sm:inline">Editar Servidor</span>
                    </a>
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm text-gray-700 transition-colors border border-gray-300 rounded-lg sm:flex-none sm:px-4 sm:text-base hover:bg-gray-50">
                        <span class="sm:mr-1">←</span>
                        <span>Voltar</span>
                    </a>
                </div>
            </div>

            <!-- Card de Informações Pessoais -->
            <div class="p-4 mb-4 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-6 sm:mb-6">
                <h2 class="mb-3 text-lg font-semibold text-gray-900 sm:text-xl sm:mb-4">📋 Dados Pessoais</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="space-y-3 sm:space-y-0">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Nome Completo</label>
                            <p class="text-base font-medium text-gray-900 sm:text-lg">{{ $servidor->nome }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-0">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Matrícula</label>
                            <p class="font-mono text-sm text-gray-900 sm:text-base">{{ $servidor->matricula }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-0">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">CPF</label>
                            <p class="text-sm text-gray-900 sm:text-base">{{ $servidor->cpf ?? 'Não informado' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-0">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Email</label>
                            <p class="text-sm text-gray-900 break-all sm:text-base">
                                {{ $servidor->email ?? 'Não informado' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-0 sm:col-span-2">
                        <div>
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Telefone</label>
                            <p class="text-sm text-gray-900 sm:text-base">{{ $servidor->telefone ?? 'Não informado' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vínculo Atual -->
            @if ($servidor->vinculos()->ativos()->count() > 0)
                <div class="p-4 mb-4 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-6 sm:mb-6">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 sm:text-xl sm:mb-4">🏢 Vínculo Atual</h2>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 sm:gap-4">
                        <div class="p-3 border border-gray-100 rounded-lg sm:p-0 sm:border-none">
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Secretaria</label>
                            <p class="text-sm text-gray-900 sm:text-base">{{ $vinculoAtual->secretaria }}</p>
                        </div>

                        <div class="p-3 border border-gray-100 rounded-lg sm:p-0 sm:border-none">
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Lotação</label>
                            <p class="text-sm text-gray-900 sm:text-base">{{ $vinculoAtual->lotacao }}</p>
                        </div>

                        <div class="p-3 border border-gray-100 rounded-lg sm:p-0 sm:border-none">
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Cargo</label>
                            <p class="text-sm text-gray-900 sm:text-base">{{ $vinculoAtual->cargo }}</p>
                        </div>

                        <div class="p-3 border border-gray-100 rounded-lg sm:p-0 sm:border-none">
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Departamento</label>
                            <p class="text-sm text-gray-900 sm:text-base">
                                {{ $vinculoAtual->departamento ?? 'Não informado' }}</p>
                        </div>

                        <div class="p-3 border border-gray-100 rounded-lg sm:p-0 sm:border-none">
                            <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Sexo</label>
                            <p class="text-sm text-gray-900 sm:text-base">{{ $vinculoAtual->sexo ?? 'Não informado' }}
                            </p>
                        </div>

                        <div
                            class="p-3 border border-gray-100 rounded-lg sm:p-0 sm:border-none sm:col-span-3 lg:col-span-1">
                            <label class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm">Tipo de
                                Servidor</label>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($vinculoAtual->tipo_servidor as $tipo)
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium rounded-full
                                            {{ $tipo == 'interno' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $tipo == 'cedido' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $tipo == 'federal' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $tipo == 'regional' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $tipo == 'disponibilizado' ? 'bg-red-100 text-red-800' : '' }}">
                                        <span class="hidden sm:inline">{{ $tipo }}</span>
                                        <span class="sm:hidden">{{ Str::limit($tipo, 3) }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Alerta Sem Vínculo -->
                <div class="p-4 mb-4 border border-yellow-200 rounded-xl bg-yellow-50 sm:p-6 sm:mb-6">
                    <div class="flex items-start">
                        <svg class="flex-shrink-0 w-5 h-5 mt-0.5 mr-3 text-yellow-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-yellow-800 sm:text-base">Este servidor não possui vínculo ativo.</p>
                            <a href="{{ route('servidores.nomeacao.create', $servidor) }}"
                                class="inline-flex items-center px-3 py-2 mt-2 text-xs text-white transition-colors bg-green-600 rounded-lg sm:px-4 sm:text-sm hover:bg-green-700">
                                <span class="mr-1">➕</span>
                                <span>Cadastrar Nomeação</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Histórico de Vínculos -->
            @if ($servidor->vinculos->count() > 1)
                <div class="p-4 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-6">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 sm:text-xl sm:mb-4">📜 Histórico de Vínculos
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 py-2 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                                        <span class="hidden sm:inline">Secretaria</span>
                                        <span class="sm:hidden">Sec.</span>
                                    </th>
                                    <th
                                        class="px-3 py-2 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                                        <span class="hidden sm:inline">Lotação</span>
                                        <span class="sm:hidden">Lot.</span>
                                    </th>
                                    <th
                                        class="px-3 py-2 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                                        Cargo
                                    </th>
                                    <th
                                        class="px-3 py-2 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                                        Tipo
                                    </th>
                                    <th
                                        class="px-3 py-2 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 sm:py-3">
                                        Data
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($servidor->vinculos->skip(0) as $vinculo)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 text-xs text-gray-900 sm:px-4 sm:py-3 sm:text-sm">
                                            <span class="sm:hidden" title="{{ $vinculo->secretaria }}">
                                                {{ Str::limit($vinculo->secretaria, 15) }}
                                            </span>
                                            <span class="hidden sm:inline">
                                                {{ $vinculo->secretaria }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900 sm:px-4 sm:py-3 sm:text-sm">
                                            <span class="sm:hidden" title="{{ $vinculo->lotacao }}">
                                                {{ Str::limit($vinculo->lotacao, 15) }}
                                            </span>
                                            <span class="hidden sm:inline">
                                                {{ $vinculo->lotacao }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900 sm:px-4 sm:py-3 sm:text-sm">
                                            <span class="sm:hidden" title="{{ $vinculo->cargo }}">
                                                {{ Str::limit($vinculo->cargo, 12) }}
                                            </span>
                                            <span class="hidden sm:inline">
                                                {{ $vinculo->cargo }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-xs sm:px-4 sm:py-3 sm:text-sm">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($vinculo->tipo_servidor as $tipo)
                                                    <span
                                                        class="inline-block px-1 py-0.5 text-xs font-medium text-gray-800 bg-gray-100 rounded-full sm:px-2 sm:py-1">
                                                        <span class="hidden sm:inline">{{ $tipo }}</span>
                                                        <span class="sm:hidden">{{ Str::limit($tipo, 3) }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-500 sm:px-4 sm:py-3 sm:text-sm">
                                            {{ $vinculo->data_movimentacao ? date('d/m/Y', strtotime($vinculo->data_movimentacao)) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Ações Adicionais Mobile -->
            <div class="flex flex-col gap-3 mt-6 sm:hidden">
                <a href="{{ route('servidores.edit', $servidor) }}"
                    class="inline-flex items-center justify-center px-4 py-3 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar Servidor
                </a>

                @if ($servidor->vinculos()->ativos()->count() === 0)
                    <a href="{{ route('servidores.nomeacao.create', $servidor) }}"
                        class="inline-flex items-center justify-center px-4 py-3 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                        <span class="mr-2">➕</span>
                        Cadastrar Nomeação
                    </a>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Melhorias para mobile */
        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            /* Garantir que tabelas sejam scrolláveis */
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }

            /* Melhorar legibilidade em mobile */
            .text-sm {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
        }
    </style>
</x-app-layout>
