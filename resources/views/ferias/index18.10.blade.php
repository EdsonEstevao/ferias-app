<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Férias
        </h2>
    </x-slot>
    <div class="p-6 mx-auto max-w-7xl" x-data="{
        filtroAberto: true,
        detalhesAberto: null,
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
        calcularDias() {
            if (this.novaInicio) {
                const inicio = new Date(this.novaInicio);

                // Atualiza novaFim automaticamente com base em filhos.dias
                if (this.filhos?.dias) {
                    const fim = new Date(inicio);
                    fim.setDate(fim.getDate() + this.filhos.dias - 1); // -1 para incluir o dia inicial
                    this.novaFim = fim.toISOString().split('T')[0]; // Formata para 'YYYY-MM-DD'
                }

                // Recalcula os dias se novaFim estiver preenchido
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


        adicionarPeriodo() {
            if (!this.novaInicio || !this.novaFim) return;

            const inicio = new Date(this.novaInicio);
            const fim = new Date(this.novaFim);

            if (fim < inicio) {
                alert('⚠️ Período inválido: a data final é anterior à inicial.');
                return;
            }

            const dias = (fim - inicio) / (1000 * 60 * 60 * 24) + 1;

            this.periodos.push({
                inicio: this.novaInicio,
                fim: this.novaFim,
                dias: dias,
                tipo: this.novoTipo
            });

            this.novaInicio = '';
            this.novaFim = '';
            this.novoTipo = 'Férias';
            this.diasCalculados = 0;
        }

    }">

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

        <div class="mb-4 bg-transparent">
            <div id="mensagem" class="hidden px-4 py-2 mt-4 mb-2 font-semibold text-red-600 bg-red-200 rounded-lg">
            </div>

        </div>


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
                <h3 class="mb-4 text-xl font-bold text-gray-800">🗓️ Ano: {{ $registro->ano_exercicio }}</h3>

                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $registro->servidor->nome }} - Matrícula:
                            {{ $registro->servidor->matricula }}</h3>
                        <p class="text-sm text-gray-600">Ano: {{ $registro->ano_exercicio }}</p>
                        <p class="text-sm text-gray-600">Situação: {{ $registro->situacao }}</p>
                    </div>
                    <a href="{{ route('ferias.pdf', $registro->servidor->id) }}" target="_blank"
                        class="text-indigo-600 hover:underline">
                        🖨️ Gerar PDF
                    </a>
                </div>

                @foreach ($registro->periodos->whereNull('periodo_origem_id') as $periodo)
                    <div class="space-y-4" x-data="{
                        aberto: false,
                        periodoInicio: '{{ $periodo->inicio }}',
                        periodoFim: '{{ $periodo->fim }}',

                    }">

                        <!-- Período original -->
                        <div
                            class="flex items-start gap-3 {{ $periodo->tipo == 'Abono' ? 'bg-yellow-100 rounded-lg shadow-xl' : 'text-blue-600' }}">
                            <div class="text-xl text-blue-600">📌</div>
                            <div>
                                <p class="font-semibold text-gray-700">Período Original ({{ $periodo->ordem }}º Período
                                    {{ $periodo->tipo == 'Abono' ? 'de Abono' : 'de Férias' }})
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

                                @if ($periodo->ativo)
                                    <p class="text-sm text-gray-600">
                                        {{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                                        {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                        {{ $periodo->dias }} dias
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500">Situação: {{ $periodo->situacao }}</p>
                                <button @click="aberto = !aberto" class="text-xs text-blue-600 hover:underline">
                                    <span x-text="aberto ? 'Ocultar detalhes' : 'Ver detalhes'"></span>
                                </button>
                            </div>
                            <div class="" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">
                                @if ($periodo->ativo && $periodo->situacao === 'Planejado')
                                    <button
                                        @click="modalAberto = true; periodoSelecionado = {{ $periodo->id }}; filhos = {{ json_encode($periodo) }}"
                                        class="px-3 py-1 mt-3 text-white bg-blue-600 rounded hover:bg-blue-700">
                                        🔁 Remarcar
                                    </button>
                                    @if ($periodo->situacao !== 'Interrompido')
                                        <button @click="periodoId = {{ $periodo->id }}"
                                            class="px-3 py-1 mt-3 text-white bg-red-600 rounded hover:bg-red-700">
                                            ✋ Interromper este período
                                        </button>
                                    @endif

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
                                                :max="periodoFim"
                                                class="block w-full mt-1 border-gray-300 rounded">
                                        </div>
                                        <!--Link do Diof -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700">Titulo</label>
                                            <input type="text" x-model="tituloDiof" name="titulo_diof"
                                                placeholder="Portaria de férias nº 005 de 02 de Junho de 2023."
                                                class="block w-full mt-1 border-gray-300 rounded px-3 py-2">
                                            <label class="block text-sm font-medium text-gray-700">Link do DIOF</label>
                                            <input type="url" x-model="linkDiof" name="link_diof"
                                                placeholder="https://exemplo.com/diof"
                                                class="block w-full mt-1 border-gray-300 rounded px-3 py-2">
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
                            <!-- Detalhes adicionais aqui -->
                            <x-periodo :periodo="$periodo" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{ $ferias->links() }}
        <!-- Modal -->
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
                            class="block w-full mt-1 border-gray-300 rounded px-3 py-2">
                        <label class="block text-sm font-medium text-gray-700">Link do DIOF</label>
                        <input type="url" x-model="linkDiof" name="link_diof"
                            placeholder="https://exemplo.com/diof"
                            class="block w-full mt-1 border-gray-300 rounded px-3 py-2">
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
                        const url =
                            "{{ route('relatorio.ferias.ativas.pdf') }}" +
                            `?ano_exercicio=${ano_exercicio}&ano=${ano}&mes=${mes }`; ///relatorio/ferias-ativas?ano_exercicio=${ano_exercicio}&ano=${ano}&mes=${mes}`;
                        window.open(url, '_blank');
                    } else {

                        const mensagem = document.getElementById('mensagem');
                        // Mostra mensagem suavemente usando o Alpine.js
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
    </script>


</x-app-layout>
