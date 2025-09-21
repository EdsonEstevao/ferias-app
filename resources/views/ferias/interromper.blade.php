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
                    {{-- @livewire('ferias-interromper') --}}
                    <div class="max-w-4xl p-6 mx-auto bg-white rounded shadow" x-data="{ motivo: '', dataInterrupcao: '', periodoId: null }">
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

                                    <button @click="periodoId = {{ $periodo->id }}"
                                        class="px-3 py-1 mt-3 text-white bg-red-600 rounded hover:bg-red-700">
                                        ✋ Interromper este período
                                    </button>

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
