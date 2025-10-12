<!-- Cadastro de Servidores --->
<x-app-layout>

    <div x-data="{ aba: 'pessoal' }" class="max-w-5xl mx-auto bg-white p-6 rounded shadow mt-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">📝 Cadastro de Servidor</h2>

        <!-- Abas -->
        <div class="flex space-x-4 mb-6">
            <button @click="aba = 'pessoal'"
                :class="aba === 'pessoal' ? 'font-bold text-indigo-600' : 'text-gray-600'">Dados Pessoais</button>
            <button @click="aba = 'funcional'"
                :class="aba === 'funcional' ? 'font-bold text-indigo-600' : 'text-gray-600'">Vínculo Funcional</button>
            <button @click="aba = 'documentacao'"
                :class="aba === 'documentacao' ? 'font-bold text-indigo-600' : 'text-gray-600'">Documentação e
                Endereço</button>
        </div>

        <form action="{{ route('servidores.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Aba 1: Dados Pessoais -->
            <div x-show="aba === 'pessoal'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-100"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4 scale-100">
                @include('servidores.partials.pessoal')
            </div>

            <!-- Aba 2: Vínculo Funcional -->
            <div x-show="aba === 'funcional'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-100"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4 scale-100">
                @include('servidores.partials.funcional')
            </div>

            <!-- Aba 3: Documentação e Endereço -->
            <div x-show="aba === 'documentacao'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-100"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4 scale-100">
                @include('servidores.partials.documentacao')
            </div>

            <div class="flex justify-between">
                <!-- Voltar -->
                <div class="flex justify-end pt-4">
                    <a href="{{ route('servidores.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Salvar Servidor
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Inputmask({
                mask: '999.999.999-99'
            }).mask(document.querySelectorAll('.cpf-mask'));
            Inputmask({
                mask: '99/99/9999'
            }).mask(document.querySelectorAll('.date-mask'));
            Inputmask({
                mask: '99999-999'
            }).mask(document.querySelectorAll('.cep-mask'));
        });
    </script>

</x-app-layout>
