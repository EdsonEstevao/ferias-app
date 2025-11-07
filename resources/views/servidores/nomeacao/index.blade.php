<x-app-layout>
    <div class="container px-3 py-4 mx-auto sm:px-4 sm:py-8">
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
                {{-- <a href="{{ route('servidores.nomeacao.create') }}" --}}
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
                    <input type="text" id="searchInput" placeholder="Nome ou matrícula..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filtro por Situação -->
                <div>
                    <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Situação</label>
                    <select id="statusFilter"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas</option>
                        <option value="ativo">Ativos</option>
                        <option value="inativo">Inativos</option>
                    </select>
                </div>

                <!-- Filtro por Departamento -->
                <div>
                    <label class="block mb-1 text-xs font-medium text-gray-700 sm:text-sm">Departamento</label>
                    <select id="departamentoFilter"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento }}">{{ $departamento }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botões de Ação -->
                <div class="flex gap-2 sm:items-end">
                    <button id="btnAplicarFiltros"
                        class="flex-1 px-3 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 sm:flex-none">
                        <span class="sm:hidden">🔍</span>
                        <span class="hidden sm:inline">Aplicar</span>
                    </button>
                    <button id="btnLimparFiltros"
                        class="flex-1 px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 sm:flex-none">
                        <span class="sm:hidden">🗑️</span>
                        <span class="hidden sm:inline">Limpar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-2 gap-3 mb-6 sm:grid-cols-4 sm:gap-4 sm:mb-8">
            <div class="p-3 bg-white border border-blue-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-blue-600 sm:text-2xl">{{ $totalServidores }}</div>
                <div class="text-xs font-medium text-blue-700 sm:text-sm">Total</div>
            </div>
            <div class="p-3 bg-white border border-green-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-green-600 sm:text-2xl">{{ $ativos }}</div>
                <div class="text-xs font-medium text-green-700 sm:text-sm">Ativos</div>
            </div>
            <div class="p-3 bg-white border border-orange-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-orange-600 sm:text-2xl">{{ $inativos }}</div>
                <div class="text-xs font-medium text-orange-700 sm:text-sm">Inativos</div>
            </div>
            <div class="p-3 bg-white border border-purple-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-purple-600 sm:text-2xl">{{ $semVinculo }}</div>
                <div class="text-xs font-medium text-purple-700 sm:text-sm">Sem Vínculo</div>
            </div>
        </div>

        <!-- Lista de Servidores -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
            <!-- Cabeçalho da Tabela -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-900 sm:text-xl">
                    📊 Lista de Servidores
                    <span class="text-sm font-normal text-gray-500">({{ $servidores->count() }})</span>
                </h3>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 sm:text-sm">Ordenar:</span>
                    <select id="sortSelect"
                        class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="nome" {{ request()->get('sort') == 'nome' ? 'selected' : '' }}>Nome A-Z
                        </option>
                        <option value="nome_desc" {{ request()->get('sort') == 'nome_desc' ? 'selected' : '' }}>Nome Z-A
                        </option>
                        <option value="matricula" {{ request()->get('sort') == 'matricula' ? 'selected' : '' }}>
                            Matrícula</option>
                        <option value="departamento" {{ request()->get('sort') == 'departamento' ? 'selected' : '' }}>
                            Departamento
                        </option>
                    </select>
                </div>
            </div>

            <!-- Conteúdo -->
            @if ($servidores->count() > 0)
                <!-- Vista Mobile - Cards -->
                <div class="sm:hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach ($servidores as $servidor)
                            <div class="p-4 transition-colors duration-200 hover:bg-gray-50">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 truncate" title="{{ $servidor->nome }}">
                                            {{ $servidor->nome }}
                                        </h4>
                                        <p class="font-mono text-xs text-gray-500">{{ $servidor->matricula }}</p>
                                    </div>
                                    <div class="flex gap-1 ml-2">
                                        @if ($servidor->vinculos()->ativos()->exists())
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                Ativo
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                Inativo
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Informações do Vínculo -->
                                @if ($vinculoAtual = $servidor->vinculoAtual)
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex justify-between">
                                            <span>Cargo:</span>
                                            <span
                                                class="font-medium text-gray-900">{{ Str::limit($vinculoAtual->cargo, 20) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Depto:</span>
                                            <span
                                                class="font-medium text-gray-900">{{ $vinculoAtual->departamento ?? 'N/I' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Secretaria:</span>
                                            <span
                                                class="font-medium text-gray-900">{{ Str::limit($vinculoAtual->secretaria, 15) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-2 text-xs text-center text-yellow-800 bg-yellow-100 rounded">
                                        Sem vínculo ativo
                                    </div>
                                @endif

                                <!-- Ações Mobile -->
                                <div class="flex gap-2 mt-3">
                                    <a href="{{ route('servidores.show', $servidor) }}"
                                        class="flex-1 px-2 py-1 text-xs text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                                        👁️ Ver
                                    </a>
                                    <a href="{{ route('servidores.edit', $servidor) }}"
                                        class="flex-1 px-2 py-1 text-xs text-center text-white bg-green-600 rounded hover:bg-green-700">
                                        ✏️ Editar
                                    </a>
                                    <a href="{{ route('servidores.nomeacao.create', $servidor) }}"
                                        class="flex-1 px-2 py-1 text-xs text-center text-white bg-purple-600 rounded hover:bg-purple-700">
                                        ➕ Vincular
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Vista Desktop - Tabela -->
                <div class="hidden sm:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase cursor-pointer sortable"
                                        data-sort="nome">
                                        Nome
                                    </th>
                                    <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase cursor-pointer sortable"
                                        data-sort="matricula">
                                        Matrícula
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
                                @foreach ($servidores as $servidor)
                                    <tr class="transition-colors duration-200 hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-semibold text-white rounded-full bg-gradient-to-r from-green-500 to-lime-500">
                                                    {{ substr($servidor->nome, 0, 1) }}{{ substr(strstr($servidor->nome, ' ') ?: '', 1, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $servidor->nome }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $servidor->email }}
                                                    </div>
                                                </div>

                                            </div>

                                        </td>
                                        <td class="px-4 py-3 font-mono text-sm text-gray-900">
                                            {{ $servidor->matricula }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $servidor->vinculoAtual->cargo ?? 'N/I' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $servidor->vinculoAtual->departamento ?? 'N/I' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $servidor->vinculoAtual->secretaria ?? 'N/I' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if ($servidor->vinculos()->ativos()->exists())
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                    Ativo
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                    Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('servidores.show', $servidor) }}"
                                                    class="text-blue-600 hover:text-blue-900" title="Ver detalhes">
                                                    👁️
                                                </a>
                                                <a href="{{ route('servidores.edit', $servidor) }}"
                                                    class="text-green-600 hover:text-green-900"
                                                    title="Editar servidor">
                                                    ✏️
                                                </a>
                                                <a href="{{ route('servidores.nomeacao.create', $servidor) }}"
                                                    class="text-purple-600 hover:text-purple-900"
                                                    title="Criar vínculo">
                                                    ➕
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação -->
                @if ($servidores->hasPages())
                    <div class="p-4 border-t border-gray-200 sm:p-6">
                        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
                            <div class="text-sm text-gray-700">
                                Mostrando {{ $servidores->firstItem() }} a {{ $servidores->lastItem() }} de
                                {{ $servidores->total() }} resultados
                            </div>
                            <div class="flex gap-1">
                                {{ $servidores->onEachSide(3)->links('pagination::tailwind') }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Estado Vazio -->
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400 sm:w-16 sm:h-16" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-medium text-gray-900 sm:text-xl">Nenhum servidor encontrado</h3>
                    <p class="mb-4 text-sm text-gray-600 sm:text-base">Não há servidores nomeados com os filtros
                        aplicados.</p>
                    {{-- <a href="{{ route('servidores.nomeacao.create') }}" --}}
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <span class="mr-2">➕</span>
                        Cadastrar Primeira Nomeação
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const departamentoFilter = document.getElementById('departamentoFilter');
            const sortSelect = document.getElementById('sortSelect');
            const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
            const btnLimparFiltros = document.getElementById('btnLimparFiltros');

            // Aplicar filtros
            btnAplicarFiltros.addEventListener('click', function() {
                aplicarFiltros();
            });

            // Limpar filtros
            btnLimparFiltros.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                departamentoFilter.value = '';
                sortSelect.value = 'nome';
                aplicarFiltros();
            });

            // Ordenação
            sortSelect.addEventListener('change', function() {
                aplicarFiltros();
            });

            // Busca em tempo real com debounce
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(aplicarFiltros, 500);
            });

            function aplicarFiltros() {
                const params = new URLSearchParams({
                    search: searchInput.value,
                    status: statusFilter.value,
                    departamento: departamentoFilter.value,
                    sort: sortSelect.value
                });

                window.location.href = `${window.location.pathname}?${params.toString()}`;
            }

            // Melhorar UX mobile
            if (window.innerWidth < 640) {
                // Adicionar swipe para cards mobile
                const cards = document.querySelectorAll('.sm\\:hidden > div > div');
                cards.forEach(card => {
                    card.style.cursor = 'pointer';
                    card.addEventListener('click', function(e) {
                        if (!e.target.closest('a')) {
                            const link = this.querySelector('a[href*="/servidores/"]');
                            if (link) {
                                window.location.href = link.href;
                            }
                        }
                    });
                });
            }
        });
    </script>

    <style>
        /* Melhorias para mobile */
        @media (max-width: 640px) {
            .sortable {
                padding: 0.5rem 0.75rem;
            }

            /* Melhorar toque em mobile */
            .sm\\:hidden>div>div {
                -webkit-tap-highlight-color: transparent;
            }
        }

        /* Animações suaves */
        .hover\\:bg-gray-50 {
            transition: background-color 0.2s ease-in-out;
        }
    </style>
</x-app-layout>
