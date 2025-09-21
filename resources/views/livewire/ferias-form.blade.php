<div class="max-w-4xl p-6 mx-auto space-y-6">
    <h2 class="text-xl font-bold">Lançamento de Férias com Múltiplos Períodos</h2>

    @if (session('success'))
        <div class="p-3 text-green-800 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    <div>
        <label class="block mb-1 font-semibold">Ano de Exercício</label>
        <input type="number" wire:model="ano_exercicio" class="w-full p-2 border rounded" />
    </div>

    @foreach ($periodos as $index => $periodo)
        <div class="grid items-end grid-cols-1 gap-4 p-4 border rounded md:grid-cols-4 bg-gray-50">
            <div>
                <label class="block text-sm font-medium">Tipo</label>
                <select wire:model="periodos.{{ $index }}.tipo" class="w-full p-2 border rounded">
                    <option value="Férias">Férias</option>
                    <option value="Abono">Abono</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Início</label>
                <input type="date" wire:model="periodos.{{ $index }}.inicio"
                    class="w-full p-2 border rounded" />
            </div>

            <div>
                <label class="block text-sm font-medium">Fim</label>
                <input type="date" wire:model="periodos.{{ $index }}.fim" class="w-full p-2 border rounded" />
            </div>

            <div>
                @if (count($periodos) > 1)
                    <button wire:click="removePeriodo({{ $index }})"
                        class="text-red-600 hover:underline">Remover</button>
                @endif
            </div>
        </div>
    @endforeach

    @if (count($periodos) < 3)
        <button wire:click="addPeriodo" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">+ Adicionar
            Período</button>
    @endif

    <div>
        <button wire:click="save" class="px-6 py-2 mt-4 text-white bg-blue-600 rounded">Salvar Férias</button>
    </div>
</div>
{{--
<div class="space-y-4">
    <h3 class="text-lg font-semibold">Lançar Férias</h3>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <label>Ano de Exercício</label>
            <input type="number" wire:model="anoExercicio" class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label>Tipo</label>
            <select wire:model="tipo" class="w-full px-3 py-2 border rounded">
                <option value="Integral">Integral</option>
                <option value="Parcelado">Parcelado</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label>Data Início</label>
            <input type="date" wire:model="inicio" class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label>Data Fim</label>
            <input type="date" wire:model="fim" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <button wire:click="salvar" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
        ✅ Lançar Férias
    </button>

    @if (session()->has('success'))
        <div class="mt-4 font-semibold text-green-600">
            {{ session('success') }}
        </div>
    @endif
</div> --}}
