<div class="space-y-4">
    <h2 class="text-xl font-bold">🔍 Buscar Férias por Servidor</h2>

    <input type="text" wire:model.debounce.500ms="query" placeholder="Digite nome, CPF ou matrícula"
        class="w-full px-3 py-2 border rounded">

    @if ($servidores->isEmpty())
        <p class="mt-4 text-gray-500">Nenhum servidor encontrado.</p>
    @else
        <ul class="mt-4 divide-y">
            @foreach ($servidores as $servidor)
                <li class="py-3">
                    <div class="font-semibold text-gray-800">
                        {{ $servidor->nome }} — Matrícula: {{ $servidor->matricula }}
                    </div>
                    <div class="text-sm text-gray-600">CPF: {{ $servidor->cpf }}</div>

                    <ul class="mt-2 ml-4 text-sm text-gray-700 list-disc">
                        @foreach ($servidor->ferias as $ferias)
                            @foreach ($ferias->periodos as $p)
                                <li>
                                    {{ $p->inicio }} a {{ $p->fim }} —
                                    {{ $p->dias }} dias
                                    <span class="{{ $p->abono ? 'text-green-600' : 'text-blue-600' }}">
                                        ({{ $p->tipo }})
                                    </span>
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    @endif
</div>
