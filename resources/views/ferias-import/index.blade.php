<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 rounded-t-lg">
                    <h1 class="text-2xl font-bold text-blue-800">
                        <i class="fas fa-history mr-2"></i>
                        Histórico de Importações
                    </h1>
                </div>

                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                        <i class="fas fa-tools text-blue-500 text-4xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-blue-800 mb-2">Funcionalidade em Desenvolvimento</h3>
                        <p class="text-blue-700 mb-4">Em breve você poderá visualizar o histórico de importações
                            realizadas.</p>

                        <a href="{{ route('ferias-import.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 transition-colors">
                            <i class="fas fa-file-import mr-2"></i>
                            Fazer Nova Importação
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
