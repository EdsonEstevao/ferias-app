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
                $filho->situacao === 'Interrompido' => 'bg-orange-50 border-l-4 border-orange-400',
                $filho->situacao === 'Remarcado' => 'bg-blue-50 border-l-4 border-blue-400',
                $filho->usufruido => 'bg-green-100 border-l-4 border-green-500',
                default => 'bg-gray-50 border-l-4 border-gray-300',
            };
        @endphp

        <div style="margin-left: {{ $nivel * 20 }}px;" class="p-3 {{ $classes }} rounded">

            <!-- Conteúdo do filho -->
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p
                        class="font-semibold
                        @if ($filho->situacao === 'Interrompido') text-orange-700'
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
                    <div class="mt-2">
                        <div class="flex flex-wrap gap-2">
                            @if ($filho->ativo && $filho->situacao === 'Remarcado' && !$filho->usufruido)
                                <button data-periodo-id="{{ $filho->id }}" data-inicio="{{ $filho->inicio }}"
                                    data-fim="{{ $filho->fim }}" data-dias="{{ $filho->dias }}"
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

                            @if ($filho->situacao !== 'Usufruido' || $filho->tipo == 'Abono')
                                @if ($filho->usufruido && $filho->ativo)
                                    <button @click="desmarcarUsufruto({{ $filho->id }})"
                                        class="text-xs text-orange-600 hover:underline">
                                        ↩️ Desmarcar Usufruto
                                    </button>
                                @endif
                            @endif
                        </div>

                        @if ($filho->ativo && $filho->situacao && !$filho->usufruido)
                            <div class="flex flex-wrap gap-2 mt-2">
                                <button @click="abrirModalRemarcacao({{ $filho->id }}, {{ json_encode($filho) }})"
                                    class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                                    🔁 Remarcar
                                </button>

                                @if ($filho->situacao !== 'Interrompido')
                                    <button @click="periodoId = {{ $filho->id }}"
                                        class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                        ✋ Interromper
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
