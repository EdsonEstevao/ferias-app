<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold leading-tight text-gray-800 dark:text-gray-200">
                Gerenciamento de Usuários
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Alertas e Notificações -->
            @if (session('success'))
                <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium">Erro ao processar solicitação</h3>
                            <ul class="mt-2 text-sm list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-6xl mx-auto space-y-8">
                        <!-- Card de Criação de Usuário -->
                        <div
                            class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Editar Usuário
                            </h3>

                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome
                                            Completo</label>
                                        <input type="text" id="name" name="name" value="{{ $user->name }}"
                                            placeholder="Digite o nome completo"
                                            class="w-full p-3 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            required>
                                    </div>
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                                        <input type="email" id="email" name="email" value="{{ $user->email }}"
                                            placeholder="Digite o e-mail"
                                            class="w-full p-3 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    @if (Auth::id() === $user->id)
                                        <div>
                                            <label for="password" value=""
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                                            <input type="password" id="password" name="password"
                                                placeholder="Digite a senha"
                                                class="w-full p-3 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        </div>
                                    @endif

                                    <div>
                                        <label for="role"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Perfil</label>
                                        <select id="role" name="role"
                                            class="w-full p-3 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                                            @foreach ($roles as $role)
                                                @foreach ($user->roles as $userRole)
                                                    <option value="{{ $role->name }}"
                                                        {{ $userRole->id == $role->id ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <!-- Botão Voltar -->
                                    <a href="{{ route('admin.users.index') }}"
                                        class="px-6 py-3 mr-2 text-white transition duration-200 bg-gray-600 rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 focus:outline-none">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg>
                                            <span>Voltar</span>
                                        </div>
                                    </a>

                                    <button type="submit"
                                        class="px-6 py-3 text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            <span>Salvar</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
