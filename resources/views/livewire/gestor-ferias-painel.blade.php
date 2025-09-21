<div class="max-w-6xl p-6 mx-auto space-y-6">
    <h2 class="text-2xl font-bold">Painel de Férias - {{ $servidor->nome }}</h2>

    @if (session('success'))
        <div class="p-3 text-green-800 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    {{-- @foreach ($ferias as $registro)
        <div class="p-4 bg-white border rounded shadow">
            <h3 class="mb-2 text-lg font-semibold">Ano: {{ $registro->ano_exercicio }}</h3>

            @foreach ($registro->periodos as $periodo)
                <div class="p-3 mb-3 border rounded bg-gray-50">
                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-5">
                        <div><strong>Tipo:</strong> {{ $periodo->tipo }}</div>
                        <div><strong>Dias:</strong> {{ $periodo->dias }}</div>
                        <div><strong>Início:</strong> {{ \Carbon\Carbon::parse($periodo->inicio)->format('d/m/Y') }}
                        </div>
                        <div><strong>Fim:</strong> {{ \Carbon\Carbon::parse($periodo->fim)->format('d/m/Y') }}</div>
                        <div><strong>Situação:</strong> {{ $periodo->situacao }}</div>
                    </div>

                    <div class="flex gap-2 mt-2">
                        @if ($periodo->ativo && $periodo->situacao === 'Planejado')
                            <button wire:click="interromper({{ $periodo->id }})"
                                class="px-3 py-1 text-white bg-red-600 rounded">Interromper</button>
                        @endif

                        @if (!$periodo->ativo && $periodo->situacao === 'Interrompido')
                            <button wire:click="remarcar({{ $periodo->id }})"
                                class="px-3 py-1 text-white bg-yellow-500 rounded">Remarcar</button>
                        @endif
                    </div>

                    @if ($periodo->eventos->count())
                        <div class="mt-3 text-sm text-gray-600">
                            <strong>Histórico:</strong>
                            <ul class="ml-4 list-disc">
                                @foreach ($periodo->eventos as $evento)
                                    <li>
                                        {{ $evento->acao }} em {{ $evento->data_acao->format('d/m/Y H:i') }} —
                                        {{ $evento->descricao }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach --}}


    @foreach ($ferias as $registro)
        <div class="p-4 mb-6 bg-white border rounded shadow">
            <h3 class="mb-4 text-lg font-semibold">Ano: {{ $registro->ano_exercicio }}</h3>

            @foreach ($registro->periodos as $periodo)
                <div class="p-4 mb-4 border rounded bg-gray-50">
                    <!--- Ordem -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold">Ordem: {{ $ordemDesc[$periodo->ordem - 1] }} Período</span>
                    </div>
                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-5">
                        <div><strong>Tipo:</strong> {{ $periodo->tipo }}</div>
                        <div><strong>Dias:</strong> {{ $periodo->dias }}</div>
                        <div><strong>Início:</strong> {{ \Carbon\Carbon::parse($periodo->inicio)->format('d/m/Y') }}
                        </div>
                        <div><strong>Fim:</strong> {{ \Carbon\Carbon::parse($periodo->fim)->format('d/m/Y') }}</div>
                        <div><strong>Situação:</strong> {{ $periodo->situacao }}</div>
                    </div>

                    {{-- Botões de ação --}}
                    <div class="flex gap-2 mt-3">
                        {{-- @if ($periodo->ativo && $periodo->situacao === 'Planejado')
                            <button wire:click="interromper({{ $periodo->id }})"
                                class="px-3 py-1 text-white bg-red-600 rounded hover:bg-red-700">
                                ✋ Interromper
                            </button>
                        @endif --}}

                        @if (
                            ($periodo->ativo && $periodo->situacao === 'Remarcado') ||
                                ($periodo->situacao === 'Planejado' && $periodo->ativo) ||
                                ($periodo->situacao === 'Interrompido' && $periodo->ativo))
                            <button wire:click="iniciarRemarcacao({{ $periodo->id }})"
                                class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                🔁 Remarcar
                            </button>
                        @endif
                    </div>

                    {{-- Formulário de remarcação --}}
                    @if ($remarcarId === $periodo->id)
                        <div class="grid items-end grid-cols-1 gap-4 mt-4 md:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nova Data Início</label>
                                <input type="date" wire:model="novaDataInicio"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nova Data Fim</label>
                                <input type="date" wire:model="novaDataFim"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm">
                            </div>
                            <div>
                                <button wire:click="salvarRemarcacao"
                                    class="w-full px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                                    ✅ Confirmar Remarcação
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Histórico de eventos --}}
                    @if ($periodo->eventos->count())
                        <div class="mt-4 text-sm text-gray-600">
                            <strong>Histórico:</strong>
                            <ul class="mt-2 ml-5 list-disc">
                                @foreach ($periodo->eventos as $evento)
                                    <li>
                                        {{ $evento->acao }} em {{ date('d/m/Y H:i', strtotime($evento->data_acao)) }}
                                        —
                                        {{ $evento->descricao }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

</div>
