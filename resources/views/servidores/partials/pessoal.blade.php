<!-- resources/views/servidores/partials/pessoal.blade.php -->
<div class="space-y-8">
    <!-- Cabeçalho da Seção -->
    <div class="flex items-center pb-6 space-x-4 border-b border-gray-200">
        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-xl font-semibold text-gray-900">Dados Pessoais</h3>
            <p class="mt-1 text-sm text-gray-600">Informações básicas de identificação do servidor</p>
        </div>
    </div>

    <!-- Grid de Campos -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Nome -->
        <div class="space-y-2">
            <label for="nome" class="flex items-center text-sm font-medium text-gray-700">
                <span>Nome Completo</span>
                <span class="ml-1 text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <input type="text" name="nome" id="nome"
                    value="{{ isset($servidor) ? $servidor->nome : old('nome') }}"
                    class="form-input pl-10 w-full px-4 py-3 border rounded-lg focus:ring-2  transition-colors duration-200 @error('nome') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    placeholder="Digite o nome completo" required autofocus>
            </div>
            @error('nome')
                <div class="flex items-center mt-1 space-x-1 text-sm text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <input type="email" name="email" id="email"
                    value="{{ isset($servidor) ? $servidor->email : old('email') }}"
                    class="form-input pl-10 w-full px-4 py-3 border  rounded-lg focus:ring-2 transition-colors duration-200 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    placeholder="servidor@exemplo.com">
            </div>
            @error('email')
                <div class="flex items-center mt-1 space-x-1 text-sm text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- CPF -->
        {{-- <div class="space-y-2">
            <label for="cpf" class="flex items-center text-sm font-medium text-gray-700">
                <span>CPF</span>
                <span class="ml-1 text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <input type="text" name="cpf" id="cpf"
                    value="{{ isset($servidor) ? $servidor->cpf : old('cpf') }}"
                    class="cpf-mask form-input pl-10 w-full px-4 py-3 border  rounded-lg focus:ring-2  transition-colors duration-200 @error('cpf') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    placeholder="000.000.000-00" required>
            </div>
            @error('cpf')
                <div class="flex items-center mt-1 space-x-1 text-sm text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div> --}}

        <!-- Matrícula -->
        <div class="space-y-2">
            <label for="matricula" class="flex items-center text-sm font-medium text-gray-700">
                <span>Matrícula</span>
                <span class="ml-1 text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <input type="text" name="matricula" id="matricula"
                    value="{{ isset($servidor) ? $servidor->matricula : old('matricula') }}"
                    class="form-input pl-10 w-full px-4 py-3 border rounded-lg focus:ring-2  transition-colors duration-200 @error('matricula') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    placeholder="Número da matrícula" required>
            </div>
            @error('matricula')
                <div class="flex items-center mt-1 space-x-1 text-sm text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Telefone -->
        <div class="space-y-2">
            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                </div>
                <input type="text" name="telefone" id="telefone"
                    value="{{ isset($servidor) ? $servidor->telefone : old('telefone') }}"
                    class="form-input pl-10 w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500  transition-colors duration-200 @error('telefone') border-red-500  focus:border-red-500  @else border  @enderror"
                    placeholder="(00) 00000-0000">
            </div>
            @error('telefone')
                <div class="flex items-center mt-1 space-x-1 text-sm text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Data de Nascimento -->
        <div class="space-y-2">
            <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <input type="text" name="data_nascimento" id="data_nascimento"
                    value="{{ isset($vinculo?->data_nascimento) ? date('d/m/Y', strtotime($vinculo->data_nascimento)) : old('data_nascimento') }}"
                    class="date-mask form-input pl-10 w-full px-4 py-3 border  rounded-lg focus:ring-2  transition-colors duration-200 @error('data_nascimento') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    placeholder="dd/mm/aaaa">
            </div>
            @error('data_nascimento')
                <div class="flex items-center mt-1 space-x-1 text-sm text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="p-4 border border-blue-200 bg-blue-50 rounded-xl">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium text-blue-900">Informações importantes</h4>
                <ul class="mt-2 space-y-1 text-sm text-blue-800">
                    <li>• Campos marcados com <span class="text-red-500">*</span> são obrigatórios</li>
                    <li>• Certifique-se de que o CPF está correto antes de prosseguir</li>
                    <li>• A matrícula deve ser única para cada servidor</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para telefone
        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);

                if (value.length > 10) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length > 6) {
                    value = value.replace(/(\d{2})(\d{4})(\d+)/, '($1) $2-$3');
                } else if (value.length > 2) {
                    value = value.replace(/(\d{2})(\d+)/, '($1) $2');
                }

                e.target.value = value;
            });
        }

        // Validação de email em tempo real
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value;
                if (email && !this.validarEmail(email)) {
                    this.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                } else {
                    this.classList.remove('border-red-500', 'focus:ring-red-500',
                        'focus:border-red-500');
                }
            });
        }
    });

    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
</script>

<style>
    .form-input {
        transition: all 0.2s ease-in-out;
    }

    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }

    .form-input:invalid:not(:focus):not(:placeholder-shown) {
        border-color: #ef4444;
    }
</style>
