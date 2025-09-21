<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Servidores com férias lançadas</h3>
                            {{-- <p class="text-2xl font-bold text-blue-600">{{ $totalComFerias }}</p> --}}
                            <p class="text-2xl font-bold text-blue-600">150</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Férias interrompidas</h3>
                            {{-- <p class="text-2xl font-bold text-red-600">{{ $totalInterrompidas }}</p> --}}
                            <p class="text-2xl font-bold text-red-600">10</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Remarcações pendentes</h3>
                            {{-- <p class="text-2xl font-bold text-yellow-600">{{ $totalRemarcacoes }}</p> --}}
                            <p class="text-2xl font-bold text-yellow-600">50</p>
                        </div>
                    </div>

                    <!-- gráficos -->
                    <div class="p-6 mb-6 bg-white rounded shadow">
                        <h3 class="mb-4 text-lg font-bold">📊 Distribuição de Férias por Mês</h3>
                        <canvas id="graficoFeriasPorMes" height="100"></canvas>
                    </div>
                    <!-- ulitmos lançamentos -->
                    <table class="w-full text-sm bg-white rounded shadow">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Servidor</th>
                                <th class="px-4 py-2 text-left">Ano</th>
                                <th class="px-4 py-2 text-left">Períodos</th>
                                <th class="px-4 py-2 text-left">Situação</th>
                                {{-- <th class="px-4 py-2 text-left">Períodos</th> --}}
                                <th class="px-4 py-2 text-left">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ultimos as $ferias)
                                {{-- @dd($ferias) --}}
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $ferias->servidor->nome }}</td>
                                    <td class="px-4 py-2">{{ $ferias->ano_exercicio }}</td>
                                    <td class="px-4 py-2">{{ $ferias->periodos->count() }}</td>
                                    <td class="px-4 py-2">
                                        <ul>
                                            @foreach ($ferias->periodos as $periodo)
                                                @if ($periodo->ativo)
                                                    <li>
                                                        {{ $periodo->situacao }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td class="px-4 py-2">
                                        <ul>
                                            @foreach ($ferias->periodos as $periodo)
                                                @if ($periodo->ativo)
                                                    <li>{{ date('d/m/Y', strtotime($periodo->inicio)) }} -
                                                        {{ date('d/m/Y', strtotime($periodo->fim)) }} <button
                                                            @click="modalAberto = true; periodoSelecionado = 1"
                                                            class="text-xs text-green-600 hover:underline">
                                                            🔁 Remarcar
                                                        </button>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td>


                                    </td>
                                </tr>
                            @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- @push('scripts') --}}
    <script>
        const ctx = document.getElementById('graficoFeriasPorMes').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($meses),
                datasets: [{
                    label: 'Períodos de Férias',
                    data: @json($dadosPorMes),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    {{-- @endpush --}}
</x-app-layout>
