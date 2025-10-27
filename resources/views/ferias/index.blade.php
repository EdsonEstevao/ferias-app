<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Férias
        </h2>
    </x-slot>

    <div class="p-4 mx-auto max-w-7xl sm:p-6" x-data="feriasManager()" x-init="init()">

        <h2 class="mb-4 text-xl font-bold sm:text-2xl">📅 Férias dos Servidores</h2>

        <!-- Botão Filtros Mobile -->
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <button @click="filtroAberto = !filtroAberto"
                class="flex items-center px-3 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 sm:px-4">
                <span class="mr-1">🔍</span>
                <span class="text-sm sm:text-base">Filtros</span>
            </button>

            <!-- Gerar PDF Mobile -->
            <form method="GET" action="{{ route('relatorio.ferias.ativas.pdf') }}" target="_blank"
                class="flex flex-col w-full gap-2 mt-2 sm:flex-row sm:items-center sm:w-auto">
                <select name="ano_exercicio" class="w-full px-2 py-1 text-sm border rounded sm:w-auto">
                    <option value="">Todos os exercícios</option>
                    @for ($y = 2020; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>

                <select name="ano" class="w-full px-2 py-1 text-sm border rounded sm:w-auto">
                    <option value="">Todos os anos</option>
                    @for ($y = 2020; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>

                <select name="mes" class="w-full px-2 py-1 text-sm border rounded sm:w-auto">
                    <option value="">Todos os meses</option>
                    @foreach ($meses as $m => $mes)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                            {{ $mes }}
                        </option>
                    @endforeach
                </select>

                <button type="button" onclick="verificarRelatorio()"
                    class="flex items-center justify-center px-3 py-2 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700 sm:px-3">
                    <span class="mr-1">🖨️</span>
                    PDF
                </button>
            </form>
        </div>

        <!-- Mensagens do Sistema -->
        <div class="mb-4 bg-transparent">
            <div id="mensagem"
                class="hidden px-3 py-2 mt-2 mb-2 text-sm font-semibold text-red-600 bg-red-200 rounded-lg">
            </div>
            <div x-show="mensagemSucesso" x-text="mensagemSucesso"
                class="p-3 mt-2 text-sm text-green-700 bg-green-100 rounded-lg" x-transition></div>
            <div x-show="mensagemErro" x-text="mensagemErro" class="p-3 mt-2 text-sm text-red-700 bg-red-100 rounded-lg"
                x-transition></div>
        </div>

        <!-- Filtros - Mobile Optimized -->
        <div x-show="filtroAberto" class="p-3 mt-2 bg-white rounded shadow sm:p-4">
            <form method="GET" action="{{ route('ferias.index') }}"
                class="space-y-3 sm:grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-4 sm:space-y-0">
                {{-- Ano --}}
                <div>
                    <label class="block text-sm font-medium">Ano</label>
                    <select name="ano_exercicio" class="block w-full mt-1 text-sm border-gray-300 rounded">
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
                    <select name="mes" class="block w-full mt-1 text-sm border-gray-300 rounded">
                        <option value="">Todos</option>
                        @foreach ($meses as $m => $mes)
                            <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                {{ $mes }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Busca por nome, CPF ou matrícula --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium">Servidor</label>
                    <input type="text" name="busca" value="{{ request('busca') }}"
                        placeholder="Nome, CPF ou matrícula" class="block w-full mt-1 text-sm border-gray-300 rounded">
                </div>

                {{-- Botão --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <button type="submit" @click="filtroAberto = true"
                        class="w-full px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        {{-- Listagem de ferias - Mobile Optimized --}}
        @foreach ($ferias as $registro)
            <div class="p-4 mb-6 bg-white rounded shadow sm:p-6">
                <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1">
                        <h3 class="mb-1 text-lg font-bold text-gray-800 sm:text-xl">🗓️ Ano:
                            {{ $registro->ano_exercicio }}</h3>
                        <h3 class="text-base font-semibold sm:text-lg">{{ $registro->servidor->nome }}</h3>
                        <p class="text-xs text-gray-600 sm:text-sm">Matrícula: {{ $registro->servidor->matricula }}</p>
                        <p class="text-xs text-gray-600 sm:text-sm">Situação:
                            <span
                                class="{{ $registro->situacao === 'Ativo' ? 'text-green-600' : ($registro->situacao === 'Pendente' ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $registro->situacao }}
                            </span>
                        </p>

                        <!-- Resumo de usufruto Mobile -->
                        @php
                            $totalDias = $registro->periodos->where('ativo', true)->sum('dias');
                            $diasUsufruidos = $registro->periodos
                                ->where('usufruido', true)
                                ->where('ativo', true)
                                ->sum('dias');
                            $diasPendentes = $totalDias - $diasUsufruidos;
                        @endphp

                        <div class="flex flex-wrap gap-1 mt-2 text-xs">
                            <span class="px-2 py-1 font-semibold text-green-800 bg-green-200 rounded shadow-xl">
                                ✅ {{ $diasUsufruidos }}d usuf.
                            </span>
                            <span class="px-2 py-1 font-semibold text-yellow-800 bg-yellow-200 rounded shadow-xl">
                                ⏳ {{ $diasPendentes }}d pend.
                            </span>
                            <span class="px-2 py-1 font-semibold text-blue-800 bg-blue-200 rounded shadow-xl">
                                📊 {{ $totalDias }}d total
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2 sm:mt-0">
                        <!-- Botões existentes -->
                        <a href="{{ route('ferias.pdf', $registro->servidor->id) }}" target="_blank"
                            class="flex items-center px-2 py-1 text-xs text-indigo-600 border border-indigo-600 rounded hover:bg-indigo-50 sm:px-3 sm:text-sm">
                            <span class="mr-1">🖨️</span>
                            PDF
                        </a>
                        <button
                            @click="confirmarExclusaoFerias({{ $registro->id }}, '{{ $registro->servidor->nome }}', {{ $registro->ano_exercicio }})"
                            class="flex items-center px-2 py-1 text-xs text-red-600 border border-red-600 rounded hover:bg-red-50 sm:px-3 sm:text-sm">
                            <span class="mr-1">🗑️</span>
                            Excluir
                        </button>
                    </div>
                </div>

                @foreach ($registro->periodos->whereNull('periodo_origem_id') as $periodo)
                    <div class="mb-3 space-y-3" x-data="{
                        aberto: false,
                        periodoInicio: '{{ date('Y-m-d', strtotime($periodo->inicio)) }}',
                        periodoFim: '{{ date('Y-m-d', strtotime($periodo->fim)) }}',
                    }">

                        <!-- Período original - Mobile Optimized -->
                        <div
                            class="flex flex-col gap-2 p-3 rounded-md sm:flex-row sm:items-start {{ $periodo->tipo == 'Abono' ? 'bg-yellow-100 rounded-lg shadow-xl' : ($periodo->usufruido ? 'bg-green-100 border-l-4 border-green-500' : 'bg-blue-50') }}">
                            <div class="flex items-start gap-2">
                                <div class="text-xl {{ $periodo->usufruido ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $periodo->usufruido ? '✅' : '📌' }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-700 sm:text-base">
                                        @if ($periodo->tipo !== 'Abono')
                                            {{ $periodo->ordem }}º Período
                                        @endif
                                        {{ $periodo->tipo == 'Abono' ? 'Abono' : 'de Férias' }}
                                        @if ($periodo->usufruido)
                                            <span
                                                class="px-1 py-0.5 ml-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">
                                                ✅ USUFRUÍDO
                                            </span>
                                        @else
                                            <span
                                                class="px-1 py-0.5 ml-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">
                                                ⏳ PENDENTE
                                            </span>
                                        @endif
                                    </p>

                                    <!-- link da Portaria -->
                                    @if ($periodo->title)
                                        <p class="text-xs text-gray-600 sm:text-sm">
                                            <a href="{{ $periodo->url }}" target="_blank"
                                                class="text-blue-600 break-all hover:underline">
                                                {{ $periodo->title }}
                                            </a>
                                        </p>
                                    @endif

                                    <p class="text-xs text-gray-600 sm:text-sm">
                                        {{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                                        {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                        {{ $periodo->dias }} dias
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Situação: {{ $periodo->situacao }}
                                        @if ($periodo->usufruido && $periodo->data_usufruto)
                                            • Usufruído em: {{ date('d/m/Y', strtotime($periodo->data_usufruto)) }}
                                        @endif
                                    </p>

                                    <div
                                        class="flex flex-col items-start gap-2 mt-2 sm:flex-col lg:flex-row lg:w-full">

                                        <!-- gridi com 3 colunas  e 1 coluna para mobile -->
                                        <div class="grid justify-between grid-cols-2 gap-2 lg:gap-5 lg:grid-cols-3">


                                            <button @click="aberto = !aberto"
                                                class="w-full px-2 py-2 text-xs text-blue-600 bg-blue-200 rounded shadow-lg hover:bg-blue-500 hover:text-blue-100">
                                                <span x-text="aberto ? 'Ocultar' : 'Detalhes'"></span>
                                            </button>

                                            @if ($periodo->ativo && $periodo->situacao === 'Planejado' && !$periodo->usufruido)
                                                <button
                                                    @click="abrirModalEditarPeriodo({{ $periodo->id }}, '{{ $periodo->inicio }}', '{{ $periodo->fim }}', {{ $periodo->dias }}, '{{ $periodo->justificativa }}')"
                                                    class="w-full px-2 py-2 text-xs text-green-600 bg-green-200 rounded shadow-lg hover:bg-green-500 hover:text-green-100 text-nowrap">
                                                    ✏️ Editar
                                                </button>
                                                @role('super admin')
                                                    <button
                                                        @click="confirmarExclusaoPeriodo({{ $periodo->id }}, '{{ date('d/m/Y', strtotime($periodo->inicio)) }}', '{{ date('d/m/Y', strtotime($periodo->fim)) }}')"
                                                        class="w-full px-2 py-2 text-xs text-red-600 bg-red-200 rounded shadow-lg hover:bg-red-500 hover:text-red-100 text-nowrap">
                                                        🗑️ Excluir
                                                    </button>
                                                @endrole

                                                <button @click="marcarComoUsufruido({{ $periodo->id }})"
                                                    class="w-full px-2 py-2 text-xs text-purple-600 bg-purple-200 rounded shadow-lg flex-inline text-nowrap hover:bg-purple-500 hover:text-purple-100">
                                                    ✅ Usufruído
                                                </button>
                                            @endif
                                            @if ($periodo->situacao !== 'Usufruido' || $periodo->tipo == 'Abono')
                                                @if ($periodo->usufruido)
                                                    <button @click="desmarcarUsufruto({{ $periodo->id }})"
                                                        class="w-full px-2 py-2 text-xs text-orange-600 bg-orange-200 rounded shadow-lg hover:bg-orange-500 hover:text-orange-100 text-nowrap">
                                                        ↩️ Desmarcar
                                                    </button>
                                                @endif
                                            @endif

                                            @if ($periodo->ativo && $periodo->situacao === 'Planejado' && !$periodo->usufruido)
                                                <button
                                                    @click="abrirModalRemarcacao({{ $periodo->id }}, {{ json_encode($periodo) }})"
                                                    class="w-full px-2 py-2 text-xs text-indigo-600 bg-indigo-200 rounded shadow-lg hover:bg-indigo-500 hover:text-indigo-100 text-nowrap">
                                                    🔁 Remarcar
                                                </button>
                                                @if ($periodo->situacao !== 'Interrompido')
                                                    <button @click="periodoId = {{ $periodo->id }}"
                                                        class="w-full px-2 py-2 text-xs rounded shadow-lg text-lime-600 hover:bg-lime-500 hover:text-lime-100 bg-lime-200 text-nowrap">
                                                        ✋ Interromper
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação Mobile -->
                            {{-- <div class="flex flex-wrap gap-1 sm:flex-col sm:gap-2">
                                @if ($periodo->ativo && $periodo->situacao === 'Planejado' && !$periodo->usufruido)
                                    <button
                                        @click="abrirModalRemarcacao({{ $periodo->id }}, {{ json_encode($periodo) }})"
                                        class="flex-1 px-2 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700 sm:px-3 sm:text-sm">
                                        🔁 Remarcar
                                    </button>
                                    @if ($periodo->situacao !== 'Interrompido')
                                        <button @click="periodoId = {{ $periodo->id }}"
                                            class="flex-1 px-2 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700 sm:px-3 sm:text-sm">
                                            ✋ Interromper
                                        </button>
                                    @endif
                                @endif
                            </div> --}}
                        </div>

                        <!-- Formulário de interrupção Mobile -->
                        <div x-show="periodoId === {{ $periodo->id }}"
                            class="p-3 mt-2 space-y-3 transition duration-300 transform rounded bg-gray-50"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transform opacity-100 scale-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data da Interrupção</label>
                                <input type="date" x-model="dataInterrupcao" :min="periodoInicio"
                                    :max="periodoFim" class="block w-full mt-1 text-sm border-gray-300 rounded">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Título</label>
                                <input type="text" x-model="tituloDiof" name="titulo_diof"
                                    placeholder="Portaria de férias..."
                                    class="block w-full px-2 py-1 mt-1 text-sm border-gray-300 rounded">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Link do DIOF</label>
                                <input type="url" x-model="linkDiof" name="link_diof"
                                    placeholder="https://exemplo.com/diof"
                                    class="block w-full px-2 py-1 mt-1 text-sm border-gray-300 rounded">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Motivo</label>
                                <textarea x-model="motivo" rows="2" class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                            </div>

                            <div class="flex gap-2">
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
                                    class="flex-1 px-3 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">
                                    ✅ Confirmar
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
                                    class="flex-1 px-3 py-2 text-sm text-white bg-gray-600 rounded hover:bg-gray-700">
                                    ❌ Cancelar
                                </button>
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

        {{ $ferias->withQueryString()->onEachSide(1)->links('pagination::tailwind') }}

        <!-- Modal Remarcar com Múltiplos Períodos - Mobile Optimized -->
        <div x-show="modalAberto"
            class="fixed inset-0 z-50 flex items-center justify-center p-2 bg-black bg-opacity-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="w-full max-w-4xl p-4 bg-white rounded shadow-lg max-h-[95vh] overflow-y-auto sm:p-6"
                @click.away="modalAberto = false">
                <h3 class="mb-4 text-lg font-bold sm:text-xl">Remarcar Férias - <span x-text="filhos.dias"
                        class="font-semibold text-blue-600"></span> Dias</h3>

                <!-- Resumo de Dias Mobile -->
                <div class="p-3 mb-4 rounded-lg bg-blue-50 sm:p-4">
                    <div class="space-y-2 sm:flex sm:items-center sm:justify-between sm:space-y-0">
                        <div class="text-sm">
                            <span class="font-semibold">Dias totais:</span>
                            <span x-text="filhos.dias" class="ml-1 text-blue-700"></span>
                        </div>
                        <div class="text-sm">
                            <span class="font-semibold">Distribuídos:</span>
                            <span x-text="totalDiasDistribuidos" class="ml-1"
                                :class="totalDiasDistribuidos > filhos.dias ? 'text-red-600' : 'text-green-600'"></span>
                        </div>
                    </div>
                    <div x-show="totalDiasDistribuidos !== filhos.dias"
                        class="mt-2 text-xs text-orange-600 sm:text-sm">
                        ⚠️ Restam <span x-text="filhos.dias - totalDiasDistribuidos"></span> dias
                    </div>
                    <div x-show="totalDiasDistribuidos === filhos.dias"
                        class="mt-2 text-xs text-green-600 sm:text-sm">
                        ✅ Todos os dias distribuídos
                    </div>
                </div>

                <!-- Lista de Períodos Mobile -->
                <div class="space-y-3">
                    <template x-for="(periodo, index) in periodosRemarcacao" :key="index">
                        <div class="p-3 border border-gray-200 rounded-lg sm:p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-700 sm:text-base">Período <span
                                        x-text="index + 1"></span></h4>
                                <button type="button" @click="removerPeriodo(index)"
                                    x-show="periodosRemarcacao.length > 1"
                                    class="text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i> Remover
                                </button>
                            </div>

                            <div class="space-y-3 sm:grid sm:grid-cols-3 sm:gap-3 sm:space-y-0">
                                <!-- Data Início -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 sm:text-sm">Data
                                        Início</label>
                                    <input type="date" x-model="periodo.inicio"
                                        @change="validarDatasPeriodo(index)" :min="obterMinDate(index)"
                                        class="block w-full mt-1 text-sm border-gray-300 rounded">
                                </div>

                                <!-- Data Fim -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 sm:text-sm">Data Fim</label>
                                    <input type="date" x-model="periodo.fim"
                                        @change="validarDatasPeriodo(index); calcularDiasPeriodo(index)"
                                        :min="periodo.inicio || ''"
                                        class="block w-full mt-1 text-sm border-gray-300 rounded">
                                </div>

                                <!-- Dias -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 sm:text-sm">Dias</label>
                                    <input type="number" x-model="periodo.dias" @input="atualizarFimPorDias(index)"
                                        min="1" :max="diasDisponiveis(index)"
                                        class="block w-full mt-1 text-sm border-gray-300 rounded">
                                    <p class="mt-1 text-xs text-gray-500" x-text="'Máx: ' + diasDisponiveis(index)">
                                    </p>
                                </div>
                            </div>

                            <!-- Link do DIOF Mobile -->
                            <div class="mt-3 space-y-3 sm:grid sm:grid-cols-2 sm:gap-3 sm:space-y-0">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 sm:text-sm">Título</label>
                                    <input type="text" x-model="periodo.titulo" placeholder="Ex: 1º período 2024"
                                        class="block w-full px-2 py-1 mt-1 text-sm border-gray-300 rounded">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 sm:text-sm">Link DIOF</label>
                                    <input type="url" x-model="periodo.linkDiof" placeholder="https://..."
                                        class="block w-full px-2 py-1 mt-1 text-sm border-gray-300 rounded">
                                </div>
                            </div>

                            <!-- Observações Mobile -->
                            <div class="mt-3">
                                <label class="block text-xs font-medium text-gray-700 sm:text-sm">Observações</label>
                                <textarea x-model="periodo.observacoes" rows="2" placeholder="Observações..."
                                    class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                            </div>

                            <!-- Resumo do período Mobile -->
                            <div x-show="periodo.dias > 0" class="p-2 mt-2 text-xs rounded bg-gray-50 sm:text-sm">
                                <span class="font-medium" x-text="periodo.dias"></span> dias -
                                <span x-text="periodo.inicio ? formatarData(periodo.inicio) : '...'"></span>
                                a
                                <span x-text="periodo.fim ? formatarData(periodo.fim) : '...'"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Botões Mobile -->
                <div class="flex flex-col gap-2 mt-4 sm:flex-row sm:items-center">
                    <button type="button" @click="adicionarPeriodo()"
                        :disabled="totalDiasDistribuidos >= filhos.dias"
                        :class="totalDiasDistribuidos >= filhos.dias ?
                            'bg-gray-400 cursor-not-allowed' :
                            'bg-blue-600 hover:bg-blue-700'"
                        class="px-4 py-2 text-sm text-white rounded sm:text-base">
                        <i class="fas fa-plus"></i> Adicionar Período
                    </button>
                </div>

                <!-- Justificativa Geral Mobile -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Justificativa</label>
                    <textarea x-model="justificativaGeral" rows="3" placeholder="Justificativa para a remarcação..."
                        class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                </div>

                <!-- Botões de Ação Mobile -->
                <div class="flex flex-col gap-2 mt-4 sm:flex-row sm:justify-end sm:gap-2">
                    <button @click="fecharModalRemarcacao()"
                        class="px-4 py-2 text-sm bg-gray-300 rounded hover:bg-gray-400 sm:text-base">Cancelar</button>

                    <button @click="confirmarRemarcacaoMultiplosPeriodos()" :disabled="!podeConfirmarRemarcacao()"
                        :class="podeConfirmarRemarcacao() ?
                            'bg-green-600 hover:bg-green-700' :
                            'bg-gray-400 cursor-not-allowed'"
                        class="px-4 py-2 text-sm text-white rounded sm:text-base">
                        ✅ Confirmar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Editar Período Mobile -->
        <div x-show="modalEditarAberto"
            class="fixed inset-0 z-50 flex items-center justify-center p-2 bg-black bg-opacity-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="w-full max-w-md p-4 bg-white rounded shadow-lg sm:p-6" @click.away="fecharModalEditar">
                <h3 class="mb-4 text-lg font-bold">Editar Período</h3>

                <form @submit.prevent="salvarPeriodo()" class="space-y-4">
                    <input type="hidden" x-model="periodoEditando.id">

                    <div class="space-y-3 sm:grid sm:grid-cols-2 sm:gap-3 sm:space-y-0">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data Início</label>
                            <input type="date" x-model="periodoEditando.inicio" name="edit-inicio"
                                @change="calcularDiasEdicao" class="block w-full mt-1 border-gray-300 rounded"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data Fim</label>
                            <input type="date" x-model="periodoEditando.fim" name="edit-fim"
                                @change="calcularDiasEdicao" class="block w-full mt-1 border-gray-300 rounded"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dias</label>
                        <input type="number" x-model="periodoEditando.dias"
                            class="block w-full mt-1 border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Observação</label>
                        <textarea x-model="periodoEditando.justificativa" rows="3"
                            class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                    </div>

                    <div class="flex flex-col gap-2 mt-4 sm:flex-row sm:justify-end sm:gap-2">
                        <button type="button" @click="fecharModalEditar"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Confirmação Exclusão Mobile -->
        <div x-show="modalConfirmacaoAberto" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-2 transition-all duration-300 bg-black bg-opacity-50">

            <div class="w-full max-w-md p-4 bg-white rounded shadow-lg sm:p-6">
                <h3 class="mb-4 text-lg font-bold text-red-600">Confirmar Exclusão</h3>
                <p class="mb-4 text-sm" x-text="mensagemConfirmacao"></p>

                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end sm:gap-2">
                    <button @click="fecharModalConfirmacao"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                    <button @click="confirmarAcaoExclusao"
                        class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // function verificarRelatorio() {
        //     const ano_exercicio = document.getElementById('ano_exercicio').value;
        //     const ano = document.getElementById('ano').value;
        //     const mes = document.getElementById('mes').value;

        //     fetch(`/verificar-ferias?ano_exercicio=${ano_exercicio}&ano=${ano}&mes=${mes}`)
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.tem_dados) {
        //                 const url = "{{ route('relatorio.ferias.ativas.pdf') }}" +
        //                     `?ano_exercicio=${ano_exercicio}&ano=${ano}&mes=${mes}`;
        //                 window.open(url, '_blank');
        //             } else {
        //                 const mensagem = document.getElementById('mensagem');
        //                 mensagem.innerText = 'Nenhum dado encontrado para os filtros selecionados.';
        //                 mensagem.style.opacity = 1;
        //                 mensagem.style.transition = 'opacity 0.5s ease-in-out';
        //                 mensagem.classList.remove('hidden');

        //                 setTimeout(() => {
        //                     mensagem.style.transition = 'opacity 1s';
        //                     mensagem.style.opacity = 0;
        //                 }, 5000);
        //             }
        //         })
        //         .catch(() => {
        //             document.getElementById('mensagem').innerText = 'Erro ao verificar os dados.';
        //         });
        // }
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
                modalAberto: false,
                periodoSelecionado: null,
                // Novos estados para múltiplos períodos
                periodosRemarcacao: [],
                justificativaGeral: '',
                totalDiasDistribuidos: 0,
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

                // Método para abrir o modal com múltiplos períodos
                abrirModalRemarcacao(periodoId, periodoData) {
                    this.modalAberto = true;
                    this.periodoSelecionado = periodoId;
                    this.filhos = periodoData;

                    // Inicializar com um período vazio
                    this.periodosRemarcacao = [{
                        inicio: '',
                        fim: '',
                        dias: 0,
                        titulo: '',
                        linkDiof: '',
                        observacoes: ''
                    }];

                    this.justificativaGeral = '';
                    this.totalDiasDistribuidos = 0;
                },

                // Adicionar novo período
                adicionarPeriodo() {
                    if (this.totalDiasDistribuidos >= this.filhos.dias) {
                        alert('Todos os dias já foram distribuídos!');
                        return;
                    }

                    this.periodosRemarcacao.push({
                        inicio: '',
                        fim: '',
                        dias: 0,
                        titulo: '',
                        linkDiof: '',
                        observacoes: ''
                    });
                },

                // Remover período
                removerPeriodo(index) {
                    if (this.periodosRemarcacao.length > 1) {
                        const diasRemovidos = this.periodosRemarcacao[index].dias || 0;
                        this.periodosRemarcacao.splice(index, 1);
                        this.totalDiasDistribuidos -= diasRemovidos;
                    }
                },

                // Calcular dias de um período específico
                calcularDiasPeriodo(index) {
                    const periodo = this.periodosRemarcacao[index];
                    if (periodo.inicio && periodo.fim) {
                        const inicio = new Date(periodo.inicio);
                        const fim = new Date(periodo.fim);

                        if (fim >= inicio) {
                            const diff = Math.floor((fim - inicio) / (1000 * 60 * 60 * 24)) + 1;
                            const diasAnteriores = periodo.dias || 0;

                            periodo.dias = diff;
                            this.totalDiasDistribuidos += (diff - diasAnteriores);
                        } else {
                            periodo.dias = 0;
                            alert('⚠️ A data final não pode ser anterior à data inicial.');
                        }
                    }
                },

                // Atualizar data fim baseado nos dias
                atualizarFimPorDias(index) {
                    const periodo = this.periodosRemarcacao[index];
                    if (periodo.inicio && periodo.dias > 0) {
                        const inicio = new Date(periodo.inicio);
                        const fim = new Date(inicio);
                        fim.setDate(fim.getDate() + periodo.dias - 1);
                        periodo.fim = fim.toISOString().split('T')[0];

                        this.calcularTotalDiasDistribuidos();
                    }
                },

                // Calcular total de dias distribuídos
                calcularTotalDiasDistribuidos() {
                    this.totalDiasDistribuidos = this.periodosRemarcacao.reduce((total, periodo) => {
                        return total + (parseInt(periodo.dias) || 0);
                    }, 0);
                },

                // Validar datas do período
                validarDatasPeriodo(index) {
                    const periodo = this.periodosRemarcacao[index];
                    if (periodo.inicio && periodo.fim) {
                        const inicio = new Date(periodo.inicio);
                        const fim = new Date(periodo.fim);

                        if (fim < inicio) {
                            alert('⚠️ A data final não pode ser anterior à data inicial.');
                            periodo.fim = '';
                            periodo.dias = 0;
                        }
                    }
                },

                // Obter data mínima para um período
                obterMinDate(index) {
                    if (index === 0) return '';

                    // Para períodos subsequentes, a data início deve ser depois do fim do período anterior
                    const periodoAnterior = this.periodosRemarcacao[index - 1];
                    if (periodoAnterior && periodoAnterior.fim) {
                        const minDate = new Date(periodoAnterior.fim);
                        minDate.setDate(minDate.getDate() + 1);
                        return minDate.toISOString().split('T')[0];
                    }
                    return '';
                },

                // Dias disponíveis para um período
                diasDisponiveis(index) {
                    const diasUsados = this.periodosRemarcacao.reduce((total, periodo, i) => {
                        return i !== index ? total + (parseInt(periodo.dias) || 0) : total;
                    }, 0);

                    return Math.max(0, this.filhos.dias - diasUsados);
                },

                // Formatar data para exibição
                formatarData(dataString) {
                    if (!dataString) return '';
                    const data = new Date(dataString + 'T00:00:00');
                    return data.toLocaleDateString('pt-BR');
                },

                // Fechar modal de remarcação
                fecharModalRemarcacao() {
                    this.modalAberto = false;
                    this.periodosRemarcacao = [];
                    this.justificativaGeral = '';
                    this.totalDiasDistribuidos = 0;
                },

                // Verificar se pode confirmar a remarcação
                podeConfirmarRemarcacao() {
                    return this.totalDiasDistribuidos === this.filhos.dias &&
                        this.periodosRemarcacao.every(p => p.inicio && p.fim && p.dias > 0) &&
                        this.justificativaGeral.trim() !== '';
                },

                // Confirmar remarcação com múltiplos períodos
                async confirmarRemarcacaoMultiplosPeriodos() {
                    if (!this.podeConfirmarRemarcacao()) {
                        alert('Preencha todos os períodos corretamente e a justificativa!');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('ferias.remarcar.multiplus') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                periodo_id: this.periodoSelecionado,
                                periodos: this.periodosRemarcacao,
                                justificativa: this.justificativaGeral
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.mostrarMensagemSucesso(data.message ||
                                'Períodos remarcados com sucesso!');
                            this.fecharModalRemarcacao();
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            throw new Error(data.message || 'Erro ao remarcar períodos');
                        }
                    } catch (error) {
                        this.mostrarMensagemErro(error.message);
                        console.error('Erro:', error);
                    }
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

                // Métodos existentes
                calcularDiasEdicao() {

                    if (document.querySelector('input[name="edit-inicio"]').value === '') {

                        document.querySelector('input[name="edit-fim"]').value = '';
                        document.querySelector('input[name="edit-inicio"]').focus();
                        alert('⚠️ A data inicial nao pode ser vazia');
                    }

                    const inicio = new Date(this.periodoEditando.inicio);
                    const fim = new Date(inicio);
                    fim.setDate(fim.getDate() + this.periodoEditando.dias -
                        1);
                    this.periodoEditando.fim = fim.toISOString().split('T')[0];


                    console.log('fim', this.periodoEditando.fim);
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
                    document.querySelector('input[name="edit-inicio"]').value = dataInicio;

                    document.querySelector('input[name="edit-fim"]').value = dataFim;
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
                        const response = await fetch(
                            `/api/periodos-ferias/${periodoId}/usufruir`, {
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
                    if (!confirm(
                            'Tem certeza que deseja desmarcar o usufruto deste período?')) {
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
