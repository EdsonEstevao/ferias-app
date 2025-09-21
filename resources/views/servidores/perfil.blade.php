<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Servidores
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-6xl p-6 mx-auto space-y-8">

                        {{-- Dados do servidor --}}
                        <div class="p-6 bg-white rounded shadow">
                            <h2 class="mb-4 text-2xl font-bold">Perfil do Servidor</h2>
                            <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                                <div><strong>Nome:</strong> {{ $servidor->nome }}</div>
                                <div><strong>CPF:</strong> {{ $servidor->cpf }}</div>
                                <div><strong>Email:</strong> {{ $servidor->email }}</div>
                                <div><strong>Matrícula:</strong> {{ $servidor->matricula }}</div>
                                <div><strong>Telefone:</strong> {{ $servidor->telefone }}</div>
                                <div><strong>Secretaria:</strong> {{ $servidor->secretaria }}</div>
                                <div><strong>Lotação:</strong> {{ $servidor->lotacao }}</div>
                                <div><strong>Departamento:</strong> {{ $servidor->departamento }}</div>
                                <div><strong>Processo de Implantação:</strong> {{ $servidor->processo_implantacao }}
                                </div>
                                <div><strong>Processo de Disposição:</strong> {{ $servidor->processo_disposicao }}</div>
                                <div><strong>Memorando:</strong> {{ $servidor->numero_memorando }}</div>
                            </div>
                        </div>

                        {{-- Formulário de lançamento de férias --}}
                        <div class="p-6 bg-white rounded shadow">
                            <h2 class="mb-4 text-xl font-semibold">Lançar Férias</h2>
                            @livewire('ferias-form', ['servidorId' => $servidor->id])
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
