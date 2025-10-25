<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Férias
        </h2>
    </x-slot>

    <div class="p-6 mx-auto max-w-7xl" x-data="feriasManager()" x-init="init()">

        <h2 class="mb-6 text-2xl font-bold">📅 Férias dos Servidores</h2>

        <button @click="filtroAberto = !filtroAberto"
            class="px-4 py-2 mb-4 text-white bg-blue-600 rounded hover:bg-blue-700">
            🔍 Filtros
        </button>

        <form method="GET" action="{{ route('relatorio.ferias.ativas.pdf') }}" target="_blank"
            class="flex flex-wrap items-center gap-2">
            <select name="ano_exercicio" class="px-2 py-1 border rounded" id="ano_exercicio">
                <option value="">Todos os exercícios</option>
                @for ($y = 2020; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>

            <select name="ano" class="px-2 py-1 border rounded" id="ano">
                <option value="">Todos os anos de início</option>
                @for ($y = 2020; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>

            <select name="mes" class="px-2 py-1 border rounded" id="mes">
                <option value="">Todos os meses</option>
                @foreach ($meses as $m => $mes)
                    <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                        {{ $mes }}
                    </option>
                @endforeach
            </select>

            <button type="button" onclick="verificarRelatorio()"
                class="px-3 py-1 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                🖨️ Gerar PDF
            </button>
        </form>

        <!-- Mensagens do Sistema -->
        <div class="mb-4 bg-transparent">
            <div id="mensagem" class="hidden px-4 py-2 mt-4 mb-2 font-semibold text-red-600 bg-red-200 rounded-lg">
            </div>
            <div x-show="mensagemSucesso" x-text="mensagemSucesso"
                class="p-4 mt-4 text-green-700 bg-green-100 rounded-lg" x-transition></div>
            <div x-show="mensagemErro" x-text="mensagemErro" class="p-4 mt-4 text-red-700 bg-red-100 rounded-lg"
                x-transition></div>
        </div>

        <!-- Filtros -->
        <div x-show="filtroAberto" class="p-4 mt-4 bg-white rounded shadow">
            <form method="GET" action="{{ route('ferias.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                {{-- Ano --}}
                <div>
                    <label class="block text-sm font-medium">Ano</label>
                    <select name="ano_exercicio" class="block w-full mt-1 border-gray-300 rounded">
                        <option value="">Todos</option>
                        @foreach (range(date('Y') + 1, date('Y') - 4) as $ano)
                            <option value="{{ $ano }}"
                                {{ request('ano_exercicio') == $ano ? 'selected' : '' }}>
                                {{ $ano }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mês --}}
                <div>
                    <label class="block text-sm font-medium">Mês de Início</label>
                    <select name="mes" class="block w-full mt-1 border-gray-300 rounded">
                        <option value="">Todos</option>
                        @foreach ($meses as $m => $mes)
                            <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                {{ $mes }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Busca por nome, CPF ou matrícula --}}
                <div>
                    <label class="block text-sm font-medium">Servidor</label>
                    <input type="text" name="busca" value="{{ request('busca') }}"
                        placeholder="Nome, CPF ou matrícula" class="block w-full mt-1 border-gray-300 rounded">
                </div>

                {{-- Botão --}}
                <div class="flex items-end">
                    <button type="submit" @click="filtroAberto = true"
                        class="w-full px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        {{-- Listagem de ferias --}}
        @foreach ($ferias as $registro)
            <div class="p-6 mb-8 bg-white rounded shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="mb-2 text-xl font-bold text-gray-800">🗓️ Ano: {{ $registro->ano_exercicio }}</h3>
                        <h3 class="text-lg font-semibold">{{ $registro->servidor->nome }} - Matrícula:
                            {{ $registro->servidor->matricula }}</h3>
                        <p class="text-sm text-gray-600">Situação:
                            <span
                                class="{{ $registro->situacao === 'Ativo' ? 'text-green-600' : ($registro->situacao === 'Pendente' ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $registro->situacao }}
                            </span>
                        </p>

                        <!-- NOVO: Resumo de usufruto -->
                        @php
                            $totalDias = $registro->periodos->where('ativo', true)->sum('dias');
                            $diasUsufruidos = $registro->periodos->where('usufruido', true)->sum('dias');
                            $diasPendentes = $totalDias - $diasUsufruidos;
                        @endphp

                        <div class="flex gap-4 mt-2 text-xs">
                            <span class="px-2 py-1 font-semibold text-green-800 bg-green-200 rounded">
                                ✅ {{ $diasUsufruidos }} dias usufruídos
                            </span>
                            <span class="px-2 py-1 font-semibold text-yellow-800 bg-yellow-200 rounded">
                                ⏳ {{ $diasPendentes }} dias pendentes
                            </span>
                            <span class="px-2 py-1 font-semibold text-blue-800 bg-blue-200 rounded">
                                📊 {{ $totalDias }} dias totais
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <!-- Botões existentes -->
                        <a href="{{ route('ferias.pdf', $registro->servidor->id) }}" target="_blank"
                            class="px-3 py-1 text-indigo-600 border border-indigo-600 rounded hover:bg-indigo-50">
                            🖨️ PDF
                        </a>
                        <button
                            @click="confirmarExclusaoFerias({{ $registro->id }}, '{{ $registro->servidor->nome }}', {{ $registro->ano_exercicio }})"
                            class="px-3 py-1 text-red-600 border border-red-600 rounded hover:bg-red-50">
                            🗑️ Excluir
                        </button>
                    </div>
                </div>

                @foreach ($registro->periodos->whereNull('periodo_origem_id') as $periodo)
                    <div class="space-y-4 mb-2" x-data="{
                        aberto: true,
                        periodoInicio: '{{ date('Y-m-d', strtotime($periodo->inicio)) }}', // {{ $periodo->inicio }}',
                        periodoFim: '{{ date('Y-m-d', strtotime($periodo->fim)) }}', // {{ $periodo->fim }}',
                    }">

                        <!-- Período original - ATUALIZADO -->
                        <div
                            class="flex items-start gap-3 px-3 py-2 rounded-md {{ $periodo->tipo == 'Abono' ? 'bg-yellow-100 rounded-lg shadow-xl' : ($periodo->usufruido ? 'bg-green-100 border-l-4 border-green-500' : 'bg-blue-50') }}">
                            <div class="text-xl {{ $periodo->usufruido ? 'text-green-600' : 'text-blue-600' }}">
                                {{ $periodo->usufruido ? '✅' : '📌' }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-700">
                                    @if ($periodo->tipo !== 'Abono')
                                        {{ $periodo->ordem }}º Período
                                    @endif
                                    {{ $periodo->tipo == 'Abono' ? 'Abono' : 'de Férias' }}
                                    @if ($periodo->usufruido)
                                        <span
                                            class="px-2 py-1 ml-2 text-xs font-semibold text-green-800 bg-green-200 rounded-full">
                                            ✅ USUFRUÍDO
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 ml-2 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">
                                            ⏳ PENDENTE
                                        </span>
                                    @endif
                                </p>

                                <!-- link da Portaria -->
                                @if ($periodo->title)
                                    <p class="text-sm text-gray-600">
                                        <a href="{{ $periodo->url }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $periodo->title }}
                                        </a>
                                    </p>
                                @endif

                                {{-- @if ($periodo->ativo) --}}
                                <p class="text-sm text-gray-600">
                                    {{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                                    {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                    {{ $periodo->dias }} dias
                                </p>
                                {{-- @endif --}}

                                <p class="text-xs text-gray-500">
                                    Situação: {{ $periodo->situacao }}
                                    @if ($periodo->usufruido && $periodo->data_usufruto)
                                        • Usufruído em: {{ date('d/m/Y', strtotime($periodo->data_usufruto)) }}
                                    @endif
                                </p>

                                <div class="flex gap-2 mt-2">

                                    <button @click="aberto = !aberto" class="text-xs text-blue-600 hover:underline">
                                        <span x-text="aberto ? 'Ocultar detalhes' : 'Ver detalhes'"></span>
                                    </button>


                                    @if ($periodo->ativo && $periodo->situacao === 'Planejado' && !$periodo->usufruido)
                                        <button
                                            @click="abrirModalEditarPeriodo({{ $periodo->id }}, '{{ $periodo->inicio }}', '{{ $periodo->fim }}', {{ $periodo->dias }}, '{{ $periodo->justificativa }}')"
                                            class="text-xs text-green-600 hover:underline">
                                            ✏️ Editar
                                        </button>
                                        @role('super admin')
                                            <button
                                                @click="confirmarExclusaoPeriodo({{ $periodo->id }}, '{{ date('d/m/Y', strtotime($periodo->inicio)) }}', '{{ date('d/m/Y', strtotime($periodo->fim)) }}')"
                                                class="text-xs text-red-600 hover:underline">
                                                🗑️ Excluir
                                            </button>
                                        @endrole


                                        <!-- NOVO: Botão para marcar como usufruído -->
                                        <button @click="marcarComoUsufruido({{ $periodo->id }})"
                                            class="text-xs text-purple-600 hover:underline">
                                            ✅ Marcar como Usufruído
                                        </button>
                                    @endif
                                    @if ($periodo->situacao !== 'Usufruido' || $periodo->tipo == 'Abono')
                                        @if ($periodo->usufruido)
                                            <button @click="desmarcarUsufruto({{ $periodo->id }})"
                                                class="text-xs text-orange-600 hover:underline">
                                                ↩️ Desmarcar Usufruto
                                            </button>
                                        @endif
                                    @endif
                                </div>

                                <!-- Resto do código permanece igual -->
                                {{-- Formulário de interrupção --}}
                                <div x-show="periodoId === {{ $periodo->id }}"
                                    class="mt-4 space-y-4 transition duration-300 transform"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter="transform opacity-0 scale-95"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transform opacity-100 scale-100"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Data da
                                            Interrupção</label>
                                        <input type="date" x-model="dataInterrupcao" :min="periodoInicio"
                                            :max="periodoFim" class="block w-full mt-1 border-gray-300 rounded">
                                    </div>
                                    <!--Link do Diof -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Titulo</label>
                                        <input type="text" x-model="tituloDiof" name="titulo_diof"
                                            placeholder="Portaria de férias nº 005 de 02 de Junho de 2023."
                                            class="block w-full px-3 py-2 mt-1 border-gray-300 rounded">
                                        <label class="block text-sm font-medium text-gray-700">Link do DIOF</label>
                                        <input type="url" x-model="linkDiof" name="link_diof"
                                            placeholder="https://exemplo.com/diof"
                                            class="block w-full px-3 py-2 mt-1 border-gray-300 rounded">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Motivo</label>
                                        <textarea x-model="motivo" rows="3" class="block w-full mt-1 border-gray-300 rounded"></textarea>
                                    </div>

                                    <button
                                        @click="fetch('{{ route('ferias.interromper') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'Accept': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    periodo_id: periodoId,
                                                    data: dataInterrupcao,
                                                    motivo: motivo,
                                                    linkDiof: linkDiof,
                                                    tituloDiof: tituloDiof,
                                                })
                                            })
                                            .then(res => res.json())
                                            .then(data => {
                                                alert(data.message);
                                                periodoId = null;
                                                dataInterrupcao = '';
                                                motivo = '';
                                                linkDiof = '';
                                                tituloDiof = '';
                                                location.reload();
                                            })
                                            .catch(err => {
                                                console.error(err);
                                                alert('Erro ao interromper período');
                                            })"
                                        class="px-3 py-1 text-white bg-red-600 rounded hover:bg-red-700">
                                        ✅ Confirmar Interrupção
                                    </button>
                                    <button
                                        @click="setTimeout(() => {
                                                periodoId = null;
                                                motivo = '';
                                                dataInterrupcao = '';
                                                tituloDiof = '';
                                                linkDiof = '';
                                                novaInicio = '';
                                                novaFim = '';
                                            }, 10);"
                                        class="px-3 py-1 text-white bg-gray-600 rounded hover:bg-gray-700">
                                        ❌ Cancelar
                                    </button>
                                </div>
                            </div>

                            <div class="" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">

                                @if ($periodo->ativo && $periodo->situacao === 'Planejado' && !$periodo->usufruido)
                                    <button
                                        @click="modalAberto = true; periodoSelecionado = {{ $periodo->id }}; filhos = {{ json_encode($periodo) }}"
                                        class="px-3 py-1 mt-3 text-white bg-blue-600 rounded hover:bg-blue-700">
                                        🔁 Remarcar
                                    </button>
                                    @if ($periodo->situacao !== 'Interrompido')
                                        <button @click="periodoId = {{ $periodo->id }}"
                                            class="px-3 py-1 mt-3 text-white bg-red-600 rounded hover:bg-red-700">
                                            ✋ Interromper
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Filhos: interrupções e remarcações -->
                        <div x-show="aberto" class="mt-2 text-sm text-gray-500"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95">
                            <x-periodo :periodo="$periodo" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{ $ferias->links() }}

        <!-- Modal Remarcar -->
        <div x-show="modalAberto" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg" @click.away="modalAberto = false">

                <h3 class="mb-4 text-lg font-bold">Remarcar Férias de <span x-text="filhos.dias"
                        class="font-semibold text-blue-600"></span>
                    Dias
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nova Data Início</label>
                        <input type="date" x-model="novaInicio" name="nova_inicio" @change="calcularDias"
                            class="block w-full mt-1 border-gray-300 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nova Data Fim</label>
                        <input type="date" x-model="novaFim" name="nova_fim" @change="calcularDias"
                            class="block w-full mt-1 border-gray-300 rounded">
                    </div>
                    <div x-show="diasCalculados > 0" x-transition
                        class="px-3 py-2 text-sm text-gray-700 bg-yellow-100 rounded">
                        <p class="text-yellow-600">
                            Período de <strong><span x-text="diasCalculados"></span></strong> dias
                        </p>
                    </div>
                    <!--Link do Diof -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Titulo</label>
                        <input type="text" x-model="tituloDiof" name="titulo_diof"
                            placeholder="Portaria de férias nº 005 de 02 de Junho de 2023."
                            class="block w-full px-3 py-2 mt-1 border-gray-300 rounded">
                        <label class="block text-sm font-medium text-gray-700">Link do DIOF</label>
                        <input type="url" x-model="linkDiof" name="link_diof"
                            placeholder="https://exemplo.com/diof"
                            class="block w-full px-3 py-2 mt-1 border-gray-300 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Justificativa</label>
                        <textarea x-model="justificativa" rows="3" class="block w-full mt-1 border-gray-300 rounded"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button
                            @click="
                            modalAberto = false;
                            novaInicio = '';
                            novaFim = '';
                            tituloDiof = '';
                            linkDiof = '';
                            justificativa = '';
                            diasCalculados = 0;
                            "
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>

                        <button
                            @click="fetch('{{ route('ferias.remarcar') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                periodo_id: periodoSelecionado,
                                nova_inicio: novaInicio,
                                nova_fim: novaFim,
                                justificativa: justificativa,
                                linkDiof: linkDiof,
                                tituloDiof: tituloDiof
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                            modalAberto = false;
                            novaInicio = '';
                            novaFim = '';
                            tituloDiof = '';
                            linkDiof = '';
                            justificativa = '';
                            location.reload();
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Erro ao remarcar férias');
                        })"
                            class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                            ✅ Confirmar Remarcação
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Período -->
        <div x-show="modalEditarAberto"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg" @click.away="fecharModalEditar">
                <h3 class="mb-4 text-lg font-bold">Editar Período</h3>

                <form @submit.prevent="salvarPeriodo()" class="space-y-4">
                    <input type="hidden" x-model="periodoEditando.id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data Início</label>
                        <input type="date" x-model="periodoEditando.inicio"
                            class="block w-full mt-1 border-gray-ounded" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data Fim</label>
                        <input type="date" x-model="periodoEditando.fim"
                            class="block w-full mt-1 border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dias</label>
                        <input type="number" x-model="periodoEditando.dias"
                            class="block w-full mt-1 border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observação</label>
                        <textarea x-model="periodoEditando.justificativa" rows="3" class="block w-full mt-1 border-gray-300 rounded"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="fecharModalEditar"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Confirmação Exclusão -->
        <div x-show="modalConfirmacaoAberto" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-all duration-300">

            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                <h3 class="mb-4 text-lg font-bold text-red-600">Confirmar Exclusão</h3>
                <p class="mb-4" x-text="mensagemConfirmacao"></p>

                <div class="flex justify-end gap-2">
                    <button @click="fecharModalConfirmacao"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                    <button @click="confirmarAcaoExclusao"
                        class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                        Confirmar Exclusão
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function verificarRelatorio() {
            const ano_exercicio = document.getElementById('ano_exercicio').value;
            const ano = document.getElementById('ano').value;
            const mes = document.getElementById('mes').value;

            fetch(`/verificar-ferias?ano_exercicio=${ano_exercicio}&ano=${ano}&mes=${mes}`)
                .then(response => response.json())
                .then(data => {
                    if (data.tem_dados) {
                        const url = "{{ route('relatorio.ferias.ativas.pdf') }}" +
                            `?ano_exercicio=${ano_exercicio}&ano=${ano}&mes=${mes}`;
                        window.open(url, '_blank');
                    } else {
                        const mensagem = document.getElementById('mensagem');
                        mensagem.innerText = 'Nenhum dado encontrado para os filtros selecionados.';
                        mensagem.style.opacity = 1;
                        mensagem.style.transition = 'opacity 0.5s ease-in-out';
                        mensagem.classList.remove('hidden');

                        setTimeout(() => {
                            mensagem.style.transition = 'opacity 1s';
                            mensagem.style.opacity = 0;
                        }, 5000);
                    }
                })
                .catch(() => {
                    document.getElementById('mensagem').innerText = 'Erro ao verificar os dados.';
                });
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('feriasManager', () => ({
                // Estados existentes
                filtroAberto: true,
                modalAberto: false,
                periodoSelecionado: null,
                novaInicio: '',
                novaFim: '',
                justificativa: '',
                motivo: '',
                dataInterrupcao: '',
                periodoId: null,
                linkDiof: '',
                tituloDiof: '',
                filhos: [],
                diasCalculados: 0,

                // Novos estados para edição/exclusão
                modalEditarAberto: false,
                modalConfirmacaoAberto: false,
                periodoEditando: {
                    id: null,
                    inicio: '',
                    fim: '',
                    dias: 0,
                    justificativa: ''
                },
                itemParaExcluir: null,
                tipoExclusao: '', // 'periodo' ou 'ferias'
                mensagemConfirmacao: '',
                mensagemSucesso: '',
                mensagemErro: '',

                init() {
                    console.log('Ferias Manager inicializado');
                },

                // Métodos existentes
                calcularDias() {
                    if (this.novaInicio) {
                        const inicio = new Date(this.novaInicio);

                        if (this.filhos?.dias) {
                            const fim = new Date(inicio);
                            fim.setDate(fim.getDate() + this.filhos.dias - 1);
                            this.novaFim = fim.toISOString().split('T')[0];
                        }

                        if (this.novaFim) {
                            const fim = new Date(this.novaFim);
                            if (fim >= inicio) {
                                const diff = Math.floor((fim - inicio) / (1000 * 60 * 60 * 24)) + 1;
                                this.diasCalculados = diff;
                            } else {
                                this.diasCalculados = 0;
                                alert('⚠️ A data final não pode ser anterior à data inicial.');
                            }
                        }
                    } else {
                        this.diasCalculados = 0;
                    }
                },

                // Novos métodos para edição
                abrirModalEditarPeriodo(id, dataInicio, dataFim, dias, justificativa) {
                    this.periodoEditando = {
                        id: id,
                        inicio: dataInicio,
                        fim: dataFim,
                        dias: dias,
                        justificativa: justificativa || ''
                    };
                    this.modalEditarAberto = true;
                },

                fecharModalEditar() {
                    this.modalEditarAberto = false;
                    this.periodoEditando = {
                        id: null,
                        inicio: '',
                        fim: '',
                        dias: 0,
                        justificativa: ''
                    };
                },

                async salvarPeriodo() {
                    try {
                        const response = await fetch(
                            `/api/periodos-ferias/${this.periodoEditando.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.periodoEditando)
                            });

                        if (response.ok) {
                            this.mostrarMensagemSucesso('Período atualizado com sucesso!');
                            this.fecharModalEditar();
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            throw new Error('Erro ao atualizar período');
                        }
                    } catch (error) {
                        this.mostrarMensagemErro('Erro ao atualizar período');
                        console.error('Erro:', error);
                    }
                },

                // Métodos para exclusão
                confirmarExclusaoPeriodo(id, dataInicio, dataFim) {
                    this.itemParaExcluir = id;
                    this.tipoExclusao = 'periodo';
                    this.mensagemConfirmacao =
                        `Tem certeza que deseja excluir o período de ${dataInicio} a ${dataFim}?`;
                    this.modalConfirmacaoAberto = true;
                },

                confirmarExclusaoFerias(id, nomeServidor, anoExercicio) {
                    this.itemParaExcluir = id;
                    this.tipoExclusao = 'ferias';
                    this.mensagemConfirmacao =
                        `Tem certeza que deseja excluir todas as férias de ${nomeServidor} para o ano ${anoExercicio}?`;
                    this.modalConfirmacaoAberto = true;
                },

                fecharModalConfirmacao() {
                    this.modalConfirmacaoAberto = false;
                    this.itemParaExcluir = null;
                    this.tipoExclusao = '';
                },

                async confirmarAcaoExclusao() {
                    try {
                        let url, message;

                        if (this.tipoExclusao === 'periodo') {
                            url = `/periodos-ferias/${this.itemParaExcluir}`;
                            message = 'Período excluído com sucesso!';
                        } else {
                            url = `/api/ferias/${this.itemParaExcluir}`;
                            message = 'Férias excluídas com sucesso!';
                        }

                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            console.log(response);
                            this.mostrarMensagemSucesso(message);
                            this.fecharModalConfirmacao();
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            throw new Error('Erro ao excluir');
                        }
                    } catch (error) {
                        this.mostrarMensagemErro('Erro ao excluir');
                        console.error('Erro:', error);
                    }
                },

                // Utilitários
                mostrarMensagemSucesso(mensagem) {
                    this.mensagemSucesso = mensagem;
                    this.mensagemErro = '';
                    setTimeout(() => this.mensagemSucesso = '', 5000);
                },

                mostrarMensagemErro(mensagem) {
                    this.mensagemErro = mensagem;
                    this.mensagemSucesso = '';
                    setTimeout(() => this.mensagemErro = '', 5000);
                },
                // feriasManager
                // Adicionar estes métodos no seu feriasManager()
                async marcarComoUsufruido(periodoId) {
                    try {
                        const response = await fetch(`/api/periodos-ferias/${periodoId}/usufruir`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            this.mostrarMensagemSucesso('Período marcado como usufruído!');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            throw new Error('Erro ao marcar como usufruído');
                        }
                    } catch (error) {
                        this.mostrarMensagemErro('Erro ao marcar como usufruído');
                        console.error('Erro:', error);
                    }
                },

                async desmarcarUsufruto(periodoId) {
                    if (!confirm('Tem certeza que deseja desmarcar o usufruto deste período?')) {
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/api/periodos-ferias/${periodoId}/desusufruir`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                        if (response.ok) {
                            this.mostrarMensagemSucesso('Usufruto desmarcado com sucesso!');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            throw new Error('Erro ao desmarcar usufruto');
                        }
                    } catch (error) {
                        this.mostrarMensagemErro('Erro ao desmarcar usufruto');
                        console.error('Erro:', error);
                    }
                }
            }));
        });
    </script>
</x-app-layout>
