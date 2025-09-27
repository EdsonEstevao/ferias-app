{{-- Editar Secretaria --}}
<x-app-layout>
    <div class="max-w-2xl p-6 mx-auto mt-6 bg-white rounded shadow">
        <h2 class="mb-4 text-xl font-bold text-gray-800">✏️ Editar Secretaria</h2>

        @if (session('success'))
            <div class="p-3 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('secretarias.update', $secretaria->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Secretaria</label>
                <input type="text" name="nome" id="nome" value="{{ old('nome', $secretaria->nome) }}"
                    class="mt-1 block w-full border rounded px-3 py-2 @error('nome') border-red-500 @enderror" required>
                @error('nome')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sigla" class="block text-sm font-medium text-gray-700">Sigla</label>
                <input type="text" name="sigla" id="sigla" value="{{ old('sigla', $secretaria->sigla) }}"
                    class="mt-1 block w-full border rounded px-3 py-2 @error('sigla') border-red-500 @enderror"
                    required>
                @error('sigla')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="secretaria_origem_id" class="block text-sm font-medium text-gray-700">Secretaria de
                    Origem</label>
                <select name="secretaria_origem_id" id="secretaria_origem_id"
                    class="block w-full px-3 py-2 mt-1 border rounded">
                    <option value="">-- Nenhuma --</option>
                    @foreach ($secretarias as $s)
                        @if ($s->id !== $secretaria->id)
                            <option value="{{ $s->id }}"
                                {{ old('secretaria_origem_id', $secretaria->secretaria_origem_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->sigla }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('secretarias.index') }}" class="text-sm text-gray-600 hover:underline">← Voltar</a>
                <button type="submit" class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                    Atualizar Secretaria
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
