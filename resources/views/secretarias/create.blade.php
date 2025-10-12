<x-app-layout>
    <div class="max-w-3xl p-8 mx-auto mt-10 bg-white shadow-lg rounded-xl ring-1 ring-gray-200">

        <h1 class="mb-8 text-3xl font-extrabold tracking-tight text-gray-900">
            📌 Adicionar Secretaria
        </h1>

        @if (session('success'))
            <div class="flex items-center p-4 mb-6 space-x-3 text-green-800 bg-green-100 rounded">
                <svg class="flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('secretarias.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nome" class="block mb-2 text-sm font-semibold text-gray-700">Nome da Secretaria</label>
                <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                    class="block w-full rounded-md border  px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror"
                    placeholder="Digite o nome da secretaria" required>
                @error('nome')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sigla" class="block mb-2 text-sm font-semibold text-gray-700">Sigla</label>
                <input type="text" name="sigla" id="sigla" value="{{ old('sigla') }}"
                    class="block w-full rounded-md border  px-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('sigla') border-red-500 @enderror"
                    placeholder="Ex: SEC" required>
                @error('sigla')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="secretaria_origem_id" class="block mb-2 text-sm font-semibold text-gray-700">
                    Secretaria de Origem (opcional)
                </label>
                <select name="secretaria_origem_id" id="secretaria_origem_id"
                    class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" class="text-gray-400">-- Nenhuma --</option>
                    @foreach ($secretarias as $s)
                        <option value="{{ $s->id }}"
                            {{ old('secretaria_origem_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->sigla }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('secretarias.index') }}"
                    class="flex items-center space-x-2 text-indigo-600 transition hover:text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Voltar</span>
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 font-semibold text-white transition bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Salvar Secretaria
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
