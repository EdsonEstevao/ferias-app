<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Detalhes das Férias
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-4 text-gray-900 sm:p-6 dark:text-gray-100">

                    <x-servidor-info :servidor="$ferias->servidor" :ferias="$ferias" />

                    <!-- Períodos de Férias -->
                    <div class="p-4 bg-white border border-gray-200 rounded-lg sm:p-6">
                        <h3 class="mb-4 text-lg font-bold">🗓️ Histórico de Períodos de Férias</h3>

                        @forelse($periodosOriginais as $periodo)
                            <x-periodo-ferias-card :periodo="$periodo" />
                        @empty
                            <p class="text-gray-500">Nenhum período de férias encontrado.</p>
                        @endforelse
                    </div>

                    <x-resumo-ferias :ferias="$ferias" :periodos-originais="$periodosOriginais" />

                    <!-- Botões de Ação -->
                    <div class="flex flex-col gap-2 mt-6 sm:flex-row">
                        <a href="{{ url()->previous() }}"
                            class="px-4 py-2 text-center text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                            ← Voltar para a Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
