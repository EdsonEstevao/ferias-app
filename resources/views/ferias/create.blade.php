<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Férias
        </h2>
    </x-slot>

    <form id="form" action="">
        <div class="max-w-5xl p-6 mx-auto bg-white rounded shadow" x-data="{
            servidorId: {{ $servidorId }},
            ano_exercicio: '{{ date('Y') }}',
            novoInicio: '',
            novoFim: '',
            novoTipo: 'Férias',
            periodos: [],
            diasCalculados: 0,
            adicionarPeriodo() {

                if (!this.novoInicio || !this.novoFim) return;
                this.formPreventDefault();
                const dias = (new Date(this.novoFim) - new Date(this.novoInicio)) / (1000 * 60 * 60 * 24) + 1;
                this.periodos.push({
                    inicio: this.novoInicio,
                    fim: this.novoFim,
                    dias: dias,
                    tipo: this.novoTipo
                });
                this.novoInicio = '';
                this.novoFim = '';
                this.novoTipo = 'Férias';
            },
            removerPeriodo(index) {
                this.periodos.splice(index, 1);
            },
            formPreventDefault() {
                document.getElementById('form').addEventListener('submit', function(e) {
                    e.preventDefault();
                });
            },
            calcularDias() {
                if (this.novoInicio && this.novoFim) {
                    const inicio = new Date(this.novoInicio);
                    const fim = new Date(this.novoFim);
                    const diff = (fim - inicio) / (1000 * 60 * 60 * 24);
                    this.diasCalculados = diff >= 0 ? diff + 1 : 0;
                } else {
                    this.diasCalculados = 0;
                }
            }


        }">
            <h2 class="mb-6 text-2xl font-bold">📅 Lançar Férias</h2>

            {{-- Seleção de servidor --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Servidor</label>
                <input type="hidden" x-model="servidorId" name="servidor_id">
                <select x-model="servidorId" class="block w-full mt-1 border-gray-300 rounded">
                    @foreach ($servidores as $servidor)
                        <option value="{{ $servidor->id }}" disabled>{{ $servidor->nome }} ({{ $servidor->matricula }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Ano de exercício --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Ano de Exercício</label>
                <input type="number" x-model="ano_exercicio" class="block w-full mt-1 border-gray-300 rounded">
            </div>

            {{-- Formulário de período --}}
            <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">
                <div>
                    <label>Data Início</label>
                    <input type="date" x-model="novoInicio" @change="calcularDias"
                        class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label>Data Fim</label>
                    <input type="date" x-model="novoFim" @change="calcularDias"
                        class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label>Tipo</label>
                    <select x-model="novoTipo" class="w-full px-3 py-2 border rounded">
                        <option value="Férias">Férias</option>
                        <option value="Abono">Abono</option>
                    </select>
                </div>
            </div>

            <div class="mb-4 px-3 py-2 text-sm text-gray-700 bg-red-100 rounded" x-show="diasCalculados > 0"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
                <template x-if="diasCalculados > 0">
                    <p class="text-red-400">Período de <strong><span x-text="diasCalculados"></span></strong> dias</p>
                </template>
            </div>

            <button @click="adicionarPeriodo" class="px-4 py-2 mb-6 text-white bg-blue-600 rounded hover:bg-blue-700">
                <i class="mr-2 fas fa-plus"></i>Adicionar Período
            </button>

            {{-- Lista de períodos adicionados --}}
            <template x-if="periodos.length > 0">
                <div class="mb-6">
                    <h4 class="mb-2 font-semibold">Períodos Selecionados:</h4>
                    <ul class="ml-5 space-y-2 text-sm text-gray-700 list-disc">
                        <template x-for="(p, index) in periodos" :key="index">
                            <li>
                                <span x-text="p.inicio"></span> a <span x-text="p.fim"></span> —
                                <span x-text="p.dias"></span> dias
                                <span x-text="p.tipo === 'Abono' ? '(Abono)' : '(Férias)'"
                                    :class="p.tipo === 'Abono' ? 'text-green-600' : 'text-blue-600'"></span>
                                <button @click="removerPeriodo(index)"
                                    class="ml-2 text-xs text-red-500 hover:underline">Remover</button>
                            </li>
                        </template>
                    </ul>
                </div>
            </template>

            {{-- Botão de envio (simulado) --}}
            <button @click="salvarTodos(servidorId, ano_exercicio, periodos)"
                class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                ✅ Lançar Férias
            </button>

            {{-- Botão de voltar --}}
            <a href="{{ route('ferias.index') }}" class="px-4 py-2 text-white bg-gray-600 rounded hover:bg-gray-700">
                Ir para Férias
            </a>
        </div>
    </form>

    {{-- @livewire('ferias-form', ['servidorId' => intval($servidorId)]) --}}

    <script>
        function salvarTodos(servidorId, ano_exercicio, periodos) {
            console.log('salvar férias')
            fetch('{{ route('ferias.lancar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        servidorId,
                        ano_exercicio,
                        periodos
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        periodos = [];
                        document.querySelector('#form').reset();
                        location.reload();
                    } else {
                        alert('Erro ao lançar férias');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Falha na comunicação com o servidor');
                })
        }
    </script>
</x-app-layout>
