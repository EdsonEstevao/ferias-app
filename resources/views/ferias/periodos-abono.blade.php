<x-app-layout>
    <div class="container mx-auto">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center w-10 h-10 mr-3 bg-purple-100 rounded-full">
                    <span class="text-xl">💰</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Períodos Convertidos para Abono Pecuniário</h1>
            </div>

            @if ($periodosAbono->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Servidor</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Matrícula</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Ano Exercício</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Período</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Dias</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Data Conversão</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($periodosAbono as $periodo)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $periodo->ferias->servidor->nome }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $periodo->ferias->servidor->matricula }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $periodo->ferias->ano_exercicio }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $periodo->inicio_formatado }} a {{ $periodo->fim_formatado }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 text-xs font-semibold leading-5 text-purple-800 bg-purple-100 rounded-full">
                                            {{ $periodo->dias }} dias
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $periodo->data_conversao_abono->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('ferias.show', $periodo->ferias_id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Ver Férias</a>
                                        <a href="{{ route('ferias.reverter-abono.view', $periodo->id) }}"
                                            class="ml-4 text-orange-600 hover:text-orange-900">Reverter</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $periodosAbono->links() }}
                </div>
            @else
                <div class="p-8 text-center rounded-lg bg-gray-50">
                    <div class="mb-4 text-4xl">💰</div>
                    <h3 class="text-lg font-medium text-gray-900">Nenhum período convertido</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Não há períodos convertidos para abono pecuniário no momento.
                    </p>
                </div>
                <!-- Botão de Voltar -->
                <div class="flex flex-col gap-2 mt-6 sm:flex-row">
                    <a href="{{ url()->previous() }}"
                        class="px-4 py-2 text-center text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                        ← Voltar para a Lista
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
