<!-- Cadastro de Servidores --->
<x-app-layout>

    <div class="container px-4 py-8 mx-auto">
        <div class="max-w-4xl mx-auto">
            <!-- Cabeçalho -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Cadastrar Novo Servidor</h1>
                <p class="text-gray-600">Preencha os dados básicos do servidor</p>
            </div>

            <!-- Formulário -->
            <form action="{{ route('servidores.store') }}" method="POST">
                @csrf

                <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h2 class="mb-6 text-xl font-semibold text-gray-900">👤 Dados Pessoais</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Nome Completo -->
                        <div class="col-span-2">
                            <label for="nome" class="block mb-2 text-sm font-medium text-gray-700">
                                👋 Nome Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Digite o nome completo do servidor" required>
                            @error('nome')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Matrícula -->
                        <div>
                            <label for="matricula" class="block mb-2 text-sm font-medium text-gray-700">
                                🆔 Matrícula <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="matricula" id="matricula" value="{{ old('matricula') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Número da matrícula" required>
                            @error('matricula')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CPF -->
                        <div>
                            <label for="cpf" class="block mb-2 text-sm font-medium text-gray-700">
                                📄 CPF
                            </label>
                            <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cpf-mask"
                                placeholder="000.000.000-00">
                            @error('cpf')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                                📧 Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="email@exemplo.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="telefone" class="block mb-2 text-sm font-medium text-gray-700">
                                📞 Telefone
                            </label>
                            <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 phone-mask"
                                placeholder="(61) 99999-9999">
                            @error('telefone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('servidores.index') }}"
                        class="inline-flex items-center px-6 py-3 text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
                        ← Cancelar
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Cadastrar Servidor
                    </button>
                </div>
            </form>

            <!-- Informações -->
            <div class="p-6 mt-8 rounded-lg bg-blue-50">
                <h3 class="mb-3 text-lg font-semibold text-blue-900">💡 Informações Importantes</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li>• Após cadastrar o servidor, você poderá adicionar o vínculo funcional (nomeação)</li>
                    <li>• A matrícula deve ser única no sistema</li>
                    <li>• O CPF é opcional, mas recomendado para evitar duplicidades</li>
                    <li>• Campos marcados com <span class="text-red-500">*</span> são obrigatórios</li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        // Máscaras para os campos
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara de CPF
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length <= 11) {
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                        e.target.value = value;
                    }
                });
            }

            // Máscara de telefone
            const phoneInput = document.getElementById('telefone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length <= 11) {
                        value = value.replace(/(\d{2})(\d)/, '($1) $2');
                        value = value.replace(/(\d{5})(\d)/, '$1-$2');
                        e.target.value = value;
                    }
                });
            }

            // Verificar matrícula única em tempo real
            const matriculaInput = document.getElementById('matricula');
            if (matriculaInput) {
                let timeout;
                matriculaInput.addEventListener('input', function(e) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        verificarMatricula(e.target.value);
                    }, 500);
                });

                async function verificarMatricula(matricula) {
                    if (matricula.length < 3) return;

                    try {
                        const response = await fetch(`/api/verificar-matricula/${matricula}`);
                        const data = await response.json();

                        const feedback = document.getElementById('matricula-feedback') ||
                            createFeedbackElement(matriculaInput);

                        if (data.existe) {
                            feedback.className = 'text-red-600 text-sm mt-1';
                            feedback.textContent = '❌ Matrícula já cadastrada no sistema';
                        } else {
                            feedback.className = 'text-green-600 text-sm mt-1';
                            feedback.textContent = '✅ Matrícula disponível';
                        }
                    } catch (error) {
                        console.error('Erro ao verificar matrícula:', error);
                    }
                }

                function createFeedbackElement(input) {
                    const feedback = document.createElement('p');
                    feedback.id = 'matricula-feedback';
                    input.parentNode.appendChild(feedback);
                    return feedback;
                }
            }
        });
    </script>
</x-app-layout>
