<x-app-layout>
    <div class="px-4 py-6 mx-auto max-w-7xl" x-data="{ busca: '' }">
        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">👥 Lista de Servidores</h2>
            <a href="{{ route('servidores.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                <i class="fas fa-plus"></i> Adicionar Servidor
            </a>
        </div>

        {{-- Campo de busca --}}
        <div class="mb-6">
            <input type="text" x-model="busca" placeholder="Buscar por nome, CPF ou matrícula..."
                class="w-full px-4 py-2 border rounded shadow-sm focus:ring focus:border-blue-300">
        </div>

        {{-- Tabela --}}
        <div class="overflow-hidden bg-white rounded shadow">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">Nome</th>
                        <th class="px-6 py-3">Matrícula</th>
                        <th class="px-6 py-3">Departamento</th>
                        <th class="px-6 py-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="servidor in {{ Js::from($servidores) }}" :key="servidor.id">
                        <tr x-show="servidor.nome.toLowerCase().includes(busca.toLowerCase()) || servidor.matricula.includes(busca) || (servidor.cpf && servidor.cpf.includes(busca))"
                            class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900" x-text="servidor.nome"></td>
                            <td class="px-6 py-4" x-text="servidor.matricula"></td>
                            <td class="px-6 py-4" x-text="servidor.departamento"></td>
                            <td class="px-6 py-4 space-x-2">
                                <a :href="'/gestor/ferias/painel/' + servidor.id"
                                    class="text-blue-600 hover:underline">📅 Ver</a>
                                <a :href="'/ferias/create?servidorId=' + servidor.id"
                                    class="text-green-600 hover:underline">➕ Marcar</a>
                                <a :href="'{{ route('ferias.interromper.periodo', ['servidorId' => '__ID__']) }}'.replace(
                                    '__ID__', servidor.id)"
                                    class="text-red-600 hover:underline">
                                    ⛔ Interromper Férias
                                </a>
                                {{-- <a :href="{{ Js::from(route('ferias.interromper.periodo', ['servidorId' => $servidor->id])) }}"
                                    class="text-red-600 hover:underline">⛔ Interromper</a> --}}
                            </td>
                        </tr>
                    </template>

                    {{-- Sem resultados --}}
                    <tr
                        x-show="!{{ Js::from($servidores) }}.some(s => s.nome.toLowerCase().includes(busca.toLowerCase()) || s.matricula.includes(busca) || (s.cpf && s.cpf.includes(busca)))">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum servidor encontrado.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
