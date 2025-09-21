<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-5xl p-6 mx-auto">
                        <h2 class="mb-4 text-2xl font-bold">Gerenciar Usuários</h2>

                        {{-- Criar novo usuário --}}
                        <form method="POST" action="{{ route('admin.users.store') }}" class="mb-6 space-y-4">
                            @csrf
                            <input type="text" name="name" placeholder="Nome" class="w-full p-2 border rounded"
                                required>
                            <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded"
                                required>
                            <input type="password" name="password" placeholder="Senha" class="w-full p-2 border rounded"
                                required>

                            <select name="role" class="w-full p-2 border rounded">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">Criar
                                Usuário</button>
                        </form>

                        {{-- Lista de usuários --}}
                        @foreach ($users as $user)
                            <div class="p-4 mb-4 border rounded">
                                <h3 class="text-lg font-semibold">{{ $user->name }} <span
                                        class="text-sm text-gray-500">({{ $user->email }})</span></h3>

                                <form method="POST" action="{{ route('admin.users.updateRoles', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-2 gap-2 mt-2 md:grid-cols-3">
                                        @foreach ($roles as $role)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}
                                                    class="border-gray-300 rounded">
                                                <span>{{ $role->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit"
                                        class="px-4 py-2 mt-4 text-white bg-green-600 rounded">Atualizar
                                        Roles</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
