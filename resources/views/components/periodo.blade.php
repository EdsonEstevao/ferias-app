<div class="pl-4 ml-6 space-y-2 border-l-2 border-gray-300">
    @foreach ($periodo->todosFilhosRecursivos as $filho)
        <div x-data="{
            aberto: false,
            periodoInicio: '{{ date('Y-m-d', strtotime($filho->inicio)) }}',
            periodoFim: '{{ date('Y-m-d', strtotime($filho->fim)) }}',
        }"
            class="p-3 {{ $filho->situacao === 'Interrompido' ? 'bg-orange-50 border-l-4 border-orange-400' : ($filho->situacao === 'Remarcado' ? 'bg-blue-50 border-l-4 border-blue-400' : ($filho->usufruido ? 'bg-green-100 border-l-4 border-green-500' : 'bg-gray-50')) }} rounded">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p
                        class="font-semibold {{ $filho->situacao === 'Interrompido' ? 'text-orange-700' : ($filho->situacao === 'Remarcado' ? 'text-blue-700' : ($filho->usufruido ? 'text-green-700' : 'text-gray-700')) }}">
                        @if ($filho->situacao === 'Interrompido')
                            🔄 Interrompido
                        @elseif($filho->situacao === 'Remarcado')
                            📅 Remarcado
                        @else
                            📌 Período
                        @endif

                        @if ($filho->usufruido)
                            <span class="px-2 py-1 ml-2 text-xs font-semibold text-green-800 bg-green-200 rounded-full">
                                ✅ USUFRUÍDO
                            </span>
                        @else
                            <span
                                class="px-2 py-1 ml-2 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">
                                ⏳ PENDENTE
                            </span>
                        @endif
                    </p>

                    <!-- Link da Portaria -->
                    @if ($filho->title)
                        <p class="text-sm text-gray-600">
                            <a href="{{ $filho->url }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $filho->title }}
                            </a>
                        </p>
                    @endif

                    {{-- @if ($filho->ativo) --}}
                    <p class="text-sm text-gray-600">
                        {{ date('d/m/Y', strtotime($filho->inicio)) }} a
                        {{ date('d/m/Y', strtotime($filho->fim)) }}
                        — {{ $filho->dias }} dias
                    </p>
                    {{-- @endif --}}

                    <p class="text-xs text-gray-500">
                        Situação: {{ $filho->situacao }}
                        @if ($filho->usufruido && $filho->data_usufruto)
                            • Usufruído em: {{ date('d/m/Y', strtotime($filho->data_usufruto)) }}
                        @endif
                    </p>

                    <!-- Botões de Ação -->
                    {{-- <div class="flex gap-2 mt-2"> --}}
                    <div class="">
                        <div class="flex gap-2 mt-2">
                            <button @click="aberto = !aberto" class="text-xs text-blue-600 hover:underline">
                                <span x-text="aberto ? 'Ocultar detalhes' : 'Ver detalhes'"></span>
                            </button>

                            @if ($filho->ativo && $filho->situacao === 'Remarcado' && !$filho->usufruido)
                                <button
                                    @click="abrirModalEditarPeriodo({{ $filho->id }}, '{{ $filho->inicio }}', '{{ $filho->fim }}', {{ $filho->dias }}, '{{ $filho->justificativa }}')"
                                    class="text-xs text-green-600 hover:underline">
                                    ✏️ Editar
                                </button>
                                @role('super admin')
                                    <button
                                        @click="confirmarExclusaoPeriodo({{ $filho->id }}, '{{ date('d/m/Y', strtotime($filho->inicio)) }}', '{{ date('d/m/Y', strtotime($filho->fim)) }}')"
                                        class="text-xs text-red-600 hover:underline">
                                        🗑️ Excluir
                                    </button>
                                @endrole

                                <!-- NOVO: Botão para marcar como usufruído -->
                                <button @click="marcarComoUsufruido({{ $filho->id }})"
                                    class="text-xs text-purple-600 hover:underline">
                                    ✅ Marcar como Usufruído
                                </button>
                            @endif

                            @if ($filho->situacao !== 'Usufruido' || $filho->tipo == 'Abono')
                                @if ($filho->usufruido && $filho->ativo)
                                    <button @click="desmarcarUsufruto({{ $filho->id }})"
                                        class="text-xs text-orange-600 hover:underline">
                                        ↩️ Desmarcar Usufruto
                                    </button>
                                @endif
                            @endif

                            {{-- @if ($filho->usufruido)
                                <button @click="desmarcarUsufruto({{ $filho->id }})"
                                    class="text-xs text-orange-600 hover:underline">
                                    ↩️ Desmarcar Usufruto
                                </button>
                            @endif --}}
                        </div>

                        @if ($filho->ativo && $filho->situacao === $filho->situacao && !$filho->usufruido)
                            {{-- <button
                                @click="modalAberto = true; periodoSelecionado = {{ $filho->id }}; filhos = {{ json_encode($filho) }}"
                                class="px-3 py-1 mt-3 text-white bg-blue-600 rounded hover:bg-blue-700">
                                🔁 Remarcar
                            </button> --}}
                            <button @click="abrirModalRemarcacao({{ $filho->id }}, {{ json_encode($filho) }})"
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
            </div>

            <!-- Recursividade para filhos dos filhos -->
            @if ($filho->filhos->count() > 0)
                <x-periodo :periodo="$filho" />
            @endif
        </div>
    @endforeach
</div>
