<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Painel Gestor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-center">
                    <!-- Importar CSV -->

                    <div class="w-full max-w-lg bg-white shadow-xl rounded-lg p-8">
                        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">
                            Importar CSV de Férias 📥
                        </h1>

                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                <p class="font-bold">Sucesso!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                <p class="font-bold">Ops, encontramos alguns erros:</p>
                                <ul class="list-disc ml-5 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('ferias.import.csv') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf

                            <div>
                                <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Selecione o arquivo CSV
                                </label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v-8m0 8h-8m0-8h8m-12 4h.02M24 24h.02M12 30h.02M12 36h.02M30 30h.02M30 36h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="csv_file"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Clique para carregar</span>
                                                <input id="csv_file" name="csv_file" type="file" class="sr-only">
                                            </label>
                                            <p class="pl-1">ou arraste e solte</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            Apenas arquivos CSV ou TXT (máximo 10MB)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Processar e Importar Dados
                            </button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
