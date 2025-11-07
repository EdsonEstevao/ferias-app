<x-app-layout>
    <div x-data="atualizarServidor()" class="min-h-screen py-8 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">
            <!-- Cabeçalho -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-white shadow-lg rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Editar informações do Servidor</h1>
                <p class="max-w-2xl mx-auto text-lg text-gray-600">Preencha as informações do servidor para editá-lo
                    no sistema de gestão de férias</p>
            </div>

            <!-- Card Principal -->
            <div class="overflow-hidden bg-white shadow-xl rounded-2xl">
                <!-- Indicador de Progresso -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                    <div class="flex items-center justify-between text-white">
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center justify-center w-8 h-8 bg-white rounded-full bg-opacity-20">
                                <span class="text-sm font-semibold" x-text="etapaAtual"></span>
                            </div>
                            <span class="text-sm font-medium">de 4 etapas</span>
                        </div>
                        <div class="text-sm" x-text="nomeEtapa"></div>
                    </div>
                    <div class="w-full h-2 mt-3 bg-white rounded-full bg-opacity-20">
                        <div class="h-2 transition-all duration-500 ease-out bg-white rounded-full"
                            :style="`width: ${(etapaAtual / 4) * 100}%`"></div>
                    </div>
                </div>

                <!-- Abas -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button @click="mudarAba('pessoal')"
                            :class="aba === 'pessoal' ?
                                'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex-1 px-1 py-4 text-sm font-medium text-center transition-colors duration-200 border-b-2">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" :class="aba === 'pessoal' ? 'text-blue-500' : 'text-gray-400'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Dados Pessoais</span>
                            </div>
                        </button>

                        <button @click="mudarAba('funcional')"
                            :class="aba === 'funcional' ?
                                'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex-1 px-1 py-4 text-sm font-medium text-center transition-colors duration-200 border-b-2">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" :class="aba === 'funcional' ? 'text-blue-500' : 'text-gray-400'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Vínculo Funcional</span>
                            </div>
                        </button>

                        <button @click="mudarAba('endereco')"
                            :class="aba === 'endereco' ?
                                'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex-1 px-1 py-4 text-sm font-medium text-center transition-colors duration-200 border-b-2">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" :class="aba === 'endereco' ? 'text-blue-500' : 'text-gray-400'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Endereço</span>
                            </div>
                        </button>

                        <button @click="mudarAba('documentacao')"
                            :class="aba === 'documentacao' ?
                                'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex-1 px-1 py-4 text-sm font-medium text-center transition-colors duration-200 border-b-2">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" :class="aba === 'documentacao' ? 'text-blue-500' : 'text-gray-400'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Documentação</span>
                            </div>
                        </button>
                    </nav>
                </div>
                {{-- @dd($servidor) --}}
                <!-- Formulário -->
                <form action="{{ route('servidores.update', $servidor->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <!-- Aba 1: Dados Pessoais -->
                    <div x-show="aba === 'pessoal'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4">
                        @include('servidores.partials.pessoal', [
                            'servidor' => $servidor,
                            'vinculo' => $vinculo,
                        ])
                    </div>

                    <!-- Aba 2: Vínculo Funcional -->
                    <div x-show="aba === 'funcional'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4">
                        @include('servidores.partials.funcional', [
                            'servidor' => $servidor,
                            'vinculo' => $vinculo,
                            'secretarias' => $secretarias,
                            'cargos' => $cargos,
                        ])
                    </div>

                    <!-- Aba 3: Endereço -->
                    <div x-show="aba === 'endereco'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4">
                        @include('servidores.partials.endereco', [
                            'servidor' => $servidor,
                            'vinculo' => $vinculo,
                        ])

                    </div>

                    <!-- Aba 4: Documentação -->
                    <div x-show="aba === 'documentacao'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4">
                        @include('servidores.partials.documentacao', [
                            'servidor' => $servidor,
                            'vinculo' => $vinculo,
                        ])
                    </div>

                    <!-- Navegação entre Abas -->
                    <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-200">
                        <!-- Botão Voltar -->
                        <div>
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center px-6 py-3 font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Voltar para Lista
                            </a>
                        </div>

                        <!-- Navegação entre etapas -->
                        <div class="flex items-center space-x-3">
                            <!-- Botão Anterior -->
                            <button type="button" @click="abaAnterior()" x-show="etapaAtual > 1"
                                class="inline-flex items-center px-6 py-3 font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Anterior
                            </button>

                            <!-- Botão Próximo -->
                            <button type="button" @click="proximaAba()" x-show="etapaAtual < 4"
                                class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700">
                                Próxima
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <!-- Botão Finalizar -->
                            <button type="submit" x-show="etapaAtual === 4"
                                class="inline-flex items-center px-6 py-3 text-white bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Finalizar Cadastro
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Indicador de Etapas (Mobile) -->
            <div class="p-4 mt-6 bg-white rounded-lg shadow-sm lg:hidden">
                <div class="flex items-center justify-between mb-2 text-sm text-gray-600">
                    <span>Etapa <span x-text="etapaAtual"></span> de 4</span>
                    <span x-text="nomeEtapa"></span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full">
                    <div class="h-2 transition-all duration-500 bg-blue-600 rounded-full"
                        :style="`width: ${(etapaAtual / 4) * 100}%`"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('atualizarServidor', () => ({
                aba: 'pessoal',
                etapaAtual: 1,
                nomeEtapa: 'Dados Pessoais',
                cep: '',
                buscandoCEP: false,
                // endereco: {
                //     logradouro: '',
                //     numero: '',
                //     complemento: '',
                //     bairro: '',
                //     cidade: '',
                //     estado: '',
                //     ponto_referencia: ''
                // },
                endereco: {
                    logradouro: "{{ old('endereco', $servidor->vinculos->first()?->endereco) ?? '' }}",
                    numero: "{{ old('numero', $servidor->vinculos->first()?->numero) ?? '' }}",
                    complemento: "{{ old('complemento', $servidor->vinculos->first()?->complemento) ?? '' }}",
                    bairro: "{{ old('bairro', $servidor->vinculos->first()?->bairro) ?? '' }}",
                    cep: "{{ old('cep', $servidor->vinculos->first()?->cep) ?? '' }}",
                    estado: "{{ old('estado', $servidor->vinculos->first()?->estado) ?? '' }}",
                    cidade: "{{ old('cidade', $servidor->vinculos->first()?->cidade) ?? '' }}",
                    ponto_referencia: "{{ old('ponto_referencia', $servidor->vinculos->first()?->ponto_referencia) ?? '' }}",
                },

                mudarAba(novaAba) {
                    this.aba = novaAba;
                    this.atualizarEtapa();
                },

                proximaAba() {
                    const abas = ['pessoal', 'funcional', 'endereco', 'documentacao'];
                    const indexAtual = abas.indexOf(this.aba);
                    if (indexAtual < abas.length - 1) {
                        this.aba = abas[indexAtual + 1];
                        this.atualizarEtapa();
                    }
                },

                abaAnterior() {
                    const abas = ['pessoal', 'funcional', 'endereco', 'documentacao'];
                    const indexAtual = abas.indexOf(this.aba);
                    if (indexAtual > 0) {
                        this.aba = abas[indexAtual - 1];
                        this.atualizarEtapa();
                    }
                },

                atualizarEtapa() {
                    const etapas = {
                        'pessoal': {
                            numero: 1,
                            nome: 'Dados Pessoais'
                        },
                        'funcional': {
                            numero: 2,
                            nome: 'Vínculo Funcional'
                        },
                        'endereco': {
                            numero: 3,
                            nome: 'Endereço'
                        },
                        'documentacao': {
                            numero: 4,
                            nome: 'Documentação'
                        }
                    };

                    this.etapaAtual = etapas[this.aba].numero;
                    this.nomeEtapa = etapas[this.aba].nome;
                },

                async buscarCEP() {
                    const cepLimpo = this.endereco.cep.replace(/\D/g, '');

                    if (cepLimpo.length !== 8) {
                        return;
                    }

                    this.buscandoCEP = true;

                    try {
                        const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
                        const data = await response.json();

                        if (!data.erro) {
                            this.endereco.logradouro = data.logradouro || '';
                            this.endereco.bairro = data.bairro || '';
                            this.endereco.cidade = data.localidade || '';
                            this.endereco.estado = data.uf || '';
                        } else {
                            console.log('CEP não encontrado');
                        }
                    } catch (error) {
                        console.error('Erro ao buscar CEP:', error);
                    } finally {
                        this.buscandoCEP = false;
                    }
                },
            }));
        });

        // Máscaras de entrada
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Inputmask !== 'undefined') {
                // CPF
                Inputmask({
                    mask: '999.999.999-99',
                    placeholder: '___.___.___-__',
                    clearIncomplete: true
                }).mask(document.querySelectorAll('.cpf-mask'));

                // Data
                Inputmask({
                    mask: '99/99/9999',
                    placeholder: 'dd/mm/aaaa',
                    clearIncomplete: true
                }).mask(document.querySelectorAll('.date-mask'));

                // CEP
                Inputmask({
                    mask: '99999-999',
                    placeholder: '_____-___',
                    clearIncomplete: true
                }).mask(document.querySelectorAll('.cep-mask'));
            }
        });
    </script>

    <style>
        .form-input {
            transition: all 0.2s ease-in-out;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }

        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</x-app-layout>
