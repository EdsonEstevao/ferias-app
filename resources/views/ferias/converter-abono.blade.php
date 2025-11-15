<x-app-layout>

    <div class="container mx-auto">
        <div class="max-w-2xl p-6 mx-auto bg-white rounded-lg shadow-md">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center w-10 h-10 mr-3 bg-purple-100 rounded-full">
                    <span class="text-xl">💰</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Converter para Abono Pecuniário</h1>
            </div>

            <div class="p-4 mb-6 rounded-lg bg-purple-50">
                <h2 class="mb-2 text-lg font-semibold text-purple-800">Informações do Período</h2>
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
                        <span class="font-medium">Período:</span>
                        <p class="text-gray-700">{{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                            {{ date('d/m/Y', strtotime($periodo->fim)) }}</p>
                    </div>
                    <div>
                        <span class="font-medium">Dias:</span>
                        <p class="text-gray-700">{{ $periodo->dias }} dias</p>
                    </div>
                    <div class="col-span-2">
                        <span class="font-medium">Situação:</span>
                        <p class="text-gray-700">{{ $periodo->situacao }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('ferias.converter-abono') }}" method="POST">
                @csrf
                <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">
                <div class="mb-6">
                    <label for="title_abono" class="block mb-2 text-sm font-medium text-gray-700">
                        Portaria do Abono Pecuniário *
                    </label>
                    <input type="text" name="title_abono" id="title_abono"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required placeholder="Portaria de abono pecuniário...">
                </div>
                <div class="mb-6">
                    <label for="url_abono" class="block mb-2 text-sm font-medium text-gray-700">
                        URL do Abono Pecuniário *
                    </label>
                    <input type="url" name="url_abono" id="url_abono"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required placeholder="https://www.example.com/abono.pdf">
                </div>

                <div class="mb-6">
                    <label for="justificativa" class="block mb-2 text-sm font-medium text-gray-700">
                        Justificativa da Conversão *
                    </label>
                    <textarea name="justificativa" id="justificativa" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required placeholder="Descreva o motivo da conversão para abono pecuniário..."></textarea>
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" name="conversao_parcial" id="conversao_parcial"
                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label for="conversao_parcial" class="block ml-2 text-sm text-gray-700">
                        Conversão Parcial
                    </label>
                </div>

                <div id="parcial_fields" class="hidden p-4 mb-6 border border-gray-200 rounded-lg">
                    <div class="mb-4">
                        <label for="dias_converter" class="block mb-2 text-sm font-medium text-gray-700">
                            Dias para Converter (máximo: {{ $periodo->dias }})
                        </label>
                        <input type="number" name="dias_converter" id="dias_converter" min="1"
                            max="{{ $periodo->dias }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Informe quantos dias converter">
                        <p class="mt-1 text-xs text-gray-500">
                            Os dias restantes serão mantidos como férias normais
                        </p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        💰 Converter para Abono
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conversaoParcial = document.getElementById('conversao_parcial');
            const parcialFields = document.getElementById('parcial_fields');

            conversaoParcial.addEventListener('change', function() {
                parcialFields.classList.toggle('hidden', !this.checked);
            });
        });
    </script>

</x-app-layout>
