@props(['periodo'])

<div class="pl-4 mt-2 ml-4 border-l-2">
    {{-- <p class="font-semibold text-gray-800">
        {{ $periodo->situacao }} — {{ \Carbon\Carbon::parse($periodo->inicio)->format('d/m/Y') }} a
        {{ \Carbon\Carbon::parse($periodo->fim)->format('d/m/Y') }}
    </p> --}}



    @foreach ($periodo->todosFilhosRecursivos as $filho)
        <div class="text-xl text-yellow-500">⏸️</div>
        <div>

            <div>
                <p class="font-semibold text-gray-700">{{ $filho->situacao }}</p>
                <p class="text-sm text-gray-600">
                    {{ date('d/m/Y', strtotime($filho->inicio)) }} a
                    {{ date('d/m/Y', strtotime($filho->fim)) }} —
                    {{ $filho->dias }} dias
                </p>
                <p class="text-xs text-gray-500">Situação: {{ $filho->situacao }}</p>
            </div>
            <div>
                @if ($filho->ativo)
                    <button @click="modalAberto = true; periodoSelecionado = {{ $filho->id }}"
                        class="px-3 py-1 mt-3 text-white bg-blue-600 rounded hover:bg-blue-700">
                        🔁 Remarcar
                    </button>
                    @if ($filho->situacao !== 'Interrompido')
                        <button @click="periodoId = {{ $filho->id }}"
                            class="px-3 py-1 mt-3 text-white bg-red-600 rounded hover:bg-red-700">
                            ✋ Interromper este período
                        </button>
                    @endif

                    {{-- Formulário de interrupção --}}
                    <div x-show="periodoId === {{ $filho->id }}" class="mt-4 space-y-4">
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
                @endif
            </div>
        </div>
        <x-periodo :periodo="$filho" />
    @endforeach
</div>
