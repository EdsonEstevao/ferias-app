<x-app-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-file-import text-blue-500 mr-3"></i>
            Importar Férias via JSON
        </h1>
        <p class="text-gray-600">Importe dados de férias e abono usando formato JSON</p>
    </div>

    <!-- Alert Container -->
    <div id="alert-container" class="mb-6"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-200 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-blue-800">
                            <i class="fas fa-upload mr-2"></i>
                            Dados JSON
                        </h2>
                        <div class="flex space-x-2">
                            <button type="button" onclick="formatJson()"
                                class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-indent mr-1"></i> Format
                            </button>
                            <button type="button" onclick="loadTemplate()"
                                class="px-3 py-2 text-sm bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                                <i class="fas fa-download mr-1"></i> Template
                            </button>
                        </div>
                    </div>
                </div>

                <form id="import-form" class="p-6">
                    @csrf

                    <!-- Ano Exercício -->
                    <div class="mb-6">
                        <label for="ano_exercicio" class="block text-sm font-medium text-gray-700 mb-2">
                            Ano de Exercício *
                        </label>
                        <input type="number" id="ano_exercicio" name="ano_exercicio" value="{{ date('Y') }}"
                            min="2020" max="2030" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">
                            Ano de referência para as férias
                        </p>
                    </div>

                    <!-- JSON Textarea -->
                    <div class="mb-6">
                        <label for="dados_json" class="block text-sm font-medium text-gray-700 mb-2">
                            Cole o JSON aqui *
                        </label>
                        <textarea id="dados_json" name="dados_json" rows="15"
                            placeholder='[{"nome": "FULANO DA SILVA", "matricula": "30/20/1234/56", ...}]' required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm"></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Use o botão "Template" para ver o formato esperado
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit" id="btn-import"
                            class="flex items-center px-6 py-3 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Importar JSON
                        </button>
                        <button type="button" onclick="clearForm()"
                            class="flex items-center px-6 py-3 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-eraser mr-2"></i>
                            Limpar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- JSON Structure -->
            <div class="bg-white rounded-lg shadow-md border border-yellow-200 mb-6">
                <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-yellow-800">
                        <i class="fas fa-code mr-2"></i>
                        Estrutura do JSON
                    </h3>
                </div>
                <div class="p-4">
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-md text-sm overflow-x-auto"><code class="language-json">[
  {
    "nome": "string*",
    "matricula": "string*",
    "periodos_ferias": [
      {
        "inicio": "YYYY-MM-DD*",
        "fim": "YYYY-MM-DD*",
        "dias": "number",
        "url": "string"
      }
    ],
    "periodos_abono": [
      {
        "inicio": "YYYY-MM-DD",
        "fim": "YYYY-MM-DD",
        "dias": "number"
      }
    ]
  }
]</code></pre>

                    <div class="mt-4 p-3 bg-blue-50 rounded-md border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Campos obrigatórios:</strong> nome, matricula, periodos_ferias[].inicio,
                            periodos_ferias[].fim
                        </p>
                    </div>
                </div>
            </div>

            <!-- Advantages -->
            <div class="bg-white rounded-lg shadow-md border border-green-200">
                <div class="bg-green-50 px-4 py-3 border-b border-green-200 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-green-800">
                        <i class="fas fa-star mr-2"></i>
                        Vantagens do JSON
                    </h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-sm text-gray-700">Estrutura clara e validável</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-sm text-gray-700">Fácil de gerar/exportar de outros sistemas</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-sm text-gray-700">Melhor performance</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-sm text-gray-700">Menos propenso a erros de parsing</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                            <span class="text-sm text-gray-700">Fácil debug e validação</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Card -->
    <div id="result-card" class="hidden mt-6">
        <div class="bg-white rounded-lg shadow-md border">
            <div class="bg-green-50 px-6 py-4 border-b border-green-200 rounded-t-lg">
                <h3 class="text-xl font-semibold text-green-800">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Resultado da Importação
                </h3>
            </div>
            <div class="p-6" id="result-content"></div>
        </div>
    </div>
    <!-- Result Card -->
    {{-- <div id="result-card" class="hidden mt-6">
        <div class="bg-white rounded-lg shadow-md border">
            <div id="result-content"></div>
        </div>
    </div> --}}
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/json.min.js"></script>
    <script>
        // Inicializar highlight.js
        hljs.highlightAll();

        document.addEventListener('DOMContentLoaded', function() {
            // Submit do formulário via AJAX
            document.getElementById('import-form').addEventListener('submit', function(e) {
                e.preventDefault();
                importarJson();
            });
        });

        function importarJson() {
            const btn = document.getElementById('btn-import');
            if (!btn) {
                console.error('Botão de importação não encontrado');
                return;
            }

            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Importando...';
            btn.disabled = true;

            const formData = new FormData(document.getElementById('import-form'));

            fetch("{{ route('ferias-import.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    mostrarResultado(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Erro na requisição: ' + error.message, 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        function mostrarResultado(resultado) {
            const resultCard = document.getElementById('result-card');
            const resultContent = document.getElementById('result-content');

            if (!resultCard || !resultContent) {
                console.error('Elementos do resultado não encontrados');
                return;
            }

            resultCard.classList.remove('hidden');

            // Reset classes
            resultCard.className = 'bg-white rounded-lg shadow-md border mt-6';

            if (resultado.success) {
                resultCard.classList.add('border-green-200');

                let html = `
            <div class="bg-green-50 px-6 py-4 border-b border-green-200 rounded-t-lg">
                <h3 class="text-xl font-semibold text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    Importação Concluída
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-green-800">${resultado.message}</h4>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">${resultado.total}</div>
                        <div class="text-sm text-blue-800">Total processado</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">${resultado.importados}</div>
                        <div class="text-sm text-green-800">Importados com sucesso</div>
                    </div>
                </div>
        `;

                if (resultado.erros && resultado.erros.length > 0) {
                    html += `
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-800 mb-3">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                        Erros encontrados (${resultado.erros.length})
                    </h4>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Linha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servidor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Erro</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
            `;

                    resultado.erros.forEach(erro => {
                        html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${erro.linha}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${erro.servidor}</td>
                        <td class="px-4 py-3 text-sm text-red-600">${erro.erro}</td>
                    </tr>
                `;
                    });

                    html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
                }

                html += `</div>`;
                resultContent.innerHTML = html;
            } else {
                resultCard.classList.add('border-red-200');

                resultContent.innerHTML = `
            <div class="bg-red-50 px-6 py-4 border-b border-red-200 rounded-t-lg">
                <h3 class="text-xl font-semibold text-red-800">
                    <i class="fas fa-times-circle mr-2"></i>
                    Erro na Importação
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle text-red-500 text-xl mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-red-800">Erro na importação</h4>
                            <p class="text-red-700 mt-1">${resultado.message}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
            }

            // Rolar para o resultado
            resultCard.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function formatJson() {
            const textarea = document.getElementById('dados_json');
            try {
                const json = JSON.parse(textarea.value);
                textarea.value = JSON.stringify(json, null, 2);
                mostrarAlerta('JSON formatado com sucesso!', 'success');
            } catch (e) {
                mostrarAlerta('JSON inválido: ' + e.message, 'error');
            }
        }

        function loadTemplate() {
            fetch('{{ route('ferias-import.template') }}')
                .then(response => response.json())
                .then(template => {
                    document.getElementById('dados_json').value = JSON.stringify(template, null, 2);
                    mostrarAlerta('Template carregado com sucesso!', 'success');
                })
                .catch(error => {
                    mostrarAlerta('Erro ao carregar template: ' + error.message, 'error');
                });
        }

        function clearForm() {
            document.getElementById('dados_json').value = '';
            document.getElementById('result-card').classList.add('hidden');
            document.getElementById('alert-container').innerHTML = '';
            mostrarAlerta('Formulário limpo!', 'info');
        }

        function mostrarAlerta(mensagem, tipo) {
            const alertContainer = document.getElementById('alert-container');
            const alertId = 'alert-' + Date.now();

            const tipoClasses = {
                success: 'bg-green-50 border-green-200 text-green-800',
                error: 'bg-red-50 border-red-200 text-red-800',
                warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
                info: 'bg-blue-50 border-blue-200 text-blue-800'
            };

            const tipoIcon = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            const alert = `
                <div id="${alertId}" class="flex items-center p-4 mb-4 border rounded-md ${tipoClasses[tipo]} transition-all duration-300">
                    <i class="fas ${tipoIcon[tipo]} mr-3"></i>
                    <span class="flex-1">${mensagem}</span>
                    <button type="button" onclick="document.getElementById('${alertId}').remove()" class="ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            alertContainer.innerHTML = alert;

            // Auto-remove após 5 segundos
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    alertElement.remove();
                }
            }, 5000);
        }
    </script>

    <style>
        /* Custom scrollbar for textarea */
        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>
