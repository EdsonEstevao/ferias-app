<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Painel Gestor
        </h2>
    </x-slot>

    <div x-data="formMovimentacao()" class="bg-white p-6 rounded shadow max-w-2xl mx-auto mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📌 Registrar Movimentação Funcional</h2>

        <template x-if="mensagem">
            <div class="mb-4 text-green-600 font-semibold" x-text="mensagem" x-show="mensagem" x-transition></div>
        </template>

        <form @submit.prevent="enviar()">
            <!-- Servidor -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Servidor</label>
                <input type="text" x-model="servidor" class="mt-1 block w-full border-gray-300 rounded"
                    placeholder="Nome completo" required>
            </div>

            <!-- Cargo -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Cargo</label>
                <input type="text" x-model="cargo" class="mt-1 block w-full border-gray-300 rounded"
                    placeholder="Ex: Assessor Técnico" required>
            </div>

            <!-- Secretaria -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Secretaria</label>
                <input type="text" x-model="secretaria" class="mt-1 block w-full border-gray-300 rounded"
                    placeholder="Ex: SEMAD" required>
            </div>

            <!-- Tipo de movimentação -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tipo de Movimentação</label>
                <select x-model="tipo" class="mt-1 block w-full border-gray-300 rounded" required>
                    <option value="">Selecione</option>
                    <option value="Nomeação">Nomeação</option>
                    <option value="Exoneração">Exoneração</option>
                    <option value="Tornado sem efeito">Tornado sem efeito</option>
                </select>
            </div>

            <!-- Data -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Data da Movimentação</label>
                <input type="date" x-model="data" class="mt-1 block w-full border-gray-300 rounded" required>
            </div>

            <!-- Ato normativo -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Ato Normativo</label>
                <input type="text" x-model="ato" class="mt-1 block w-full border-gray-300 rounded"
                    placeholder="Ex: Portaria 123/2025">
            </div>

            <!-- Observação -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Observação</label>
                <textarea x-model="observacao" class="mt-1 block w-full border-gray-300 rounded" rows="3" placeholder="Opcional"></textarea>
            </div>

            <!-- Botão -->
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Salvar Movimentação
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formMovimentacao', () => ({
                servidor: '',
                cargo: '',
                secretaria: '',
                tipo: '',
                data: '',
                ato: '',
                observacao: '',
                mensagem: '',

                enviar() {
                    if (!this.servidor || !this.cargo || !this.secretaria || !this.tipo || !this.data) {
                        this.mensagem = 'Preencha todos os campos obrigatórios.';
                        setTimeout(() => this.mensagem = '', 5000);
                        return;
                    }

                    fetch('/api/movimentacoes', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                servidor: this.servidor,
                                cargo: this.cargo,
                                secretaria: this.secretaria,
                                tipo_movimentacao: this.tipo,
                                data_movimentacao: this.data,
                                ato_normativo: this.ato,
                                observacao: this.observacao
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.mensagem = 'Movimentação registrada com sucesso!';
                            this.servidor = this.cargo = this.secretaria = this.tipo = this.data =
                                this.ato = this.observacao = '';
                            setTimeout(() => this.mensagem = '', 5000);
                        })
                        .catch(() => {
                            this.mensagem = 'Erro ao registrar movimentação.';
                            setTimeout(() => this.mensagem = '', 5000);
                        });
                }
            }));
        });
    </script>

</x-app-layout>
