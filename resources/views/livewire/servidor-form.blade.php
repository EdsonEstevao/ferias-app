<div class="max-w-4xl p-6 mx-auto space-y-6">
    <h2 class="text-xl font-bold">Cadastro de Servidor</h2>

    @if (session('success'))
        <div class="p-3 text-green-800 bg-green-100 rounded">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <input type="text" wire:model.defer="nome" placeholder="Nome" class="p-2 border rounded" />
        <input type="text" wire:model.defer="cpf" placeholder="CPF" class="p-2 border rounded" />
        <input type="email" wire:model.defer="email" placeholder="Email" class="p-2 border rounded" />
        <input type="text" wire:model.defer="matricula" placeholder="Matrícula" class="p-2 border rounded" />
        <input type="text" wire:model.defer="telefone" placeholder="Telefone" class="p-2 border rounded" />
        <input type="text" wire:model.defer="secretaria" placeholder="Secretaria" class="p-2 border rounded" />
        <input type="text" wire:model.defer="lotacao" placeholder="Lotação" class="p-2 border rounded" />
        <input type="text" wire:model.defer="departamento" placeholder="Departamento" class="p-2 border rounded" />
        <input type="text" wire:model.defer="processo_implantacao" placeholder="Processo de Implantação"
            class="p-2 border rounded" />
        <input type="text" wire:model.defer="processo_disposicao" placeholder="Processo de Disposição"
            class="p-2 border rounded" />
        <input type="text" wire:model.defer="numero_memorando" placeholder="Número do Memorando"
            class="p-2 border rounded" />

        <div class="md:col-span-2">
            <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded">Salvar Servidor</button>
        </div>
    </form>
</div>
