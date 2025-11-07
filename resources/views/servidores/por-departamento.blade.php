<x-app-layout>
    <div class="container px-3 py-4 mx-auto sm:px-4 sm:py-8">
        <!-- Cabeçalho Mobile -->
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between sm:mb-8">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Servidores por Departamento</h1>
                <p class="text-sm text-gray-600 sm:text-base">Visualização agrupada por departamento</p>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-3">
                <a href="{{ route('servidores.index') }}"
                    class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm text-gray-700 transition-colors border border-gray-300 rounded-lg sm:flex-none sm:px-4 sm:text-base hover:bg-gray-50">
                    ← <span class="ml-1 sm:ml-2">Voltar</span>
                </a>
                <a href="{{ route('servidores.import.form') }}"
                    class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm text-white transition-colors bg-blue-600 rounded-lg sm:flex-none sm:px-4 sm:text-base hover:bg-blue-700">
                    📤 <span class="ml-1 sm:ml-2">Importar</span>
                </a>
            </div>
        </div>

        <!-- Estatísticas Mobile -->
        <div class="grid grid-cols-2 gap-3 mb-6 sm:grid-cols-4 sm:gap-4 sm:mb-8">
            <div class="p-3 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-blue-600 sm:text-2xl">{{ $servidoresPorDepartamento->count() }}</div>
                <div class="text-xs font-medium text-blue-700 sm:text-sm">Departamentos</div>
            </div>
            <div class="p-3 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-green-600 sm:text-2xl">
                    {{ $servidoresPorDepartamento->flatten()->count() }}</div>
                <div class="text-xs font-medium text-green-700 sm:text-sm">Total Servidores</div>
            </div>
            <div class="p-3 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-purple-600 sm:text-2xl">
                    {{ $departamentoMaisPopuloso ? $departamentoMaisPopuloso->count() : 0 }}
                </div>
                <div class="text-xs font-medium text-purple-700 sm:text-sm">Maior Depto</div>
            </div>
            <div class="p-3 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-4">
                <div class="text-xl font-bold text-orange-600 sm:text-2xl">{{ $departamentosComUm }}</div>
                <div class="text-xs font-medium text-orange-700 sm:text-sm">Deptos. 1 serv.</div>
            </div>
        </div>

        <!-- Filtro e Busca Mobile -->
        <div class="p-4 mb-4 bg-white border border-gray-200 shadow-sm rounded-xl sm:p-6 sm:mb-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 w-full">
                    <input type="text" id="searchInput" placeholder="🔍 Buscar departamento ou servidor..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg sm:px-4 sm:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-2">
                    <button id="btnExpandAll"
                        class="inline-flex items-center justify-center flex-1 px-3 py-2 text-xs text-white transition-colors bg-green-600 rounded-lg sm:flex-none sm:px-4 sm:text-sm hover:bg-green-700">
                        <span class="sm:mr-1">📂</span>
                        <span class="hidden sm:inline">Expandir</span>
                        <span class="sm:hidden">Todos</span>
                    </button>
                    <button id="btnCollapseAll"
                        class="inline-flex items-center justify-center flex-1 px-3 py-2 text-xs text-white transition-colors bg-gray-600 rounded-lg sm:flex-none sm:px-4 sm:text-sm hover:bg-gray-700">
                        <span class="sm:mr-1">📁</span>
                        <span class="hidden sm:inline">Recolher</span>
                        <span class="sm:hidden">Todos</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Departamentos -->
        <div class="space-y-4 transition-all duration-500 ease-in-out sm:space-y-6" id="departamentosContainer"
            x-data="{
                expandedDepartamentos: [],
                loaded: false
            }" x-init="expandedDepartamentos = [{{ $servidoresPorDepartamento->keys()->map(fn($key) => "'$key'")->join(', ') }}];
            setTimeout(() => { loaded = true }, 100);" x-on:click.outside="expandedDepartamentos = []"
            x-on:click.self="expandedDepartamentos = []">

            @forelse($servidoresPorDepartamento as $departamento => $servidores)
                <div x-data="{
                    isOpen: $data.expandedDepartamentos.includes('{{ $departamento }}'),
                    itemLoaded: false
                }" x-init="setTimeout(() => { itemLoaded = true }, {{ $loop->index * 100 }});" x-show="itemLoaded"
                    x-transition:enter="transition-all duration-500 ease-out"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition-all duration-300 ease-in"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4" class="transition-all duration-300">
                    @include('servidores.partials.departamento-card', [
                        'departamento' => $departamento ?: 'SEM DEPARTAMENTO',
                        'servidores' => $servidores,
                        'count' => $servidores->count(),
                        'index' => $loop->index,
                    ])
                </div>
            @empty
                <div x-data="{ loaded: false }" x-init="setTimeout(() => { loaded = true }, 200)" x-show="loaded"
                    x-transition:enter="transition-all duration-500 ease-out"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="p-6 text-center bg-white border border-gray-200 shadow-sm rounded-xl sm:p-8">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 sm:w-16 sm:h-16" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <h3 class="mb-2 text-lg font-medium text-gray-900 sm:text-xl">Nenhum servidor encontrado</h3>
                    <p class="text-sm text-gray-600 sm:text-base">Não há servidores ativos para exibir.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const departamentosContainer = document.getElementById('departamentosContainer');
            const btnExpandAll = document.getElementById('btnExpandAll');
            const btnCollapseAll = document.getElementById('btnCollapseAll');

            // Função de busca otimizada para mobile
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                const cards = departamentosContainer.querySelectorAll('[x-data] > .departamento-card');

                cards.forEach(card => {
                    const departamento = card.querySelector('.departamento-nome')?.textContent
                        ?.toLowerCase() || '';
                    const servidores = card.querySelectorAll('.servidor-nome');
                    let hasMatch = departamento.includes(searchTerm);

                    // Verifica se algum servidor corresponde à busca
                    if (!hasMatch && servidores.length > 0) {
                        servidores.forEach(servidor => {
                            if (servidor.textContent.toLowerCase().includes(searchTerm)) {
                                hasMatch = true;
                            }
                        });
                    }

                    // Controlar visibilidade
                    const alpineComponent = card.parentElement;
                    if (hasMatch || searchTerm === '') {
                        alpineComponent.style.display = 'block';
                        setTimeout(() => {
                            if (alpineComponent._x_dataStack && alpineComponent
                                ._x_dataStack[0]) {
                                alpineComponent._x_dataStack[0].itemLoaded = true;
                            }
                        }, 10);
                    } else {
                        alpineComponent.style.display = 'none';
                    }
                });
            });

            // Expandir/Recolher todos
            btnExpandAll.addEventListener('click', function() {
                const alpineComponents = departamentosContainer.querySelectorAll('[x-data]');
                alpineComponents.forEach(component => {
                    const alpineData = component._x_dataStack?.[0];
                    if (alpineData) {
                        alpineData.isOpen = true;
                        if (!alpineData.$data.expandedDepartamentos.includes(alpineData.$data
                                .departamento)) {
                            alpineData.$data.expandedDepartamentos.push(alpineData.$data
                                .departamento);
                        }
                    }
                });
            });

            btnCollapseAll.addEventListener('click', function() {
                const alpineComponents = departamentosContainer.querySelectorAll('[x-data]');
                alpineComponents.forEach(component => {
                    const alpineData = component._x_dataStack?.[0];
                    if (alpineData) {
                        alpineData.isOpen = false;
                        alpineData.$data.expandedDepartamentos = alpineData.$data
                            .expandedDepartamentos.filter(
                                dept => dept !== alpineData.$data.departamento
                            );
                    }
                });
            });

            // Touch-friendly para mobile
            departamentosContainer.addEventListener('click', function(e) {
                const header = e.target.closest('.departamento-header');
                if (header) {
                    const card = header.closest('.departamento-card');
                    const alpineComponent = card.parentElement;
                    const alpineData = alpineComponent._x_dataStack?.[0];

                    if (alpineData) {
                        alpineData.isOpen = !alpineData.isOpen;
                        if (alpineData.isOpen && !alpineData.$data.expandedDepartamentos.includes(alpineData
                                .$data.departamento)) {
                            alpineData.$data.expandedDepartamentos.push(alpineData.$data.departamento);
                        } else if (!alpineData.isOpen) {
                            alpineData.$data.expandedDepartamentos = alpineData.$data.expandedDepartamentos
                                .filter(
                                    dept => dept !== alpineData.$data.departamento
                                );
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
