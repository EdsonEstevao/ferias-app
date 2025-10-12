<!-- Seção de Endereço -->
{{-- <div class="space-y-6" x -data="enderecoData()"> --}}
<div class="space-y-6">
    <!-- Cabeçalho da Seção -->
    <div class="flex items-center pb-4 space-x-3 border-b border-gray-200">
        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Endereço Residencial</h3>
            <p class="text-sm text-gray-600">Informe o endereço completo do servidor</p>
        </div>
    </div>

    <!-- Campos de Endereço -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- CEP -->
        <div class="md:col-span-1">
            <label for="cep" class="block mb-2 text-sm font-medium text-gray-700">
                CEP *
            </label>
            <input type="text" id="cep" name="cep"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg cep-mask form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="00000-000" x-model="endereco.cep" @blur="buscarCEP()">
            <p class="mt-1 text-xs text-gray-500" x-show="buscandoCEP">Buscando endereço...
            </p>
        </div>

        <!-- Endereço -->
        <div class="md:col-span-2">
            <label for="endereco" class="block mb-2 text-sm font-medium text-gray-700">
                Endereço (Logradouro) *
            </label>
            <input type="text" id="endereco" name="endereco"
                value="{{ old('endereco', $vinculo?->endereco) ?? '' }}"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Rua, Avenida, Travessa..." x-model="endereco.logradouro">
        </div>

        <!-- Número e Complemento -->
        <div class="md:col-span-1">
            <label for="numero" class="block mb-2 text-sm font-medium text-gray-700">
                Número *
            </label>
            <input type="text" id="numero" name="numero"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="123" x-model="endereco.numero">
        </div>

        <div class="md:col-span-1">
            <label for="complemento" class="block mb-2 text-sm font-medium text-gray-700">
                Complemento
            </label>
            <input type="text" id="complemento" name="complemento"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Apto 101, Bloco B..." x-model="endereco.complemento">
        </div>

        <!-- Bairro -->
        <div class="md:col-span-1">
            <label for="bairro" class="block mb-2 text-sm font-medium text-gray-700">
                Bairro *
            </label>
            <input type="text" id="bairro" name="bairro"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Centro" x-model="endereco.bairro">
        </div>
        <!-- Estado -->
        <div class="md:col-span-1">
            <label for="estado" class="block mb-2 text-sm font-medium text-gray-700">
                Estado *
            </label>
            <select id="estado" name="estado"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                x-model="endereco.estado">
                <option value="">Selecione o estado</option>

                <option value="AC" {{ $vinculo?->estado == 'AC' ? 'selected' : '' }}>Acre</option>
                <option value="AL" {{ $vinculo?->estado == 'AL' ? 'selected' : '' }}>Alagoas</option>
                <option value="AP" {{ $vinculo?->estado == 'AP' ? 'selected' : '' }}>Amapá</option>
                <option value="AM" {{ $vinculo?->estado == 'AM' ? 'selected' : '' }}>Amazonas</option>
                <option value="BA" {{ $vinculo?->estado == 'BA' ? 'selected' : '' }}>Bahia</option>
                <option value="CE" {{ $vinculo?->estado == 'CE' ? 'selected' : '' }}>Ceará</option>
                <option value="DF" {{ $vinculo?->estado == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                <option value="ES" {{ $vinculo?->estado == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                <option value="GO" {{ $vinculo?->estado == 'GO' ? 'selected' : '' }}>Goiás</option>
                <option value="MA" {{ $vinculo?->estado == 'MA' ? 'selected' : '' }}>Maranhão</option>
                <option value="MT" {{ $vinculo?->estado == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                <option value="MS" {{ $vinculo?->estado == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                <option value="MG" {{ $vinculo?->estado == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                <option value="PA" {{ $vinculo?->estado == 'PA' ? 'selected' : '' }}>Pará</option>
                <option value="PB" {{ $vinculo?->estado == 'PB' ? 'selected' : '' }}>Paraíba</option>
                <option value="PR" {{ $vinculo?->estado == 'PR' ? 'selected' : '' }}>Paraná</option>
                <option value="PE" {{ $vinculo?->estado == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                <option value="PI" {{ $vinculo?->estado == 'PI' ? 'selected' : '' }}>Piauí</option>
                <option value="RJ" {{ $vinculo?->estado == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                <option value="RN" {{ $vinculo?->estado == 'RN' ? 'selected' : '' }}>Rio Grande do Norte
                </option>
                <option value="RS" {{ $vinculo?->estado == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                <option value="RO" {{ $vinculo?->estado == 'RO' ? 'selected' : '' }}>Rondônia</option>
                <option value="RR" {{ $vinculo?->estado == 'RR' ? 'selected' : '' }}>Roraima</option>
                <option value="SC" {{ $vinculo?->estado == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                <option value="SP" {{ $vinculo?->estado == 'SP' ? 'selected' : '' }}>São Paulo</option>
                <option value="SE" {{ $vinculo?->estado == 'SE' ? 'selected' : '' }}>Sergipe</option>
                <option value="TO" {{ $vinculo?->estado == 'TO' ? 'selected' : '' }}>Tocantins</option>

            </select>
        </div>

        <!-- Cidade -->
        <div class="md:col-span-1">
            <label for="cidade" class="block mb-2 text-sm font-medium text-gray-700">
                Cidade *
            </label>
            <input type="text" id="cidade" name="cidade"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="São Paulo" x-model="endereco.cidade">

        </div>



        <!-- Ponto de Referência -->
        <div class="md:col-span-2">
            <label for="ponto_referencia" class="block mb-2 text-sm font-medium text-gray-700">
                Ponto de Referência
            </label>
            <input type="text" id="ponto_referencia" name="ponto_referencia"
                class="w-full px-4 py-3 transition-colors duration-200 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Próximo ao mercado, em frente à praça..." x-model="endereco.ponto_referencia">
        </div>
    </div>

    <!-- Mapa de Localização (Opcional) -->
    <div class="p-4 mt-6 border border-gray-200 rounded-lg bg-gray-50">
        <div class="flex items-center mb-3 space-x-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                </path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Localização</span>
        </div>
        <p class="text-sm text-gray-600">
            O endereço será usado para contato e correspondência oficial.
            Certifique-se de que as informações estão corretas.
        </p>
    </div>
</div>
{{-- <script>
    function enderecoData() {
        return {
            endereco: {
                logradouro: "{{ old('endereco', $vinculo?->endereco) ?? '' }}",
                numero: "{{ old('numero', $vinculo?->numero) ?? '' }}",
                complemento: "{{ old('complemento', $vinculo?->complemento) ?? '' }}",
                bairro: "{{ old('bairro', $vinculo?->bairro) ?? '' }}",
                cep: "{{ old('cep', $vinculo?->cep) ?? '' }}",
                estado: "{{ old('estado', $vinculo?->estado) ?? '' }}",
                cidade: "{{ old('cidade', $vinculo?->cidade) ?? '' }}",
                ponto_referencia: "{{ old('ponto_referencia', $vinculo?->ponto_referencia) ?? '' }}",
            },

        }
    }
</script> --}}
