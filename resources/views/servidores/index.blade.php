<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Lista de Servidores
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Listas de servidores
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nome
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Matricula
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Departamento
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Ação
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servidores as $servidor)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $servidor->nome }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $servidor->matricula }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $servidor->departamento }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('gestor.ferias.painel', ['servidorId' => $servidor->id]) }}"
                                            class="text-blue-600 hover:underline">
                                            📅 Ver Férias
                                        </a>
                                        <!-- marcar ferias -->
                                        <a href="{{ route('ferias.create', ['servidorId' => $servidor->id]) }}"
                                            class="text-blue-600 hover:underline">
                                            📅 Marcar Férias
                                        </a>
                                        <!--interromper ferias -->
                                        <a href="{{ route('ferias.interromper.periodo', ['servidorId' => $servidor->id]) }}"
                                            class="text-blue-600 hover:underline">
                                            📅 Interromper Férias
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
