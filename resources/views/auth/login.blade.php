<x-guest-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto login-card">
            <div class="gradient-bg text-white p-6 text-center">
                <h1 class="text-2xl font-bold">Bem-vindo de volta</h1>
                <p class="mt-2 opacity-90">Entre em sua conta para continuar</p>
            </div>

            <div class="p-8">
                <!-- Session Status -->
                <div id="sessionStatus" class="mb-6 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm hidden">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span id="statusMessage"></span>
                </div>

                <!-- Error Message -->
                @if (session('errors'))
                    <div id="errorMessage" class="mb-6 p-3 bg-red-50 text-red-700 rounded-lg text-sm error-message">
                        <i class="fas fa-exclamation-triangle mr-2"></i>

                        @foreach ($errors->all() as $error)
                            <span id="errorText">{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email Address -->
                    <div class="input-group">
                        <i class="input-icon fas fa-envelope"></i>
                        <input id="email" class="form-input w-full py-3 px-4 border rounded-lg focus:outline-none"
                            type="email" name="email" placeholder=" "
                            value="{{ old('email') ?? 'admin@empresa.com' }}" required autofocus
                            autocomplete="username" />
                        <label for="email" class="floating-label">E-mail</label>
                        <div class="text-red-500 text-sm mt-1" id="emailError">
                            <!-- Mensagens de erro do e-mail aparecerão aqui -->
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <i class="input-icon fas fa-lock"></i>
                        <input id="password" class="form-input w-full py-3 px-4 border rounded-lg focus:outline-none"
                            type="password" name="password" placeholder=" " value="password" required
                            autocomplete="current-password" />
                        <label for="password" class="floating-label">Senha</label>
                        <span class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                        <div class="text-red-500 text-sm mt-1" id="passwordError">
                            <!-- Mensagens de erro da senha aparecerão aqui -->
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex justify-between items-center mt-6">
                        <label class="remember-label">
                            <input id="remember_me" type="checkbox" class="custom-checkbox" name="remember" />
                            <span class="text-sm text-gray-600">Lembrar-me</span>
                        </label>

                        <a class="text-sm text-purple-600 hover:text-purple-800 transition-colors"
                            href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="btn-login w-full py-3 px-4 text-white font-medium rounded-lg mt-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 pulse">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Entrar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // // Toggle password visibility
        // document.getElementById('togglePassword').addEventListener('click', function() {
        //     const passwordInput = document.getElementById('password');
        //     const icon = this.querySelector('i');

        //     if (passwordInput.type === 'password') {
        //         passwordInput.type = 'text';
        //         icon.classList.remove('fa-eye');
        //         icon.classList.add('fa-eye-slash');
        //     } else {
        //         passwordInput.type = 'password';
        //         icon.classList.remove('fa-eye-slash');
        //         icon.classList.add('fa-eye');
        //     }
        // });

        // // Add floating label functionality
        // document.querySelectorAll('.form-input').forEach(input => {
        //     // Check if input has value on page load
        //     if (input.value) {
        //         input.nextElementSibling.classList.add('active');
        //     }

        //     input.addEventListener('focus', function() {
        //         this.nextElementSibling.classList.add('active');
        //     });

        //     input.addEventListener('blur', function() {
        //         if (!this.value) {
        //             this.nextElementSibling.classList.remove('active');
        //         }
        //     });
        // });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Add floating label functionality
        document.querySelectorAll('.form-input').forEach(input => {
            // Check if input has value on page load
            if (input.value) {
                input.nextElementSibling.classList.add('active');
            }

            input.addEventListener('focus', function() {
                this.nextElementSibling.classList.add('active');
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.nextElementSibling.classList.remove('active');
                }
            });
        });

        // Simulação de erro de login (para demonstração)
        // No Laravel real, isso seria tratado pelo backend
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Esta parte é apenas para demonstração
            // No seu código Laravel real, os erros virão do backend

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');
            const passwordInput = document.getElementById('password');
            const emailInput = document.getElementById('email');

            // Simular validação - REMOVA ISSO na implementação real
            // No Laravel real, os erros virão da resposta do servidor
            if (password === 'senhaerrada' || password === '') {
                e.preventDefault(); // Remove esta linha na implementação real

                // Mostrar mensagem de erro
                errorMessage.classList.add('show');

                // Adicionar classes de erro aos inputs
                passwordInput.classList.add('error', 'shake');
                emailInput.classList.add('error');

                // Remover animação shake após execução
                setTimeout(() => {
                    passwordInput.classList.remove('shake');
                }, 500);

                // Esconder mensagem de status se estiver visível
                document.getElementById('sessionStatus').classList.add('hidden');

                // Na implementação real, você não precisa deste preventDefault
                // pois o Laravel irá redirecionar de volta com os erros
            } else {
                // Limpar erros se as credenciais estiverem corretas
                errorMessage.classList.remove('show');
                passwordInput.classList.remove('error');
                emailInput.classList.remove('error');
            }
        });

        // Para integração com Laravel - detectar erros da sessão
        document.addEventListener('DOMContentLoaded', function() {
            // Esta função detectaria erros retornados do Laravel
            // Em um ambiente real, o Laravel injeta os erros na página

            // Simular um erro vindo do Laravel (remova na implementação real)
            const hasError = false; // Isso seria determinado pelo Laravel

            if (hasError) {
                const errorMessage = document.getElementById('errorMessage');
                const passwordInput = document.getElementById('password');
                const emailInput = document.getElementById('email');

                errorMessage.classList.add('show');
                passwordInput.classList.add('error');
                emailInput.classList.add('error');
            }

            // Mostrar mensagem de status se existir
            const statusMessage = document.getElementById('statusMessage').textContent;
            if (statusMessage && statusMessage.trim() !== '') {
                document.getElementById('sessionStatus').classList.remove('hidden');
            }
        });

        // Limpar erros quando o usuário começar a digitar
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('error');
            document.getElementById('errorMessage').classList.remove('show');
        });

        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('error');
            document.getElementById('errorMessage').classList.remove('show');
        });
    </script>
</x-guest-layout>
