@props(['periodo'])

<div class="pl-4 mt-2 ml-4 border-l-2 ">
    @foreach ($periodo->todosFilhosRecursivos as $filho)
        <div x-data="{
            periodoInicio: '{{ $filho->inicio }}',
            periodoFim: '{{ $filho->fim }}',
        }"
            class="flex flex-col px-2 py-2 rounded-lg {{ $filho->situacao === 'Interrompido' ? 'bg-red-100 ' : '' }} {{ $filho->situacao === 'Remarcado' ? 'bg-yellow-100' : '' }} {{ $filho->ativo ? ' shadow-lg' : '' }}">

            @if ($filho->situacao === 'Interrompido')
                <div
                    class="text-xl flex justify-start items-center gap-2 {{ $filho->situacao === 'Interrompido' ? 'bg-red-100 text-red-500' : '' }} {{ $filho->situacao === 'Remarcado' ? 'bg-yellow-100 text-yellow-500' : '' }}">
                    {{-- <i class='fas fa-ban'></i> --}}
                    <i class="fa-regular fa-calendar-xmark"></i>
                    {{ $filho->situacao }}
                </div>
            @else
                <div
                    class="text-xl flex justify-start items-center gap-2 {{ $filho->situacao === 'Interrompido' ? 'bg-red-100 text-red-500' : '' }} {{ $filho->situacao === 'Remarcado' ? 'bg-yellow-100 text-yellow-500' : '' }}">
                    {{-- <i class="fa-brands fa-gg-circle"></i> --}}
                    <i class="fa-regular fa-calendar-check"></i>
                    {{ $filho->situacao }}
                </div>
            @endif


            <div>

                <!-- link da Portaria -->
                @if ($filho->title)
                    <p class="text-sm text-gray-600">
                        <a href="{{ $filho->url }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $filho->title }}
                        </a>
                    </p>
                @endif
                <p class="text-sm text-gray-600">
                    {{ date('d/m/Y', strtotime($filho->inicio)) }} a
                    {{ date('d/m/Y', strtotime($filho->fim)) }} —
                    {{ $filho->dias }} dias
                </p>
                <p class="text-xs text-gray-500">Situação: {{ $filho->situacao }}</p>
            </div>


            <div>
                @if ($filho->ativo || $filho->situacao === 'Planejado')
                    <button
                        @click="modalAberto = true; periodoSelecionado = {{ $filho->id }}; filhos = {{ json_encode($filho) }}"
                        class="px-3 py-1 mt-3 text-white bg-blue-600 rounded hover:bg-blue-700">
                        🔁 Remarcar
                    </button>
                    @if ($filho->situacao !== 'Interrompido')
                        <button @click="periodoId = {{ $filho->id }}"
                            class="px-3 py-1 mt-3 text-white bg-red-600 rounded hover:bg-red-700">
                            ✋ Interromper este período
                        </button>
                    @endif

                    <!-- Formulário de interrupção -->
                    <div x-show="periodoId === {{ $filho->id }}"
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
                                                tituloDiof: tituloDiof
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            alert(data.message);
                                            periodoId = null;
                                            motivo = '';
                                            linkDiof = '';
                                            tituloDiof = '';
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
                        <button
                            @click="setTimeout(() => {
                                periodoId = null;
                                motivo = '';
                                dataInterrupcao = '';
                                tituloDiof = '';
                                linkDiof = '';
                                novaFim = '';
                                novaInicio = '';
                            }, 10);"
                            class="px-3 py-1 text-white bg-gray-600 rounded hover:bg-gray-700">
                            ❌ Cancelar
                        </button>
                    </div>
                @endif
            </div>
        </div>
        {{-- <x-periodo :periodo="$filho" /> --}}
    @endforeach
</div>
