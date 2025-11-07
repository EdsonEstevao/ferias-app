<x-app-layout>
    <div class="container px-4 py-8 mx-auto">
        <div class="max-w-6xl mx-auto">
            <!-- Cabeçalho -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Nomeação de Servidor</h1>
                <p class="text-gray-600">Cadastrar vínculo funcional do servidor</p>
            </div>

            <!-- Card do Servidor -->
            {{-- <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h2 class="mb-4 text-xl font-semibold text-gray-900">👤 Servidor</h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nome Completo</label>
                        <p class="text-lg font-medium text-gray-900">{{ $servidor->nome }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Matrícula</label>
                        <p class="font-mono text-gray-900">{{ $servidor->matricula }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">CPF</label>
                        <p class="text-gray-900">{{ $servidor->cpf ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div> --}}

            <!-- Formulário de Nomeação -->
            @include('servidores.nomeacao.partials.nomeacao-form', [
                'servidor' => $servidor,
                'secretarias' => $secretarias,
                'cargos' => $cargos,
                'action' => route('servidores.nomeacao.store', $servidor),
                'method' => 'POST',
            ])
            {{-- <form action="{{ route('servidores.nomeacao.store', $servidor) }}" method="POST">
                @csrf

                <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h2 class="mb-6 text-xl font-semibold text-gray-900">🏢 Dados da Nomeação</h2>

                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- Coluna 1 - Dados Principais -->
                        <div class="space-y-6">
                            <!-- Secretaria -->
                            <div class="group">
                                <label for="secretaria_id"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Secretaria <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <select name="secretaria_id" id="secretaria_id"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10"
                                        required>
                                        <option value="" class="text-gray-400">Selecione uma secretaria</option>
                                        @foreach ($secretarias as $secretaria)
                                            <option value="{{ $secretaria->id }}"
                                                {{ old('secretaria_id') == $secretaria->id ? 'selected' : '' }}>
                                                {{ $secretaria->sigla }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('secretaria_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cargo -->
                            <div class="group">
                                <label for="cargo_id"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Cargo <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <select name="cargo_id" id="cargo_id"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10"
                                        required>
                                        <option value="" class="text-gray-400">Selecione a secretaria primeiro
                                        </option>
                                        @foreach ($cargos as $cargo)
                                            <option value="{{ $cargo->id }}"
                                                {{ old('cargo_id') == $cargo->id ? 'selected' : '' }}>
                                                {{ $cargo->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('cargo_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lotação -->
                            <div class="group">
                                <label for="lotacao"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lotação <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="lotacao" id="lotacao" value="{{ old('lotacao') }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-gray-400"
                                    placeholder="Local de lotação" required>
                                @error('lotacao')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna 2 - Dados do Servidor -->
                        <div class="space-y-6">
                            <!-- Tipo de Servidor -->
                            <div class="group">
                                <label class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Tipo de Servidor <span class="text-red-400">*</span>
                                </label>
                                <div class="space-y-2">
                                    @foreach (['federal', 'cedido', 'interno', 'disponibilizado', 'regional'] as $tipo)
                                        <label class="flex items-center gap-2 cursor-pointer group/checkbox">
                                            <input type="checkbox" name="tipo_servidor[]"
                                                value="{{ $tipo }}"
                                                {{ in_array($tipo, old('tipo_servidor', [])) ? 'checked' : '' }}
                                                class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                                            <span
                                                class="text-sm text-gray-700 capitalize transition-colors group-hover/checkbox:text-gray-900">
                                                {{ $tipo }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('tipo_servidor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Selecione um ou mais tipos</p>
                            </div>

                            <!-- Sexo -->
                            <div class="group">
                                <label for="sexo"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                    </svg>
                                    Sexo <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <select name="sexo" id="sexo"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10"
                                        required>
                                        <option value="" class="text-gray-400">Selecione</option>
                                        @foreach (['Masculino', 'Feminino', 'Outro'] as $sexo)
                                            <option value="{{ $sexo }}"
                                                {{ old('sexo') == $sexo ? 'selected' : '' }}>
                                                {{ $sexo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('sexo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- É Diretor? -->
                            <div class="group">
                                <label class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    É Diretor?
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer group/radio">
                                        <input type="radio" name="is_diretor" value="1"
                                            {{ old('is_diretor', 0) == 1 ? 'checked' : '' }}
                                            class="w-4 h-4 text-yellow-500 border-gray-300 focus:ring-yellow-500">
                                        <span
                                            class="text-sm text-gray-700 transition-colors group-hover/radio:text-gray-900">Sim</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group/radio">
                                        <input type="radio" name="is_diretor" value="0"
                                            {{ old('is_diretor', 0) == 0 ? 'checked' : '' }}
                                            class="w-4 h-4 text-yellow-500 border-gray-300 focus:ring-yellow-500">
                                        <span
                                            class="text-sm text-gray-700 transition-colors group-hover/radio:text-gray-900">Não</span>
                                    </label>
                                </div>
                                @error('is_diretor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna 3 - Dados Adicionais -->
                        <div class="space-y-6">
                            <!-- Departamento -->
                            <div class="group">
                                <label for="departamento"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    Departamento
                                </label>
                                <input type="text" name="departamento" id="departamento"
                                    value="{{ old('departamento') }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm hover:border-gray-400"
                                    placeholder="Departamento de lotação">
                                @error('departamento')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Local de Trabalho -->
                            <div class="group">
                                <label for="local_trabalho"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Local de Trabalho
                                </label>
                                <input type="text" name="local_trabalho" id="local_trabalho"
                                    value="{{ old('local_trabalho') }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 shadow-sm hover:border-gray-400"
                                    placeholder="Local físico de trabalho">
                                @error('local_trabalho')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Data de Nomeação -->
                            <div class="group">
                                <label for="data_movimentacao"
                                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Data de Nomeação <span class="text-red-400">*</span>
                                </label>
                                <input type="date" name="data_movimentacao" id="data_movimentacao"
                                    value="{{ old('data_movimentacao', now()->format('Y-m-d')) }}"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 shadow-sm hover:border-gray-400"
                                    required>
                                @error('data_movimentacao')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentos da Nomeação -->
                <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h2 class="mb-6 text-xl font-semibold text-gray-900">📋 Documentos da Nomeação</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Processo de Implantação -->
                        <div class="group">
                            <label for="processo_implantacao" class="block mb-2 text-sm font-medium text-gray-700">
                                📄 Processo de Implantação
                            </label>
                            <input type="text" name="processo_implantacao" id="processo_implantacao"
                                value="{{ old('processo_implantacao') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nº do processo">
                            @error('processo_implantacao')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nº do Memorando -->
                        <div class="group">
                            <label for="numero_memorando" class="block mb-2 text-sm font-medium text-gray-700">
                                📨 Nº do Memorando
                            </label>
                            <input type="text" name="numero_memorando" id="numero_memorando"
                                value="{{ old('numero_memorando') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Número do memorando">
                            @error('numero_memorando')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ato Normativo -->
                        <div class="group md:col-span-2">
                            <label for="ato_normativo" class="block mb-2 text-sm font-medium text-gray-700">
                                ⚖️ Ato Normativo
                            </label>
                            <input type="text" name="ato_normativo" id="ato_normativo"
                                value="{{ old('ato_normativo') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ato normativo da nomeação">
                            @error('ato_normativo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="group md:col-span-2">
                            <label for="observacao" class="block mb-2 text-sm font-medium text-gray-700">
                                💬 Observações
                            </label>
                            <textarea name="observacao" id="observacao" rows="4"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Observações complementares...">{{ old('observacao') }}</textarea>
                            @error('observacao')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('servidores.show', $servidor) }}"
                        class="inline-flex items-center px-6 py-3 text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
                        ← Cancelar
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Confirmar Nomeação
                    </button>
                </div>
            </form> --}}
        </div>
    </div>


</x-app-layout>
