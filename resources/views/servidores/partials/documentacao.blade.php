<div class="space-y-8">
    <!-- Header com Título -->
    <div class="pb-6 border-b border-gray-200">
        <h3 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
            <div class="p-2 rounded-lg bg-blue-50">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            Dados Pessoais
        </h3>
        <p class="mt-2 text-sm text-gray-600 ml-11">Informações cadastrais e de identificação</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Coluna 1 - Documentação -->
        <div class="space-y-6">
            <!-- CPF e RG -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-input name="cpf" label="CPF" class="cpf-mask" icon="document-text" icon-color="text-blue-500"
                    value="{{ old('cpf', $servidor->cpf ?? '') }}" placeholder="000.000.000-00" />

                <x-input name="rg" label="RG" icon="identification" icon-color="text-green-500"
                    value="{{ old('rg', $vinculo->rg ?? '') }}" placeholder="Número do documento" />
            </div>

            <!-- Órgão Expedidor e Data -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-input name="orgao_expedidor" label="Órgão Expedidor" icon="building-office"
                    value="{{ old('orgao_expedidor', $vinculo->orgao_expedidor ?? '') }}" icon-color="text-purple-500"
                    placeholder="Ex: SSP, PM" />

                <x-input name="data_expedicao" label="Data de Expedição" class="date-mask" icon="calendar"
                    value="{{ old('data_expedicao', isset($vinculo->data_expedicao) ? date('d/m/Y', strtotime($vinculo->data_expedicao)) : '') }}"
                    icon-color="text-orange-500" placeholder="dd/mm/aaaa" />
            </div>

            <!-- Naturalidade e Nacionalidade -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-input name="naturalidade" label="Naturalidade" icon="map-pin" icon-color="text-red-500"
                    value="{{ old('naturalidade', $vinculo->naturalidade ?? '') }}"
                    placeholder="Cidade de nascimento" />

                <x-input name="nacionalidade" label="Nacionalidade" icon="globe" icon-color="text-indigo-500"
                    value="{{ old('nacionalidade', $vinculo->nacionalidade ?? '') }}" placeholder="Nacionalidade" />
            </div>
        </div>

        <!-- Coluna 2 - Família e Endereço -->
        <div class="space-y-6">
            <!-- Nome dos Pais -->
            <div class="space-y-4">
                <x-input name="nome_mae" label="Nome da Mãe" icon="user" icon-color="text-pink-500"
                    value="{{ old('nome_mae', $vinculo->nome_mae ?? '') }}" placeholder="Nome completo da mãe" />

                <x-input name="nome_pai" label="Nome do Pai" icon="user" icon-color="text-blue-500"
                    value="{{ old('nome_pai', $vinculo->nome_pai ?? '') }}" placeholder="Nome completo do pai" />
            </div>

            <!-- Escolaridade com Campo Condicional -->

            <div x-data="{
                escolaridade: '{{ old('escolaridade', $vinculo?->escolaridade) ?? '' }}',
                showCurso: '{{ old('escolaridade', $vinculo?->escolaridade) ?? '' }}' && ['Superior', 'Pós-graduação',
                    'Mestrado', 'Doutorado'
                ].includes('{{ old('escolaridade', $vinculo?->escolaridade) ?? '' }}'),
                init() {
                    this.$watch('escolaridade', (value) => {
                        this.showCurso = ['Superior', 'Pós-graduação', 'Mestrado', 'Doutorado'].includes(value);
                    });
                    this.showCurso = ['Superior', 'Pós-graduação', 'Mestrado', 'Doutorado'].includes(this.escolaridade);
                },

            }" class="space-y-4">
                <!-- Escolaridade -->
                <div class="group">
                    <label for="escolaridade"
                        class="flex items-center gap-2 mb-3 text-sm font-medium text-gray-700 transition-colors duration-200 group-hover:text-gray-900">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14v6l9-5M12 20l-9-5" />
                        </svg>
                        Escolaridade
                    </label>
                    <div class="relative">
                        <select name="escolaridade" id="escolaridade" x-model="escolaridade"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 shadow-sm hover:border-gray-400 bg-white appearance-none cursor-pointer pr-10">
                            <option value="" class="text-gray-400">Selecione o nível de escolaridade</option>
                            <option value="Fundamental" class="text-gray-700">Fundamental</option>
                            <option value="Médio" class="text-gray-700">Médio</option>
                            <option value="Superior" class="text-gray-700">Superior</option>
                            <option value="Pós-graduação" class="text-gray-700">Pós-graduação</option>
                            <option value="Mestrado" class="text-gray-700">Mestrado</option>
                            <option value="Doutorado" class="text-gray-700">Doutorado</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Curso Superior (condicional) -->
                <div x-show="showCurso" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" class="group">
                    <x-input name="curso_superior" label="Curso Superior" id="curso_superior" icon="academic-cap"
                        value="{{ old('curso_superior', $vinculo?->curso_superior) ?? '' }}"
                        icon-color="text-green-500" placeholder="Nome do curso superior" />
                    {{-- <input type="text" name="curso_superior" id="curso_superior"
                        value="{{ old('curso_superior', $vinculo->curso_superior ?? '') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 shadow-sm hover:border-gray-400 placeholder-gray-400"
                        placeholder="Nome do curso superior">
                    {{ $vinculo->curso_superior }} --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Endereço -->
    {{-- <div class="pt-8 border-t border-gray-200">
        <h4 class="flex items-center gap-2 mb-6 text-lg font-semibold text-gray-900">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Endereço
        </h4>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <x-input name="numero" label="Número" icon="hashtag" icon-color="text-gray-500" placeholder="Nº" />

            <x-input name="complemento" label="Complemento" icon="home" icon-color="text-gray-500"
                placeholder="Apto, Bloco, etc." />

            <x-input name="bairro" label="Bairro" icon="location-marker" icon-color="text-gray-500"
                placeholder="Bairro" />

            <div class="grid grid-cols-2 gap-4">
                <x-input name="cidade" label="Cidade" icon="city" icon-color="text-gray-500"
                    placeholder="Cidade" />

                <x-input name="estado" label="Estado" icon="map" icon-color="text-gray-500"
                    placeholder="UF" />
            </div>
        </div>
    </div> --}}
</div>
<style>
    /* Efeitos de foco refinados */
    input:focus,
    select:focus {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
            0 4px 6px -2px rgba(0, 0, 0, 0.05),
            0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Animações suaves para transições */
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Efeito de glow para campos preenchidos */
    input:not(:placeholder-shown):invalid,
    select:valid:not([value=""]) {
        border-color: #10B981;
        background-color: rgba(16, 185, 129, 0.02);
    }


    /* Estilo para o dropdown */
    select option {
        padding: 12px;
        margin: 4px 0;
    }

    /* Efeito de hover nos grupos */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }
</style>
<script>
    // function documentacaoData() {
    //     return {
    //         // Dados podem ser adicionados aqui se necessário
    //         showCurso: '{{ old('escolaridade', $vinculo?->escolaridade) ?? '' }}' && ['Superior', 'Pós-graduação',
    //             'Mestrado', 'Doutorado'
    //         ].includes('{{ old('escolaridade', $vinculo?->escolaridade) ?? '' }}'),
    //         showCursoSuperior: '{{ old('curso_superior', $vinculo?->curso_superior) ?? '' }}' !== '',
    //         escolaridade: '{{ old('escolaridade', $vinculo?->escolaridade) ?? '' }}',
    //         cursoSuperior: '{{ old('curso_superior', $vinculo?->curso_superior) ?? '' }}',

    //         init() {
    //             this.$watch('escolaridade', (value) => {
    //                 this.showCurso = ['Superior', 'Pós-graduação', 'Mestrado', 'Doutorado'].includes(value);
    //             });
    //             this.showCurso = ['Superior', 'Pós-graduação', 'Mestrado', 'Doutorado'].includes(this.escolaridade);
    //         },
    //         toggleCursoSuperior() {
    //             this.showCursoSuperior = !this.showCursoSuperior;
    //         },
    //         toggleCurso() {
    //             this.showCurso = !this.showCurso;
    //         },
    //     };
    // }
</script>
