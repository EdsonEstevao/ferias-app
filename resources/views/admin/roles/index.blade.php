<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-5xl p-6 mx-auto">
                        <h2 class="mb-4 text-2xl font-bold">Gerenciar Roles e Permissões</h2>

                        {{-- Criar nova Role --}}
                        <form method="POST" action="{{ route('admin.roles.store') }}" class="mb-6">
                            @csrf
                            <div class="flex items-center gap-2">
                                <input type="text" name="name" placeholder="Nome da Role"
                                    class="w-full p-2 border rounded" required>
                                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">Criar</button>
                            </div>
                        </form>

                        {{-- Listar Roles e Permissões --}}
                        @foreach ($roles as $role)
                            <div class="p-4 mb-4 border rounded">
                                <h3 class="mb-2 text-lg font-semibold">{{ $role->name }}</h3>

                                <form method="POST" action="{{ route('admin.roles.syncPermissions', $role) }}">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-2 md:grid-cols-3">
                                        @foreach ($permissions as $permission)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                                                    class="border-gray-300 rounded">
                                                <span>{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="px-4 py-2 mt-4 text-white bg-green-600 rounded">Salvar
                                        Permissões</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
