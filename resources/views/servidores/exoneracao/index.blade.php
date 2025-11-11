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
        @php
            $meses = [
                1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Março',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Setembro',
                10 => 'Outubro',
                11 => 'Novembro',
                12 => 'Dezembro',
            ];
        @endphp
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
                        @foreach ($meses as $k => $month)
                            <option value="{{ $k }}">
                                {{ $month }}</option>
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
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        @forelse($exonerados as $vinculo)
                            <tr class="transition-colors hover:bg-gray-50" data-row="main"
                                data-nome="{{ strtolower($vinculo->servidor->nome) }}"
                                data-cargo="{{ strtolower($vinculo->cargo) }}"
                                data-matricula="{{ $vinculo->servidor->matricula }}"
                                data-data-saida="{{ \Carbon\Carbon::parse($vinculo->data_saida)->format('d/m/Y') }}"
                                data-mes="{{ \Carbon\Carbon::parse($vinculo->data_saida)->format('m') }}"
                                data-ano="{{ \Carbon\Carbon::parse($vinculo->data_saida)->format('Y') }}">
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
                                        <button onclick="showDetails({{ $vinculo->servidor->id }})"
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
                            <tr id="details-{{ $vinculo->servidor->id }}"
                                class="hidden transition-all duration-300 ease-in-out bg-gray-50" data-row="details">
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
            const tableBody = document.getElementById('tableBody');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const month = filterMonth.value;
                const year = filterYear.value;

                console.log('Filtrando com:', {
                    searchTerm,
                    month,
                    year
                });

                // Seleciona apenas as linhas principais (não as de detalhes)
                const mainRows = tableBody.querySelectorAll('tr[data-row="main"]');

                let visibleCount = 0;

                mainRows.forEach(row => {
                    // Obtém os dados dos atributos data-*
                    const nome = row.getAttribute('data-nome');
                    const cargo = row.getAttribute('data-cargo');
                    const matricula = row.getAttribute('data-matricula');
                    const mes = row.getAttribute('data-mes');
                    const ano = row.getAttribute('data-ano');

                    console.log('Analisando linha:', {
                        nome,
                        cargo,
                        mes,
                        ano
                    });

                    // Filtro de busca
                    let matchesSearch = true;
                    if (searchTerm) {
                        matchesSearch = nome.includes(searchTerm) ||
                            cargo.includes(searchTerm) ||
                            matricula.includes(searchTerm);
                    }

                    // Filtro de data - CORREÇÃO AQUI
                    let matchesDate = true;
                    if (month && month !== '') {
                        console.log('Comparando mês:', mes, 'com filtro:', month);
                        matchesDate = mes === month.padStart(2, '0');
                    }

                    if (year && year !== '') {
                        console.log('Comparando ano:', ano, 'com filtro:', year);
                        matchesDate = matchesDate && (ano === year);
                    }

                    console.log('Resultado:', {
                        matchesSearch,
                        matchesDate
                    });

                    // Mostra ou esconde a linha principal
                    if (matchesSearch && matchesDate) {
                        row.style.display = '';
                        visibleCount++;

                        // Mostra também a linha de detalhes se estiver visível
                        const detailsRow = row.nextElementSibling;
                        if (detailsRow && detailsRow.id.startsWith('details-') && !detailsRow.classList
                            .contains('hidden')) {
                            detailsRow.style.display = '';
                        }
                    } else {
                        row.style.display = 'none';

                        // Esconde também a linha de detalhes
                        const detailsRow = row.nextElementSibling;
                        if (detailsRow && detailsRow.id.startsWith('details-')) {
                            detailsRow.style.display = 'none';
                        }
                    }
                });

                // Atualiza contador de resultados visíveis
                updateResultCount(visibleCount);

                // Mostra mensagem se não houver resultados
                showNoResultsMessage(visibleCount === 0);
            }

            function updateResultCount(count) {
                console.log(`${count} resultados encontrados`);
                // Você pode mostrar isso em algum lugar da UI se quiser
                const countElement = document.getElementById('resultCount');
                if (countElement) {
                    countElement.textContent = `${count} resultado(s) encontrado(s)`;
                }
            }

            function showNoResultsMessage(show) {
                // Remove mensagem anterior se existir
                const existingMessage = tableBody.querySelector('.no-results-message');
                if (existingMessage) {
                    existingMessage.remove();
                }

                if (show) {
                    const messageRow = document.createElement('tr');
                    messageRow.className = 'no-results-message';
                    messageRow.innerHTML = `
                        <td colspan="5" class="px-6 py-8 text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">Nenhum resultado encontrado</h3>
                            <p class="text-gray-600">Tente ajustar os filtros de busca.</p>
                        </td>
                    `;
                    tableBody.appendChild(messageRow);
                }
            }

            // Event listeners
            searchInput.addEventListener('input', filterTable);
            filterMonth.addEventListener('change', filterTable);
            filterYear.addEventListener('change', filterTable);

            // Filtro inicial se houver parâmetros na URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search') || urlParams.has('month') || urlParams.has('year')) {
                if (urlParams.has('search')) {
                    searchInput.value = urlParams.get('search');
                }
                if (urlParams.has('month')) {
                    filterMonth.value = urlParams.get('month');
                }
                if (urlParams.has('year')) {
                    filterYear.value = urlParams.get('year');
                }
                // Aguarda um pouco para garantir que o DOM está totalmente carregado
                setTimeout(filterTable, 100);
            }

            // Adiciona contador de resultados à UI (opcional)
            const resultCounter = document.createElement('div');
            resultCounter.id = 'resultCount';
            resultCounter.className = 'text-sm text-gray-600 mb-4 text-center';
            tableBody.parentNode.insertBefore(resultCounter, tableBody);
        });

        // Função para limpar filtros
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterMonth').value = '';
            document.getElementById('filterYear').value = '';

            const event = new Event('input');
            document.getElementById('searchInput').dispatchEvent(event);
        }
    </script>

    <style>
        .hidden {
            display: none !important;
        }

        /* Transição suave para mostrar/esconder detalhes */
        tr[data-row="details"] {
            transition: all 0.3s ease-in-out;
        }

        /* Botão para limpar filtros (opcional) */
        .clear-filters {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 8px;
        }

        .clear-filters:hover {
            background: #e5e7eb;
        }
    </style>
</x-app-layout>
