{{-- Listas de Secretarias --}}
<x-app-layout>
    <h2 class="mb-6 text-2xl font-semibold">Listas de Secretarias</h2>

    {{-- <div class="max-w-4xl p-6 mx-auto mt-6 bg-white rounded shadow" x-data="vinculoCargosSecretarias()" x-init="init()"> --}}
    <div class="max-w-4xl p-6 mx-auto mt-6 bg-white rounded shadow" x-data="filtroSecretarias()" x-init="carregar()">
        <h2 class="mb-4 text-xl font-bold text-gray-800">📌 Secretarias Cadastradas</h2>

        <div class="mb-4">
            <a href="{{ route('secretarias.create') }}"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">➕
                Adicionar Secretaria</a>
        </div>
        <!-- Mensagem -->
        <template x-if="mensagem">
            <div class="p-4 mb-4 font-semibold text-green-800 bg-green-200 rounded" x-text="mensagem" x-show="mensagem">
            </div>
        </template>
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">

                        <div class="max-w-5xl p-6 mx-auto mt-6 bg-white rounded shadow">
                            <h2 class="mb-4 text-xl font-bold text-gray-800">📋 Lista de Secretarias</h2>

                            <!-- Campo de busca -->
                            <input type="text" x-model="busca" placeholder="Buscar por nome ou sigla"
                                class="w-full px-3 py-2 mb-4 border rounded" />

                            <!-- Tabela -->
                            <table class="w-full border-collapse table-auto">
                                <thead>
                                    <tr class="text-left bg-gray-100">
                                        <th class="px-4 py-2">Sigla</th>
                                        <th class="px-4 py-2">Origem</th>
                                        <th class="px-4 py-2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="s in filtradas()" :key="s.id">
                                        <tr class="border-t">
                                            <td class="px-4 py-2" x-text="s.sigla"></td>
                                            <td class="px-4 py-2"
                                                x-text="s.secretaria_origem ?  s.secretaria_origem.sigla : 'Sede'">
                                                {{-- <p>
                                                    <span class="text-xs font-semibold"
                                                        x-text="s.secretaria.nome"></span>
                                                </p> --}}

                                                {{-- x-text="s.secretaria_origem ?? s.secretaria_origem.nome + ' (' + s.secretaria_origem.sigla + ')' : '—'"> --}}
                                            </td>
                                            <td class="px-4 py-2 space-x-2">
                                                <div class="flex justify-between gap-4">
                                                    <a :href="`/secretarias/${s.id}/edit`"
                                                        class="text-sm text-blue-600 hover:underline">Editar</a>

                                                    <button @click="confirmarExclusao(s.id)"
                                                        class="text-sm text-red-600 hover:underline">Excluir</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <template x-if="filtradas().length === 0">
                                <p class="mt-4 text-gray-500">Nenhuma secretaria encontrada.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filtroSecretarias', () => ({
                secretarias: [],
                busca: '',
                mensagem: '',

                carregar() {
                    this.secretarias = @json($secretarias);
                },

                filtradas() {
                    const termo = this.busca.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g,
                        "");
                    return this.secretarias.filter(s =>
                        s.nome.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                        .includes(termo) ||
                        s.sigla.toLowerCase().includes(termo)
                    );
                },
                confirmarExclusao(id) {
                    if (confirm('Tem certeza que deseja excluir esta secretaria?')) {
                        fetch(`/secretarias/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(res => {

                                console.log(res);
                                if (res.ok) {
                                    this.secretarias = this.secretarias.filter(s => s.id !== id);
                                    this.busca = ''; // opcional: limpa busca
                                    this.mensagem = 'Secretaria excluída com sucesso!';
                                    setTimeout(() => {
                                        this.mensagem = '';
                                    }, 3000);

                                } else {
                                    alert(
                                        'Erro ao excluir. Verifique se a secretaria está vinculada a outros dados.'
                                    );
                                }
                            });
                    }
                }
            }));
        });
    </script>
</x-app-layout>
