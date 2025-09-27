<x-app-layout>
    @php
        $cor = match ($vinculo->tipo_movimentacao) {
            'Nomeação' => 'text-green-600',
            'Exoneração' => 'text-red-600',
            'Tornado sem efeito' => 'text-yellow-600',
            default => 'text-gray-600',
        };
    @endphp
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Painel Gestor - Historico do Servidor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="flex gap-2 justify-end mb-4">
                    <a :href="`/relatorio/movimentacoes?${params}`" target="_blank"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        🖨️ PDF
                    </a>
                    <a :href="`/exportar/movimentacoes?${params}`" target="_blank"
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        📊 Excel
                    </a>
                    <button @click="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        🖨️ Imprimir
                    </button>
                </div>

                <div x-data="painelMovimentacoes()" x-init="carregar()"
                    class="bg-white p-6 rounded shadow max-w-6xl mx-auto mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">📚 Histórico Funcional dos Servidores</h2>

                    <!-- Filtros -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <input type="text" x-model="filtro.servidor" placeholder="Servidor"
                            class="border rounded px-3 py-2">
                        <input type="text" x-model="filtro.cargo" placeholder="Cargo"
                            class="border rounded px-3 py-2">
                        <input type="text" x-model="filtro.secretaria" placeholder="Secretaria"
                            class="border rounded px-3 py-2">
                        <select x-model="filtro.tipo" class="border rounded px-3 py-2">
                            <option value="">Tipo</option>
                            <option value="Nomeação">Nomeação</option>
                            <option value="Exoneração">Exoneração</option>
                            <option value="Tornado sem efeito">Tornado sem efeito</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-between items-center mb-4">
                        <button @click="carregar()"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            🔄 Atualizar
                        </button>
                        <a :href="`/relatorio/movimentacoes?servidor=${filtro.servidor}&cargo=${filtro.cargo}&secretaria=${filtro.secretaria}&tipo=${filtro.tipo}`"
                            target="_blank" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            🖨️ Gerar PDF
                        </a>
                    </div>

                    <!-- Listagem -->
                    <template x-if="movimentacoes.length">
                        <div class="space-y-4">
                            <template x-for="mov in movimentacoes" :key="mov.id">
                                <div class="border rounded p-4 shadow-sm">
                                    <p class="font-semibold text-gray-800"
                                        x-text="`${mov.tipo_movimentacao} — ${mov.cargo}`"></p>
                                    <p class="text-sm text-gray-600">Servidor: <span x-text="mov.servidor.nome"></span>
                                    </p>
                                    <p class="text-sm text-gray-600">Secretaria: <span x-text="mov.secretaria"></span>
                                    </p>
                                    <p class="text-sm text-gray-600">Data: <span
                                            x-text="formatarData(mov.data_movimentacao)"></span></p>
                                    <p class="text-xs text-gray-500">Ato: <span x-text="mov.ato_normativo"></span></p>
                                    <template x-if="mov.observacao">
                                        <p class="text-xs text-gray-500 italic">Obs: <span
                                                x-text="mov.observacao"></span></p>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!movimentacoes.length">
                        <p class="text-gray-500 mt-4">Nenhuma movimentação encontrada.</p>
                    </template>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('painelMovimentacoes', () => ({
                movimentacoes: [],
                filtro: {
                    servidor: '',
                    cargo: '',
                    secretaria: '',
                    tipo: ''
                },

                carregar() {
                    const params = new URLSearchParams(this.filtro).toString();
                    fetch(`/api/movimentacoes?${params}`)
                        .then(res => res.json())
                        .then(data => this.movimentacoes = data);
                },

                formatarData(data) {
                    const d = new Date(data);
                    return d.toLocaleDateString('pt-BR');
                }
            }));
        });
    </script>
</x-app-layout>
