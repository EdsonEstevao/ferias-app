<x-app-layout>
    <div class="container px-4 py-8 mx-auto">
        <div class="max-w-4xl mx-auto">
            <!-- Cabeçalho -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Exoneração de Servidor</h1>
                <p class="text-gray-600">Registrar desligamento do servidor</p>
            </div>

            <!-- Card de Informações do Servidor -->
            <div class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h2 class="mb-4 text-xl font-semibold text-gray-900">👤 Servidor a ser Exonerado</h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Nome Completo</label>
                        <p class="text-lg font-medium text-gray-900">{{ $servidor->nome }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Matrícula</label>
                        <p class="font-mono text-gray-900">{{ $servidor->matricula }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Cargo Atual</label>
                        <p class="text-gray-900">{{ $vinculoAtual->cargo }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Lotação</label>
                        <p class="text-gray-900">{{ $vinculoAtual->lotacao }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Departamento</label>
                        <p class="text-gray-900">{{ $vinculoAtual->departamento ?? 'Não informado' }}</p>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Tipo de Servidor</label>
                        <div class="flex flex-wrap gap-1">
                            @foreach ($vinculoAtual->tipo_servidor as $tipo)
                                <span
                                    class="inline-block px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                    {{ $tipo }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Exoneração -->
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <h2 class="mb-6 text-xl font-semibold text-gray-900">📝 Dados da Exoneração</h2>

                <form action="{{ route('servidores.exoneracao.store', $servidor) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                        <!-- Data de Saída -->
                        <div class="col-span-1">
                            <label for="data_saida" class="block mb-2 text-sm font-medium text-gray-700">
                                📅 Data de Saída <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="data_saida" id="data_saida"
                                value="{{ old('data_saida', now()->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                required>
                            @error('data_saida')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Processo de Exoneração -->
                        <div class="col-span-1">
                            <label for="processo_exoneracao" class="block mb-2 text-sm font-medium text-gray-700">
                                📋 Processo de Exoneração
                            </label>
                            <input type="text" name="processo_exoneracao" id="processo_exoneracao"
                                value="{{ old('processo_exoneracao') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Nº do processo">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                        <!-- Nº do Memorando -->
                        <div class="col-span-1">
                            <label for="numero_memorando" class="block mb-2 text-sm font-medium text-gray-700">
                                📨 Nº do Memorando
                            </label>
                            <input type="text" name="numero_memorando" id="numero_memorando"
                                value="{{ old('numero_memorando') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Número do memorando">
                        </div>

                        <!-- Ato Normativo -->
                        <div class="col-span-1">
                            <label for="ato_normativo" class="block mb-2 text-sm font-medium text-gray-700">
                                ⚖️ Ato Normativo
                            </label>
                            <input type="text" name="ato_normativo" id="ato_normativo"
                                value="{{ old('ato_normativo') }}"
                                class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Ato normativo da exoneração">
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="mb-6">
                        <label for="motivo" class="block mb-2 text-sm font-medium text-gray-700">
                            ❓ Motivo da Exoneração <span class="text-red-500">*</span>
                        </label>
                        <textarea name="motivo" id="motivo" rows="3"
                            class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Descreva o motivo da exoneração..." required>{{ old('motivo') }}</textarea>
                        @error('motivo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observações -->
                    <div class="mb-8">
                        <label for="observacoes" class="block mb-2 text-sm font-medium text-gray-700">
                            💬 Observações Adicionais
                        </label>
                        <textarea name="observacoes" id="observacoes" rows="4"
                            class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Observações complementares...">{{ old('observacoes') }}</textarea>
                    </div>

                    <!-- Ações -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('servidores.show', $servidor) }}"
                            class="inline-flex items-center px-6 py-3 text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
                            ← Cancelar
                        </a>

                        <button type="submit"
                            onclick="return confirm('Tem certeza que deseja exonerar este servidor? Esta ação não pode ser desfeita facilmente.')"
                            class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700">
                            🚨 Confirmar Exoneração
                        </button>
                    </div>
                </form>
            </div>

            <!-- Aviso Importante -->
            <div class="p-6 mt-6 border border-yellow-200 rounded-lg bg-yellow-50">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-yellow-800">Atenção</h3>
                        <div class="mt-1 space-y-1 text-sm text-yellow-700">
                            <p>• A exoneração tornará o servidor inativo no sistema</p>
                            <p>• O histórico permanecerá disponível para consulta</p>
                            <p>• É possível restaurar o servidor posteriormente se necessário</p>
                            <p>• Verifique todas as informações antes de confirmar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
