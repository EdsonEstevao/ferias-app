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
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
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
                    <button type="submit" class="w-full px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
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
                </div>

                @foreach ($registro->periodos->whereNull('periodo_origem_id') as $periodo)
                    <div class="space-y-4" x-data="{ aberto: false }">
                        {{-- Período original --}}
                        <div
                            class="flex items-start gap-3 {{ $periodo->tipo == 'Abono' ? 'bg-yellow-100 rounded-lg shadow-xl' : 'text-blue-600' }}">
                            <div class="text-xl text-blue-600">📌</div>
                            <div>
                                <p class="font-semibold text-gray-700">Período Original ({{ $periodo->ordem }}º Período
                                    {{ $periodo->tipo == 'Abono' ? 'de Abono' : 'de Férias' }})
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                                    {{ date('d/m/Y', strtotime($periodo->fim)) }} —
                                    {{ $periodo->dias }} dias
                                </p>
                                <p class="text-xs text-gray-500">Situação: {{ $periodo->situacao }}</p>
                                <button @click="aberto = !aberto" class="mt-2 text-xs text-blue-500 hover:underline">
                                    {{ 'aberto' ? 'Ocultar sequência' : 'Ver sequência completa' }}
                                </button>
                            </div>
                        </div>

                        {{-- Filhos: interrupções e remarcações --}}
                        <div x-show="aberto" class="pl-4 ml-6 space-y-4 border-l-2 border-gray-300">
                            <x-periodo :periodo="$periodo" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{ $ferias->links() }}
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




</x-app-layout>
