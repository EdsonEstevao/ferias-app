<div class="space-y-8" x-data="vinculoFuncional()">
    <!-- Header com Título -->
    <div class="pb-4 border-b border-gray-200">
        <h3 class="flex items-center gap-2 text-xl font-semibold text-gray-900">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Vínculo Funcional
        </h3>
        <p class="mt-1 text-sm text-gray-600">Informações institucionais e de lotação do servidor</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
        <!-- Coluna 1 - Dados Principais -->
        <div class="space-y-6">
            <!-- Secretaria -->
            <div class="group">
                <label for="secretaria"
                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Secretaria <span class="text-red-400">*</span>
                </label>
                {{-- @dd($vinculo) --}}
                <div class="relative">
                    <select name="secretaria" id="secretaria" x-model="secretariaSelecionada" @change="carregarCargos()"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10"
                        required>
                        <option value="" class="text-gray-400">Selecione uma secretaria</option>
                        @foreach ($secretarias as $s)
                            <option value="{{ $s->id }}"
                                {{ $vinculo?->secretaria->id == $s->id ? 'selected' : '' }}>
                                {{ $s->sigla ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cargo -->
            <div class="group">
                <label for="cargo"
                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Cargo <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <template x-if="carregandoCargos">
                        <div class="flex items-center justify-center py-3">
                            <div class="w-6 h-6 border-b-2 border-purple-500 rounded-full animate-spin"></div>
                            <span class="ml-2 text-sm text-gray-500">Carregando cargos...</span>
                        </div>
                    </template>
                    <select name="cargo" id="cargo" x-model="cargoSelecionado" x-show="!carregandoCargos"
                        :disabled="!secretariaSelecionada || cargos.length === 0"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10 disabled:opacity-50 disabled:cursor-not-allowed"
                        required>

                        <option value="" class="text-gray-400" disabled
                            x-text="!secretariaSelecionada ? 'Selecione primeiro a secretaria'  : (cargos.length === 0 ? 'Nenhum cargo disponível' : 'Selecione um cargo')">
                        </option>
                        <template x-for="cargo in cargos" :key="cargo.id">
                            <option :value="cargo.id" x-text="cargo.nome"
                                x-bind:="cargoSelecionado == cargo.nome ? 'selected' : ''" class="py-2 text-gray-700">
                            </option>
                        </template>
                    </select>
                    <span class="text-xs text-indigo-500"
                        x-show="!carregandoCargos && secretariaSelecionada && cargos.length === 0">
                        Nenhum cargo disponível para a secretaria selecionada.
                    </span>
                    <div x-show="cargoSelecionado" class="mt-2 text-sm text-purple-600">
                        <span class="font-medium">Simbologia:</span>
                        <span x-text="cargos.find(c => c.id == cargoSelecionado)?.simbologia || '—'"></span>
                    </div>

                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                        x-show="!carregandoCargos">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Departamento -->
            <div class="group">
                <label for="departamento"
                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Departamento
                </label>
                <input type="text" name="departamento" id="departamento"
                    value="{{ old('departamento', $vinculo->departamento ?? ($vinculo->departamento ?? '')) }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-gray-400 placeholder-gray-400"
                    placeholder="Digite o departamento">
            </div>
        </div>

        <!-- Coluna 2 - Dados do Servidor -->
        <div class="space-y-6">
            <!-- Tipo de Servidor -->
            <div class="group">
                <label for="tipo_servidor"
                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Tipo de Servidor
                </label>
                <div class="relative">
                    <select name="tipo_servidor" id="tipo_servidor"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10">
                        <option value="" class="text-gray-400">Selecione o tipo</option>
                        @foreach (['Federal', 'Cedido', 'Disponibilizado', 'Interno', 'Regional'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ (isset($vinculo) && $vinculo->tipo_servidor == $tipo ? 'selected' : old('tipo_servidor') == $tipo) ? 'selected' : '' }}
                                class="py-2 text-gray-700">
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sexo -->
            <div class="group">
                <label for="sexo"
                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                    <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                    </svg>
                    Sexo
                </label>
                <div class="relative">
                    <select name="sexo" id="sexo"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10">
                        <option value="" class="text-gray-400">Selecione</option>
                        @foreach (['Masculino', 'Feminino', 'Outro'] as $sexo)
                            <option value="{{ $sexo }}"
                                {{ old('sexo', $vinculo->sexo ?? null) == $sexo ? 'selected' : '' }}
                                class="py-2 text-gray-700">
                                {{ $sexo }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- É Diretor? -->
            <div class="group">
                <label
                    class="flex items-center gap-1 mb-2 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    É Diretor?
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer group/radio">
                        <input type="radio" name="is_diretor" value="Sim"
                            {{ old('is_diretor', $vinculo->is_diretor ?? 0) == 1 ? 'checked' : '' }}
                            class="w-4 h-4 text-yellow-500 border-gray-300 focus:ring-yellow-500">
                        <span
                            class="text-sm text-gray-700 transition-colors group-hover/radio:text-gray-900">Sim</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group/radio">
                        <input type="radio" name="is_diretor" value="Não"
                            {{ old('is_diretor', $vinculo->is_diretor ?? 1) == 0 ? 'checked' : '' }}
                            class="w-4 h-4 text-yellow-500 border-gray-300 focus:ring-yellow-500 ">
                        <span
                            class="text-sm text-gray-700 transition-colors group-hover/radio:text-gray-900">Não</span>
                    </label>
                </div>
            </div>
        </div>


        <!-- Coluna 3 - Dados Adicionais -->
        <div class="space-y-6">
            <x-input name="lotacao" label="Lotação" icon="location" icon-color="text-indigo-500"
                value="{{ old('lotacao', $vinculo->lotacao ?? '') }}" placeholder="Local de lotação"
                class="rounded-xl" />

            <x-input name="local_trabalho" label="Local de Trabalho" icon="office" icon-color="text-teal-500"
                value="{{ old('local_trabalho', $vinculo->local_trabalho ?? '') }}" placeholder="Local de trabalho"
                class="rounded-xl" />

            <x-input name="data_saida" label="Data de Saída" class="date-mask rounded-xl" icon="calendar"
                value="{{ old('data_saida', isset($vinculo->data_saida) ? date('d/m/Y', strtotime($vinculo->data_saida)) : '') }}"
                icon-color="text-red-500" placeholder="dd/mm/aaaa" />
        </div>
    </div>

    <!-- Grid Inferior - Campos Adicionais -->
    <div class="grid grid-cols-1 gap-6 pt-6 border-t border-gray-200 md:grid-cols-2 lg:grid-cols-4">
        <x-input name="processo_implantacao" label="Processo de Implantação" placeholder="Nº do processo"
            value="{{ old('processo_implantacao', $vinculo->processo_implantacao ?? '') }}"
            class="text-sm rounded-xl" />

        <x-input name="processo_disposicao" label="Processo de Disposição" placeholder="Nº do processo"
            value="{{ old('processo_disposicao', $vinculo->processo_disposicao ?? '') }}"
            class="text-sm rounded-xl" />

        <x-input name="numero_memorando" label="Nº do Memorando" placeholder="Nº do memorando"
            class="text-sm rounded-xl" value="{{ old('numero_memorando', $vinculo->numero_memorando ?? '') }}" />

        <x-input name="ato_normativo" label="Ato Normativo" placeholder="Informe o ato" class="text-sm rounded-xl"
            value="{{ old('ato_normativo', $vinculo->ato_normativo ?? '') }}" />
    </div>
</div>

<script>
    function vinculoFuncional() {
        return {
            secretariaSelecionada: '{{ isset($vinculo->secretaria) ? old('secretaria', $vinculo->secretaria->id) : '' }}',
            cargoSelecionado: '{{ old('cargo', $vinculo->cargo->id ?? '') }}',

            cargos: [],
            carregandoCargos: false,
            init() {
                // Se já tem uma secretaria selecionada (no caso de old), carrega os cargos
                this.cargoSelecionado = '{{ old('cargo', $vinculo->cargo->id ?? '') }}';
                if (this.secretariaSelecionada) {
                    this.carregarCargos().then(() => {
                        this.cargoSelecionado = '{{ old('cargo', $vinculo->cargo->id ?? '') }}';
                    });
                }
            },

            async carregarCargos() {

                if (!this.secretariaSelecionada) {
                    this.cargos = [];
                    this.cargoSelecionado = '';

                    return;
                }

                this.carregandoCargos = true;
                this.cargoSelecionado = '';

                try {
                    const response = await fetch(`/api/cargos/${this.secretariaSelecionada}`);
                    const data = await response.json();

                    console.log('Cargos carregados:', data, 'secretaria:', this.secretariaSelecionada);

                    this.cargos = data.cargos || [];
                } catch (error) {
                    console.error('Erro ao carregar cargos:', error);
                    this.cargos = [];
                } finally {
                    this.carregandoCargos = false;
                }
            }
        }
    }
</script>
