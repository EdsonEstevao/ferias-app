<x-app-layout>

    <div class="container mx-auto">
        <div class="max-w-2xl p-6 mx-auto bg-white rounded-lg shadow-md">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center w-10 h-10 mr-3 bg-orange-100 rounded-full">
                    <span class="text-xl">↩️</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Reverter Abono Pecuniário</h1>
            </div>

            <div class="p-4 mb-6 rounded-lg bg-orange-50">
                <h2 class="mb-2 text-lg font-semibold text-orange-800">Informações do Abono</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Servidor:</span>
                        <p class="text-gray-700">{{ $periodo->ferias->servidor->nome }}</p>
                    </div>
                    <div>
                        <span class="font-medium">Matrícula:</span>
                        <p class="text-gray-700">{{ $periodo->ferias->servidor->matricula }}</p>
                    </div>
                    <div>
                        <span class="font-medium">Período Convertido:</span>
                        <p class="text-gray-700">{{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                            {{ date('d/m/Y', strtotime($periodo->fim)) }} </p>
                    </div>
                    <div>
                        <span class="font-medium">Dias Convertidos:</span>
                        <p class="text-gray-700">{{ $periodo->dias }} dias</p>
                    </div>
                    <div class="col-span-2">
                        <span class="font-medium">Data da Conversão:</span>
                        <p class="text-gray-700">{{ $periodo->data_conversao_abono->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('ferias.reverter-abono') }}" method="POST">
                @csrf
                <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">

                <div class="mb-6">
                    <label for="justificativa" class="block mb-2 text-sm font-medium text-gray-700">
                        Justificativa da Reversão *
                    </label>
                    <textarea name="justificativa" id="justificativa" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                        required placeholder="Descreva o motivo da reversão do abono pecuniário..."></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        ↩️ Reverter Abono
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
