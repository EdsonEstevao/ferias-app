<!-- Cadastro de Servidores --->
<x-app-layout>

    <div class="max-w-4xl p-6 mx-auto bg-white rounded shadow">
        <h2 class="mb-6 text-2xl font-bold">👤 Cadastro de Servidor</h2>

        @if ($errors->any())
            <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
                <ul class="ml-5 list-disc">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('servidores.store') }}" class="space-y-4">
            @csrf

            {{-- Nome --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                <input type="text" name="nome" value="{{ old('nome') }}" required
                    class="block w-full mt-1 border-gray-300 rounded">
            </div>

            {{-- CPF e Matrícula --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf') }}" required maxlength="14"
                        placeholder="000.000.000-00" class="block w-full mt-1 border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Matrícula</label>
                    <input type="text" name="matricula" value="{{ old('matricula') }}" required
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
            </div>

            {{-- Email e Telefone --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="text" name="telefone" value="{{ old('telefone') }}" placeholder="99 9 9999-9999"
                        maxlength="16" class="block w-full mt-1 border-gray-300 rounded">
                </div>
            </div>

            {{-- Secretaria, Lotação, Departamento --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Secretaria</label>
                    <input type="text" name="secretaria" value="{{ old('secretaria') }}"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lotação</label>
                    <input type="text" name="lotacao" value="{{ old('lotacao') }}"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Departamento</label>
                    <input type="text" name="departamento" value="{{ old('departamento') }}"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
            </div>

            {{-- Processos e Memorando --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Processo de Implantação</label>
                    <input type="text" name="processo_implantacao" value="{{ old('processo_implantacao') }}"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Processo de Disposição</label>
                    <input type="text" name="processo_disposicao" value="{{ old('processo_disposicao') }}"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Número do Memorando</label>
                    <input type="text" name="numero_memorando" value="{{ old('numero_memorando') }}"
                        class="block w-full mt-1 border-gray-300 rounded">
                </div>
            </div>

            {{-- Botões --}}
            <div class="flex justify-end mt-6">
                <a href="{{ route('servidores.index') }}"
                    class="px-4 py-2 mr-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</a>
                <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">✅
                    Cadastrar</button>
            </div>
        </form>
    </div>
    <script>
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g, '');
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

            let soma = 0;
            for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
            let resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.charAt(9))) return false;

            soma = 0;
            for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            return resto === parseInt(cpf.charAt(10));
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const cpfInput = document.querySelector('input[name="cpf"]');
            if (!validarCPF(cpfInput.value)) {
                e.preventDefault();
                alert('CPF inválido. Verifique e tente novamente.');
                cpfInput.focus();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cpfInput = document.querySelector('input[name="cpf"]');

            cpfInput.addEventListener('input', function() {
                let value = cpfInput.value.replace(/\D/g, '');

                if (value.length > 11) value = value.slice(0, 11);

                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

                cpfInput.value = value;
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const telInput = document.querySelector('input[name="telefone"]');

            telInput.addEventListener('input', function() {
                let value = telInput.value.replace(/\D/g, '');

                if (value.length > 11) value = value.slice(0, 11);

                // Aplica máscara: (99) 9 9999-9999
                value = value.replace(/^(\d{2})(\d)/, '$1 $2');
                value = value.replace(/(\d{1})(\d{4})(\d{4})$/, '$1 $2-$3');

                telInput.value = value;
            });
        });
    </script>
</x-app-layout>
