@props(['periodo'])
<div class="pl-4 ml-6 space-y-0.5 border-l-2 border-gray-300">
    {{-- <div class="pl-4 ml-6 border-l-2 border-gray-300"> --}}
    @foreach ($periodo->todosFilhosRecursivos as $filho)
        @php
            // Calcular nível de profundidade para indentação
            $nivel = 0;
            $pai = $filho;
            while ($pai->periodo_origem_id && $pai->origem) {
                $nivel++;
                $pai = $pai->origem;
            }

            // Definir classes CSS baseadas na situação
            $classes = match (true) {
                $filho->convertido_abono => 'bg-purple-50 border-l-4 border-purple-400',
                $filho->situacao === 'Interrompido' => 'bg-orange-50 border-l-4 border-orange-400',
                $filho->situacao === 'Remarcado' => 'bg-blue-50 border-l-4 border-blue-400',
                $filho->usufruido => 'bg-green-100 border-l-4 border-green-500',
                default => 'bg-gray-50 border-l-4 border-gray-300',
            };

            // Encontrar o status original correspondente

        @endphp

        <div style="margin-left: {{ $nivel * 20 }}px;" class="p-3 {{ $classes }} rounded" x-data="{
            interrupcaoAbertaFilho: false,
            periodoInicioFilho: '{{ date('Y-m-d', strtotime($filho->inicio)) }}',
            periodoFimFilho: '{{ date('Y-m-d', strtotime($filho->fim)) }}',
            periodoIdFilho: '{{ $filho->id }}',
        
        }">

            <!-- Conteúdo do filho -->
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p
                        class="font-semibold
                        @if ($filho->convertido_abono) text-purple-700'
                        @elseif ($filho->situacao === 'Interrompido') text-orange-700'
                        @elseif($filho->situacao === 'Remarcado') text-blue-700'
                        @elseif($filho->usufruido) text-green-700'
                        @else text-gray-700 @endif">

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
                        @elseif ($filho->convertido_abono)
                            <span
                                class="px-2 py-1 ml-2 text-xs font-semibold text-purple-800 bg-purple-200 rounded-full">
                                💵 Convertido em Abono
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


                    <p class="text-sm text-gray-600">
                        {{ date('d/m/Y', strtotime($filho->inicio)) }} a
                        {{ date('d/m/Y', strtotime($filho->fim)) }}
                        — {{ $filho->dias }} dias
                    </p>


                    <p class="text-xs text-gray-500">
                        Situação: {{ $filho->situacao }}
                        @if ($filho->usufruido && $filho->data_usufruto)
                            • Usufruído em: {{ date('d/m/Y', strtotime($filho->data_usufruto)) }}
                        @endif
                        @if ($filho->convertido_abono && $filho->data_conversao_abono)
                            • Convertido em abono em: {{ date('d/m/Y', strtotime($filho->data_conversao_abono)) }}
                        @endif
                    </p>

                    <!-- Botões de Ação -->
                    <div class="mt-2">
                        <div class="flex flex-wrap gap-2">
                            <!-- Botões para períodos convertidos em abono -->
                            @if ($filho->convertido_abono)
                                <div class="flex flex-col gap-2">
                                    <a href="{{ $filho->url_abono }}" target="_blank"
                                        class="text-blue-600 hover:underline">{{ $filho->title_abono }}</a>
                                    <a href="{{ route('ferias.reverter-abono.view', $filho->id) }}"
                                        class="px-3 py-1 text-xs text-orange-600 bg-orange-100 rounded hover:bg-orange-200">
                                        ↩️ Reverter Abono
                                    </a>
                                </div>
                            @elseif ($filho->ativo && $filho->situacao === 'Remarcado' && !$filho->usufruido && !$filho->convertido_abono)
                                <!-- Botão para converter para abono -->
                                <a href="{{ route('ferias.converter-abono.view', $filho->id) }}"
                                    class="px-3 py-1 text-xs text-purple-600 bg-purple-100 rounded hover:bg-purple-200">
                                    💰 Converter Abono
                                </a>
                                <button data-periodo-id="{{ $filho->id }}"
                                    data-inicio="{{ $filho->inicio_formatado }}"
                                    data-fim="{{ $filho->fim_formatado }}" data-title="{{ $filho->title }}"
                                    data-url="{{ $filho->url }}" data-dias="{{ $filho->dias }}"
                                    data-justificativa="{{ $filho->justificativa }}"
                                    @click="abrirModalEditarPeriodo($event)">
                                    ✏️ Editar
                                </button>


                                @role('super admin')
                                    <button
                                        @click="confirmarExclusaoPeriodo({{ $filho->id }}, '{{ date('d/m/Y', strtotime($filho->inicio)) }}', '{{ date('d/m/Y', strtotime($filho->fim)) }}')"
                                        class="text-xs text-red-600 hover:underline">
                                        🗑️ Excluir
                                    </button>
                                @endrole


                                <button @click="marcarComoUsufruido({{ $filho->id }})"
                                    class="text-xs text-purple-600 hover:underline">
                                    ✅ Marcar como Usufruído
                                </button>
                            @endif

                            @if ($filho->situacao !== 'Usufruido' || ($filho->tipo == 'Abono' && !$filho->convertido_abono))
                                @if ($filho->usufruido && $filho->ativo && !$filho->convertido_abono)
                                    <button @click="desmarcarUsufruto({{ $filho->id }})"
                                        class="text-xs text-orange-600 hover:underline">
                                        ↩️ Desmarcar Usufruto
                                    </button>
                                @endif
                            @endif
                        </div>

                        @if ($filho->ativo && $filho->situacao && !$filho->usufruido && !$filho->convertido_abono)
                            <div class="flex flex-wrap gap-2 mt-2">
                                <button @click="abrirModalRemarcacao({{ $filho->id }}, {{ json_encode($filho) }})"
                                    class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                                    🔁 Remarcar
                                </button>
                                @if ($filho->situacao === 'Interrompido')
                                    @role('super admin')
                                        <button
                                            @click="confirmarExclusaoPeriodo({{ $filho->id }}, '{{ date('d/m/Y', strtotime($periodo->inicio)) }}', '{{ date('d/m/Y', strtotime($periodo->fim)) }}')"
                                            class="px-2 py-2 text-xs text-red-600 bg-red-200 rounded shadow-lg hover:bg-red-500 hover:text-red-100 text-nowrap">
                                            🗑️ Excluir
                                        </button>
                                    @endrole
                                    <!-- Botão para converter para abono -->
                                    <a href="{{ route('ferias.converter-abono.view', $filho->id) }}"
                                        class="px-3 py-1 text-xs text-purple-600 bg-purple-100 rounded hover:bg-purple-200">
                                        💰 Converter Abono
                                    </a>
                                @endif

                                @if ($filho->situacao !== 'Interrompido')
                                    <button @click="interrupcaoAbertaFilho = !interrupcaoAbertaFilho"
                                        class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                        ✋ Interromper este Período
                                    </button>
                                @endif

                            </div>
                            <!-- Formulário de interrupção Mobile -->
                            <div x-show="interrupcaoAbertaFilho" x-cloak="true"
                                class="p-3 mt-2 space-y-3 transition duration-300 transform rounded bg-gray-50"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transform opacity-100 scale-100"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Data da
                                        Interrupção</label>
                                    <input type="date" x-model="dataInterrupcao" :min="periodoInicioFilho"
                                        :max="periodoFimFilho"
                                        class="block w-full mt-1 text-sm border-gray-300 rounded">
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
                                                periodo_id: periodoIdFilho,
                                                data: dataInterrupcao,
                                                motivo: motivo,
                                                linkDiof: linkDiof,
                                                tituloDiof: tituloDiof,
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            alert(data.message);
                                            periodoIdFilho = null;
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
                                            interrupcaoAbertaFilho = false;
                                            periodoIdFilho = null;
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach



</div>
