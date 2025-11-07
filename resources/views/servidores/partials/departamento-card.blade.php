<div class="overflow-hidden transition-all duration-500 ease-in-out bg-white border border-gray-200 shadow-sm departamento-card rounded-xl"
    x-data="{
        isOpen: $data.expandedDepartamentos.includes('{{ $departamento }}'),
        contentHeight: '0px'
    }" x-init="$nextTick(() => { contentHeight = isOpen ? 'auto' : '0px'; });"
    x-on:click="
        isOpen = !isOpen;
        if (isOpen && !$data.expandedDepartamentos.includes('{{ $departamento }}')) {
            $data.expandedDepartamentos.push('{{ $departamento }}');
        } else if (!isOpen) {
            $data.expandedDepartamentos = $data.expandedDepartamentos.filter(dept => dept !== '{{ $departamento }}');
        }
    "
    x-bind:aria-expanded="isOpen.toString()"
    x-bind:class="{
        'ring-2 ring-blue-500 shadow-lg': isOpen,
        'hover:shadow-md': !isOpen
    }"
    style="animation-delay: {{ $index * 50 }}ms;">

    <!-- Cabeçalho do Departamento Mobile -->
    <div class="p-4 transition-all duration-300 border-b border-gray-200 cursor-pointer departamento-header bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 sm:p-6"
        x-bind:class="{ 'border-blue-200': isOpen }">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="flex-shrink-0">
                    <div
                        class="flex items-center justify-center w-10 h-10 transition-transform duration-300 bg-blue-600 rounded-lg sm:w-12 sm:h-12 hover:scale-105">
                        <svg class="w-5 h-5 text-white transition-transform duration-300 sm:w-6 sm:h-6"
                            x-bind:class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-gray-900 truncate transition-colors duration-300 departamento-nome sm:text-xl"
                        x-bind:class="{ 'text-blue-700': isOpen }" title="{{ $departamento }}">
                        {{ $departamento }}
                    </h3>
                    <div class="flex flex-wrap items-center gap-1 mt-1 sm:gap-2">
                        <span
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 transition-all duration-300 bg-blue-100 rounded-full"
                            x-bind:class="{ 'bg-blue-200': isOpen }">
                            📊 {{ $count }} servidor{{ $count > 1 ? 'es' : '' }}
                        </span>
                        @if ($count == 1)
                            <span
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                ⚠️ Único
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <span class="font-mono text-sm text-gray-600 transition-transform duration-300 toggle-icon sm:text-base"
                    x-bind:class="{ 'rotate-180': isOpen }">▼</span>
            </div>
        </div>
    </div>

    <!-- Conteúdo - Lista de Servidores Mobile -->
    <div x-show="isOpen" x-transition:enter="transition-all duration-500 ease-out"
        x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen"
        x-transition:leave="transition-all duration-300 ease-in" x-transition:leave-start="opacity-100 max-h-screen"
        x-transition:leave-end="opacity-0 max-h-0" class="overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="grid gap-2 overflow-x-auto sm:gap-3">
                @foreach ($servidores as $vinculo)
                    <div x-data="{ itemLoaded: false }" x-init="setTimeout(() => { itemLoaded = true }, {{ $loop->index * 50 }})" x-show="itemLoaded"
                        x-transition:enter="transition-all duration-300 ease-out"
                        x-transition:enter-start="opacity-0 transform -translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        class="flex flex-col gap-3 p-3 transition-all duration-300 border border-gray-200 rounded-lg servidor-item hover:bg-gray-50 group hover:shadow-sm hover:border-gray-300 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:p-4">

                        <div class="flex items-start gap-3 sm:items-center sm:flex-1">
                            <!-- Avatar Mobile -->
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center w-8 h-8 text-xs font-semibold text-white transition-transform duration-300 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 group-hover:scale-110 sm:w-10 sm:h-10 sm:text-sm">
                                    {{ substr($vinculo->nome, 0, 1) }}{{ substr(strstr($vinculo->nome, ' ') ?: '', 1, 1) }}
                                </div>
                            </div>

                            <!-- Informações do Servidor Mobile -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900 truncate transition-colors duration-300 servidor-nome group-hover:text-blue-700 sm:text-base"
                                    title="{{ $vinculo->nome }}">
                                    {{ $vinculo->nome }}
                                </h4>
                                <div class="flex flex-wrap gap-1 mt-1 sm:gap-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 transition-colors duration-300 bg-gray-100 rounded group-hover:bg-gray-200">
                                        🆔 {{ $vinculo->matricula }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 transition-colors duration-300 bg-green-100 rounded group-hover:bg-green-200">
                                        💼 <span class="hidden sm:inline">{{ $vinculo->vinculoAtual->cargo }}</span>
                                        <span class="sm:hidden"
                                            title="{{ $vinculo->vinculoAtual->cargo }}">{{ Str::limit($vinculo->vinculoAtual->cargo, 15) }}</span>
                                    </span>
                                    @forelse($vinculo->vinculoAtual->tipo_servidor ?? [] as $tipo)
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium transition-colors duration-300 rounded group-hover:scale-105
                                            {{ $tipo == 'interno' ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : '' }}
                                            {{ $tipo == 'cedido' ? 'bg-green-100 text-green-800 hover:bg-green-200' : '' }}
                                            {{ $tipo == 'federal' ? 'bg-purple-100 text-purple-800 hover:bg-purple-200' : '' }}
                                            {{ $tipo == 'regional' ? 'bg-orange-100 text-orange-800 hover:bg-orange-200' : '' }}
                                            {{ $tipo == 'disponibilizado' ? 'bg-red-100 text-red-800 hover:bg-red-200' : '' }}">
                                            <span class="hidden sm:inline">{{ $tipo }}</span>
                                            <span class="sm:hidden">{{ Str::limit($tipo, 3) }}</span>
                                        </span>
                                    @empty
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded">
                                            Sem tipo
                                        </span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Ações Mobile -->
                        <div
                            class="flex justify-end gap-2 transition-all duration-300 sm:transform sm:translate-x-4 sm:opacity-0 group-hover:sm:opacity-100 group-hover:sm:translate-x-0">
                            <a href="{{ route('servidores.show', $vinculo->id) }}"
                                class="inline-flex items-center justify-center flex-1 px-2 py-1 text-xs text-white transition-all duration-300 bg-blue-600 rounded-lg sm:flex-none sm:px-3 sm:py-1 hover:bg-blue-700 hover:scale-105 active:scale-95">
                                <span class="sm:mr-1">👁️</span>
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            <a href="{{ route('servidores.edit', $vinculo->id) }}"
                                class="inline-flex items-center justify-center flex-1 px-2 py-1 text-xs text-white transition-all duration-300 bg-green-600 rounded-lg sm:flex-none sm:px-3 sm:py-1 hover:bg-green-700 hover:scale-105 active:scale-95">
                                <span class="sm:mr-1">✏️</span>
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
