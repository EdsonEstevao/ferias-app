<x-app-layout>

    <div class="container px-4 py-8 mx-auto">
        <div class="max-w-4xl mx-auto">
            <!-- Cabeçalho -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Importar Servidores via JSON</h1>
                <p class="text-gray-600">Importe servidores em lote através de arquivo JSON</p>
            </div>

            <!-- Card Principal -->
            <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <!-- Upload Section -->
                <div class="mb-8">
                    <form action="{{ route('servidores.import.preview') }}" method="POST" enctype="multipart/form-data"
                        id="importForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Download Template -->
                            <div class="flex items-center justify-between p-4 rounded-lg bg-blue-50">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-900">📋 Passo 1: Baixe o template JSON
                                    </h3>
                                    <p class="text-sm text-blue-700">Use nosso template para formatar corretamente o
                                        JSON</p>
                                </div>
                                <a href="{{ route('servidores.import.template.json') }}"
                                    class="inline-flex items-center px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Baixar Template JSON
                                </a>
                            </div>

                            <!-- Upload File -->
                            <div>
                                <h3 class="mb-3 text-lg font-semibold text-gray-900">📤 Passo 2: Selecione o arquivo
                                    JSON</h3>
                                <div
                                    class="p-8 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400">
                                    <input type="file" name="arquivo_json" id="arquivo_json" accept=".json,.txt"
                                        class="hidden" required>
                                    <label for="arquivo_json" class="block cursor-pointer">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mb-2 text-lg text-gray-700">Clique para selecionar o arquivo JSON</p>
                                        <p class="text-sm text-gray-500">Arquivos .json ou .txt até 10MB</p>
                                    </label>
                                </div>
                                <div id="fileName" class="hidden mt-2 text-sm text-gray-600"></div>
                            </div>

                            <!-- JSON Preview -->
                            <div id="jsonPreview" class="hidden">
                                <h4 class="mb-2 font-semibold text-gray-900 text-md">Prévia do JSON:</h4>
                                <pre class="p-4 overflow-auto text-sm bg-gray-100 rounded-lg max-h-40"></pre>
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verificar Dados JSON
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Preview Section -->
                @if (isset($preview))
                    @include('servidores.partials.import-preview')
                @endif
            </div>

            <!-- Informações JSON -->
            <div class="p-6 rounded-lg bg-purple-50">
                <h3 class="mb-3 text-lg font-semibold text-purple-900">🎯 Estrutura do JSON</h3>
                <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                    <div>
                        <h4 class="mb-2 font-medium text-purple-800">📝 Formato Esperado:</h4>
                        <pre class="p-3 overflow-auto text-xs bg-white rounded">
[
  {
    "lotacao": "CASA CIVIL",
    "servidor": "NOME DO SERVIDOR",
    "matricula": "300000001",
    "cargo": "Cargo do servidor",
    "sexo": "Masculino",
    "tipo_servidor": ["interno", "cedido"],
    "departamento": "Departamento",
    "email": "email@example.com",
    "telefone": "61999999999",
    "cpf": "12345678901"
  }
]</pre>
                    </div>
                    <div>
                        <h4 class="mb-2 font-medium text-purple-800">💡 Dicas:</h4>
                        <ul class="space-y-2 text-purple-700">
                            <li>• <strong>tipo_servidor</strong> deve ser um array: <code>["interno"]</code> ou
                                <code>["interno","cedido"]</code></li>
                            <li>• Campos obrigatórios: lotacao, servidor, matricula, cargo, sexo, tipo_servidor</li>
                            <li>• Matrículas duplicadas atualizam o servidor existente</li>
                            <li>• Cada objeto cria um novo vínculo funcional</li>
                            <li>• Tipos permitidos: federal, cedido, interno, disponibilizado, regional</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('arquivo_json');
            const fileName = document.getElementById('fileName');
            const jsonPreview = document.getElementById('jsonPreview');
            const previewContent = jsonPreview.querySelector('pre');

            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    fileName.textContent = 'Arquivo selecionado: ' + file.name;
                    fileName.classList.remove('hidden');

                    // Ler e mostrar preview do JSON
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            const json = JSON.parse(e.target.result);
                            previewContent.textContent = JSON.stringify(json, null, 2);
                            jsonPreview.classList.remove('hidden');
                        } catch (error) {
                            previewContent.textContent = '❌ Erro ao ler JSON: ' + error.message;
                            jsonPreview.classList.remove('hidden');
                        }
                    };
                    reader.readAsText(file);
                }
            });

            // Mostrar loading durante o processamento
            document.getElementById('importForm').addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                button.disabled = true;
                button.innerHTML =
                    '<svg class="w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processando JSON...';
            });
        });
    </script>

</x-app-layout>
