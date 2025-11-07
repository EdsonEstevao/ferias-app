<x-app-layout>
    <div class="container px-4 py-8 mx-auto">
        <!-- Cabeçalho -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Servidores Exonerados</h1>
                <p class="text-gray-600">Histórico de exonerações e desligamentos</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('servidores.index') }}"
                    class="inline-flex items-center px-4 py-2 text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
                    ← Voltar para Servidores
                </a>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-4">
            <div class="p-6 bg-white border border-red-200 shadow-sm rounded-xl">
                <div class="text-3xl font-bold text-red-600">{{ $estatisticas['total_exonerados'] }}</div>
                <div class="font-medium text-red-700">Total Exonerados</div>
            </div>
            <div class="p-6 bg-white border border-orange-200 shadow-sm rounded-xl">
                <div class="text-3xl font-bold text-orange-600">{{ $estatisticas['exonerados_mes'] }}</div>
                <div class="font-medium text-orange-700">Este Mês</div>
            </div>
            <div class="p-6 bg-white border border-yellow-200 shadow-sm rounded-xl">
                <div class="text-3xl font-bold text-yellow-600">{{ $estatisticas['exonerados_ano'] }}</div>
                <div class="font-medium text-yellow-700">Este Ano</div>
            </div>
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="text-3xl font-bold text-gray-600">{{ $exonerados->total() }}</div>
                <div class="font-medium text-gray-700">Nesta Lista</div>
            </div>
        </div>
        <!-- Filtros -->
        <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="🔍 Buscar servidor exonerado..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg md:w-80 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                <div class="flex gap-2">
                    <select id="filterMonth"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Todos os meses</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                        @endforeach
                    </select>
                    <select id="filterYear"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Todos os anos</option>
                        @foreach (range(now()->year, now()->year - 5) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de Exonerados -->
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Servidor
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Cargo/Lotação
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Data Saída
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Tempo
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($exonerados as $vinculo)
                            <tr class="transition-colors hover:bg-gray-50">
                                <!-- Servidor -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-semibold text-white rounded-full bg-gradient-to-r from-red-500 to-pink-500">
                                            {{ substr($vinculo->servidor->nome, 0, 1) }}{{ substr(strstr($vinculo->servidor->nome, ' ') ?: '', 1, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $vinculo->servidor->nome }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $vinculo->servidor->matricula }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Cargo/Lotação -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $vinculo->cargo }}</div>
                                    <div class="text-sm text-gray-500">{{ $vinculo->lotacao }}</div>
                                    @if ($vinculo->departamento)
                                        <div class="text-xs text-gray-400">{{ $vinculo->departamento }}</div>
                                    @endif
                                </td>

                                <!-- Data Saída -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($vinculo->data_saida)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($vinculo->data_saida)->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Tempo -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $dias = \Carbon\Carbon::parse($vinculo->data_saida)->diffInDays(now());
                                        $meses = \Carbon\Carbon::parse($vinculo->data_saida)->diffInMonths(now());
                                    @endphp
                                    <div class="text-sm text-gray-900">
                                        @if ($meses > 0)
                                            {{ $meses }} {{ $meses == 1 ? 'mês' : 'meses' }}
                                        @else
                                            {{ $dias }} {{ $dias == 1 ? 'dia' : 'dias' }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">afastado</div>
                                </td>

                                <!-- Ações -->
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <a href="{{ route('servidores.show', $vinculo->servidor) }}"
                                            class="inline-flex items-center px-3 py-1 text-xs text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                            👁️ Ver
                                        </a>
                                        <button onclick="showDetails({{ $vinculo->id }})"
                                            class="inline-flex items-center px-3 py-1 text-xs text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700">
                                            📋 Detalhes
                                        </button>
                                        <form action="{{ route('servidores.exoneracao.restaurar', $vinculo) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit"
                                                onclick="return confirm('Tem certeza que deseja restaurar este servidor?')"
                                                class="inline-flex items-center px-3 py-1 text-xs text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                                🔄 Restaurar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Linha de detalhes (inicialmente oculta) -->
                            <tr id="details-{{ $vinculo->id }}" class="hidden bg-gray-50">
                                <td colspan="5" class="px-6 py-4">
                                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                                        <div>
                                            <h4 class="mb-2 font-medium text-gray-900">📝 Informações da Exoneração</h4>
                                            <div class="space-y-1">
                                                <div><span class="font-medium">Processo:</span>
                                                    {{ $vinculo->processo_disposicao ?? 'Não informado' }}</div>
                                                <div><span class="font-medium">Memorando:</span>
                                                    {{ $vinculo->numero_memorando ?? 'Não informado' }}</div>
                                                <div><span class="font-medium">Ato Normativo:</span>
                                                    {{ $vinculo->ato_normativo ?? 'Não informado' }}</div>
                                                <div><span class="font-medium">Tipo Movimentação:</span>
                                                    {{ $vinculo->tipo_movimentacao ?? 'Exoneração' }}</div>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="mb-2 font-medium text-gray-900">💬 Observações</h4>
                                            <p class="text-gray-700 whitespace-pre-wrap">
                                                {{ $vinculo->observacao ?? 'Nenhuma observação registrada.' }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mb-2 text-lg font-medium text-gray-900">Nenhum servidor exonerado</h3>
                                    <p class="text-gray-600">Não há registros de exoneração no sistema.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if ($exonerados->hasPages())
                <div class="px-6 py-4 bg-white border-t border-gray-200">
                    {{ $exonerados->links() }}
                </div>
            @endif
        </div>
    </div>
    <script>
        function showDetails(vinculoId) {
            const detailsRow = document.getElementById('details-' + vinculoId);
            detailsRow.classList.toggle('hidden');
        }

        // Filtros
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const filterMonth = document.getElementById('filterMonth');
            const filterYear = document.getElementById('filterYear');
            const rows = document.querySelectorAll('tbody tr:not(.hidden)');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const month = filterMonth.value;
                const year = filterYear.value;

                rows.forEach(row => {
                    if (row.id && row.id.startsWith('details-')) return;

                    const servidorNome = row.querySelector('td:first-child .text-sm.font-medium')
                        .textContent.toLowerCase();
                    const cargo = row.querySelector('td:nth-child(2) .text-sm.text-gray-900').textContent
                        .toLowerCase();
                    const dataSaida = row.querySelector('td:nth-child(3) .text-sm.font-medium').textContent;

                    let matchesSearch = servidorNome.includes(searchTerm) || cargo.includes(searchTerm);
                    let matchesDate = true;

                    if (month || year) {
                        const [day, monthStr, yearStr] = dataSaida.split('/');
                        const rowMonth = monthStr;
                        const rowYear = yearStr;

                        if (month && rowMonth !== month.padStart(2, '0')) {
                            matchesDate = false;
                        }
                        if (year && rowYear !== year) {
                            matchesDate = false;
                        }
                    }

                    row.style.display = (matchesSearch && matchesDate) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterTable);
            filterMonth.addEventListener('change', filterTable);
            filterYear.addEventListener('change', filterTable);
        });
    </script>
</x-app-layout>
