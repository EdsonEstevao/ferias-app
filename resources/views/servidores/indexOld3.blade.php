{{-- Lista de servidores --}}
<x-app-layout>
    <div x-data="filtroServidores()" x-init="carregar()" class="max-w-6xl p-6 mx-auto mt-6 bg-white rounded shadow">
        <h2 class="mb-4 text-xl font-bold text-gray-800">👥 Lista de Servidores</h2>

        <!-- Campo de busca -->
        <input type="text" x-model="busca" placeholder="Buscar por nome, CPF ou matrícula"
            class="w-full px-3 py-2 mb-4 border rounded" />


        <!-- botão de adicionar -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('servidores.create') }}"
                class="inline-flex items-center px-4 py-2 text-white transition duration-200 bg-green-600 rounded hover:bg-green-700">
                <i class="mr-2 fas fa-user-plus"></i> Novo Servidor
            </a>
        </div>

        <!-- Tabela -->
        <table class="w-full border-collapse table-auto">
            <thead>
                <tr class="text-left bg-gray-100">
                    <th class="px-4 py-2">Nome</th>
                    <th class="px-4 py-2">CPF</th>
                    <th class="px-4 py-2">Matrícula</th>
                    <th class="px-4 py-2">Cargo</th>
                    <th class="px-4 py-2">Secretaria</th>
                    <th class="px-4 py-2 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="s in filtrados()" :key="s.id">
                    <tr class="border-t">
                        <td class="px-4 py-2" x-text="s.nome"></td>
                        <td class="px-4 py-2" x-text="s.cpf"></td>
                        <td class="px-4 py-2" x-text="s.matricula"></td>
                        <td class="px-4 py-2" x-text="s.cargo?.nome || '—'"></td>
                        <td class="px-4 py-2" x-text="s.secretaria?.nome || '—'"></td>
                        <td class="px-4 py-2 space-x-2 text-center">
                            <a :href="`/servidores/${s.id}/edit`" class="text-blue-600 hover:text-blue-800"
                                title="Editar">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <button @click="excluir(s.id)" class="text-red-600 hover:text-red-800" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <template x-if="filtrados().length === 0">
            <p class="mt-4 text-gray-500">Nenhum servidor encontrado.</p>
        </template>

        <template x-if="mensagem">
            <div class="p-3 mt-4 text-green-800 bg-green-100 rounded" x-text="mensagem" x-show="mensagem"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2">
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filtroServidores', () => ({
                servidores: {{ Js::from($servidores) }},
                busca: '',
                mensagem: '',

                carregar() {
                    this.servidores = {{ Js::from($servidores) }};
                },

                filtrados() {
                    const termo = this.busca.toLowerCase().normalize("NFD").replace(
                        /[\u0300-\u036f]/g, "");
                    return this.servidores.filter(s =>
                        s.nome.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                        .includes(termo) ||
                        s.cpf.includes(termo) ||
                        s.matricula.includes(termo)
                    );
                },

                excluir(id) {
                    if (confirm('Deseja realmente excluir este servidor?')) {
                        fetch(`/servidores/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    this.servidores = this.servidores.filter(s => s.id !== id);
                                    this.mensagem = data.message;
                                    setTimeout(() => this.mensagem = '', 5000);
                                } else {
                                    alert(data.message || 'Erro ao excluir.');
                                }
                            });
                    }
                }
            }));
        });
    </script>
</x-app-layout>
