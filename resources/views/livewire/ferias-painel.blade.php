<div class="max-w-4xl p-6 mx-auto space-y-6">
    <h2 class="text-xl font-bold">Painel de Férias do Servidor</h2>

    @if (session('success'))
        <div class="p-3 text-green-800 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    @if ($ferias)
        <p class="text-sm text-gray-600">Ano de exercício: {{ $ferias->ano_exercicio }}</p>

        @foreach ($ferias->periodos as $periodo)
            @if ($periodo->ativo)
                <div class="p-4 mb-4 border rounded bg-gray-50">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                        <div><strong>Tipo:</strong> {{ $periodo->tipo }}</div>
                        <div><strong>Dias:</strong> {{ $periodo->dias }}</div>
                        <div><strong>Início:</strong> {{ \Carbon\Carbon::parse($periodo->inicio)->format('d/m/Y') }}
                        </div>
                        <div><strong>Fim:</strong> {{ \Carbon\Carbon::parse($periodo->fim)->format('d/m/Y') }}</div>
                        <div><strong>Situação:</strong> {{ $periodo->situacao }}</div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        {{-- <button wire:click="atualizarSituacao({{ $periodo->id }}, 'Remarcado')"
                            class="px-3 py-1 text-white bg-yellow-500 rounded">Remarcar</button> --}}
                        <button wire:click="atualizarSituacao({{ $periodo->id }}, 'Interrompido')"
                            class="px-3 py-1 text-white bg-red-600 rounded">Interromper</button>
                        {{-- <button wire:click="atualizarSituacao({{ $periodo->id }}, 'Planejado')"
                            class="px-3 py-1 text-white bg-green-600 rounded">Replanejar</button> --}}
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <p class="text-gray-500">Nenhum lançamento de férias encontrado para este servidor neste ano.</p>
    @endif
</div>
