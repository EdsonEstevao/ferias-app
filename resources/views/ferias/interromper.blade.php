<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Férias
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <!-- Iniciando o formulário de interromper ferias -->
                    <div class="max-w-4xl p-6 mx-auto bg-white rounded shadow" x-data="{ motivo: '', dataInterrupcao: '', periodoId: null, modalFracionar: false }">
                        <h2 class="mb-6 text-2xl font-bold">✋ Interromper Férias</h2>

                        @foreach ($feriass as $ferias)
                            @foreach ($ferias->periodos->where('ativo', true) as $periodo)
                                <div class="p-4 mb-4 border rounded bg-gray-50">
                                    <h3 class="text-lg font-semibold text-gray-800">Período em andamento</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($periodo->inicio)->format('d/m/Y') }} a
                                        {{ \Carbon\Carbon::parse($periodo->fim)->format('d/m/Y') }} —
                                        {{ $periodo->dias }} dias ({{ $periodo->tipo }})
                                    </p>

                                    <!-- Botão de remarcar -->
                                    @if ($periodo->ativo && $periodo->dias == 30)
                                        <button @click="modalFracionar = true; periodoSelecionado = {{ $periodo->id }}"
                                            class="text-sm text-yellow-600 hover:underline">
                                            ✂️ Fracionar Férias
                                        </button>
                                    @endif
                                    <div x-show="modalFracionar"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                        <div class="w-full max-w-2xl p-6 bg-white rounded shadow-lg"
                                            @click.away="modalFracionar = false">
                                            <h3 class="mb-4 text-lg font-bold">✂️ Fracionar Período de Férias</h3>

                                            <div x-data="{ periodos: [{ inicio: '', fim: '' }] }">
                                                <template x-for="(p, index) in periodos" :key="index">
                                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                                        <div>
                                                            <label class="text-sm font-medium">Início</label>
                                                            <input type="date" x-model="p.inicio"
                                                                class="w-full px-2 py-1 border rounded">
                                                        </div>
                                                        <div>
                                                            <label class="text-sm font-medium">Fim</label>
                                                            <input type="date" x-model="p.fim"
                                                                class="w-full px-2 py-1 border rounded">
                                                        </div>
                                                    </div>
                                                </template>

                                                <button @click="periodos.push({inicio: '', fim: ''})"
                                                    class="px-3 py-1 mb-4 text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    ➕ Adicionar Período
                                                </button>

                                                <div class="flex justify-end gap-2">
                                                    <button @click="modalFracionar = false"
                                                        class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                                                    <button
                                                        @click="
                    fetch('{{ route('ferias.fracionar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            periodo_id: periodoSelecionado,
                            periodos: periodos
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        modalFracionar = false;
                        location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Erro ao fracionar férias');
                    })
                "
                                                        class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                                                        ✅ Confirmar Fracionamento
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Botão de interromper -->

                                    @if ($periodo->ativo && $periodo->situacao !== 'Interrompido')
                                        <button @click="periodoId = {{ $periodo->id }}"
                                            class="px-3 py-1 mt-3 text-white bg-red-600 rounded hover:bg-red-700">
                                            ✋ Interromper este período
                                        </button>
                                    @endif


                                    {{-- Formulário de interrupção --}}
                                    <div x-show="periodoId === {{ $periodo->id }}" class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Data da
                                                Interrupção</label>
                                            <input type="date" x-model="dataInterrupcao"
                                                class="block w-full mt-1 border-gray-300 rounded">
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
                                                motivo: motivo
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            alert(data.message);
                                            periodoId = null;
                                            motivo = '';
                                            dataInterrupcao = '';
                                            location.reload();
                                        })
                                        .catch(err => {
                                            console.error(err);
                                            alert('Erro ao interromper férias');
                                        })"
                                            class="px-4 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            ✅ Confirmar Interrupção
                                        </button>
                                    </div>
                                    {{-- --> --}}
                                </div>
                            @endforeach
                        @endforeach

                        @if ($ferias->periodos->where('ativo', true)->isEmpty())
                            <p class="text-gray-600">Nenhum período ativo para interrupção.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
