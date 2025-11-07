<div class="pt-6 border-t">
    <h3 class="mb-4 text-xl font-semibold text-gray-900">📊 Prévia da Importação JSON</h3>

    <!-- Resumo -->
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="p-4 border border-green-200 rounded-lg bg-green-50">
            <div class="text-2xl font-bold text-green-600">{{ $validos }}</div>
            <div class="text-green-700">Registros Válidos</div>
        </div>
        <div class="p-4 border border-red-200 rounded-lg bg-red-50">
            <div class="text-2xl font-bold text-red-600">{{ $invalidos }}</div>
            <div class="text-red-700">Registros com Erros</div>
        </div>
        <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
            <div class="text-2xl font-bold text-blue-600">{{ $total }}</div>
            <div class="text-blue-700">Total no JSON</div>
        </div>
        <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
            <div class="text-2xl font-bold text-purple-600">{{ $validos + $invalidos }}</div>
            <div class="text-purple-700">Processados</div>
        </div>
    </div>

    <!-- Dados Válidos -->
    @if ($validos > 0)
        <div class="mb-6">
            <h4 class="mb-3 text-lg font-medium text-gray-900">✅ Dados que serão importados (primeiros 5):</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase">Matrícula</th>
                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase">Servidor</th>
                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase">Cargo</th>
                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase">Lotação</th>
                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase">Tipos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach (array_slice($preview, 0, 5) as $linha)
                            <tr>
                                <td class="px-4 py-2 font-mono text-sm">{{ $linha['matricula'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $linha['servidor'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $linha['cargo'] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $linha['lotacao'] }}</td>
                                <td class="px-4 py-2 text-sm">
                                    @foreach ($linha['tipo_servidor'] as $tipo)
                                        <span
                                            class="inline-block px-2 py-1 mr-1 mb-1 rounded-full text-xs font-medium
                                    {{ $tipo == 'interno' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $tipo == 'cedido' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $tipo == 'federal' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $tipo == 'regional' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $tipo == 'disponibilizado' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $tipo }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                        @if ($validos > 5)
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-sm text-center text-gray-500">
                                    ... e mais {{ $validos - 5 }} registros
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Ações -->
    <div class="flex items-center justify-between pt-4 border-t">
        <a href="{{ route('servidores.import.form') }}"
            class="inline-flex items-center px-4 py-2 text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50">
            ↶ Voltar e Corrigir
        </a>

        @if ($validos > 0)
            <form action="{{ route('servidores.import.process') }}" method="POST">
                @csrf
                <input type="hidden" name="dados" value="{{ json_encode($preview) }}">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Confirmar Importação ({{ $validos }} registros)
                </button>
            </form>
        @endif
    </div>
</div>
