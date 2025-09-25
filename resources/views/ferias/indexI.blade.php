<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Férias
        </h2>
    </x-slot>
    <div class="p-6 mx-auto max-w-7xl" x-data="{ filtroAberto: false, detalhesAberto: null, modalAberto: false, periodoSelecionado: null, novaInicio: '', novaFim: '', justificativa: '', motivo: '', dataInterrupcao: '', periodoId: null }">

        <h2 class="mb-6 text-2xl font-bold">📅 Férias dos Servidores</h2>

        <button @click="filtroAberto = !filtroAberto"
            class="px-4 py-2 mb-4 text-white bg-blue-600 rounded hover:bg-blue-700">
            🔍 Filtros
        </button>
        <form method="GET" action="{{ route('relatorio.ferias.ativas.pdf') }}" target="_blank"
            class="flex gap-2 items-center flex-wrap">
            <select name="ano_exercicio" class="border rounded px-2 py-1" id="ano_exercicio">
                <option value="">Todos os exercícios</option>
                @for ($y = 2020; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>

            <select name="ano" class="border rounded px-2 py-1" id="ano">
                <option value="">Todos os anos de início</option>
                @for ($y = 2020; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>

            <select name="mes" class="border rounded px-2 py-1" id="mes">
                <option value="">Todos os meses</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>

            <button type="button" onclick="verificarRelatorio()"
                class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                🖨️ Gerar PDF
            </button>

        </form>

        <div class="mb-4 bg-transparent">
            <div id="mensagem" class="mt-4 text-red-600 font-semibold px-4 py-2 bg-red-200 rounded-lg mb-2 hidden">
            </div>

        </div>


        <div x-data="buscaFerias()" x-init="buscar()" class="p-4 mt-4 bg-white rounded shadow">
            <template x-if="mensagem">
                <div class="mb-4 text-red-600 font-semibold" x-text="mensagem" x-show="mensagem" x-transition></div>
            </template>

            <form @submit.prevent="buscar()" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- Ano -->
                <div>
                    <label class="block text-sm font-medium">Ano</label>
                    <select x-model="ano_exercicio" class="block w-full mt-1 border-gray-300 rounded">
                        <option value="">Todos</option>
                        @foreach (range(date('Y') + 1, date('Y') - 4) as $ano)
                            <option value="{{ $ano }}">{{ $ano }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mês -->
                <div>
                    <label class="block text-sm font-medium">Mês de Início</label>
                    <select x-model="mes" class="block w-full mt-1 border-gray-300 rounded">
                        <option value="">Todos</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Busca -->
                <div>
                    <label class="block text-sm font-medium">Servidor</label>
                    <input type="text" x-model="busca" placeholder="Nome, CPF ou matrícula"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>

                <!-- Botão -->
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                        Aplicar Filtros
                    </button>
                </div>
            </form>

            <!-- Listagem dinâmica -->
            <template x-if="ferias.length">
                <div class="mt-6 space-y-6">
                    <template x-for="registro in ferias" :key="registro.id">
                        <div class="p-6 bg-white rounded shadow">
                            <h3 class="mb-4 text-xl font-bold text-gray-800">🗓️ Ano: <span
                                    x-text="registro.ano_exercicio"></span></h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold"
                                        x-text="registro.servidor.nome + ' - Matrícula: ' + registro.servidor.matricula">
                                    </h3>
                                    <p class="text-sm text-gray-600">Situação: <span x-text="registro.situacao"></span>
                                    </p>
                                </div>
                                <a :href="`/ferias/pdf/${registro.servidor.id}`" target="_blank"
                                    class="text-indigo-600 hover:underline">🖨️ Gerar PDF</a>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-if="!ferias.length && !carregando">
                <p class="mt-6 text-gray-500">Nenhum registro encontrado.</p>
            </template>
        </div>

        {{-- {{ $ferias->links() }} --}}
        {{-- Modal --}}
        <div x-show="modalAberto" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg" @click.away="modalAberto = false">
                <h3 class="mb-4 text-lg font-bold">Remarcar Férias</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nova Data Início</label>
                        <input type="date" x-model="novaInicio" class="block w-full mt-1 border-gray-300 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nova Data Fim</label>
                        <input type="date" x-model="novaFim" class="block w-full mt-1 border-gray-300 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Justificativa</label>
                        <textarea x-model="justificativa" rows="3" class="block w-full mt-1 border-gray-300 rounded"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button @click="modalAberto = false"
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
                            justificativa: justificativa
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        modalAberto = false;
                        novaInicio = '';
                        novaFim = '';
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

        document.addEventListener('alpine:init', () => {
            Alpine.data('buscaFerias', () => ({
                ano_exercicio: '',
                mes: '',
                busca: '',
                ferias: [],
                mensagem: '',
                carregando: false,

                buscar() {
                    this.carregando = true;
                    this.mensagem = '';

                    fetch(
                            `/api/ferias?ano_exercicio=${this.ano_exercicio}&mes=${this.mes}&busca=${this.busca}`
                            )
                        .then(res => res.json())
                        .then(data => {
                            this.ferias = data;
                            this.carregando = false;
                        })
                        .catch(() => {
                            this.mensagem = 'Erro ao buscar dados.';
                            this.carregando = false;
                            setTimeout(() => this.mensagem = '', 5000);
                        });
                }
            }));
        });


        <
        /x-app-layout>
