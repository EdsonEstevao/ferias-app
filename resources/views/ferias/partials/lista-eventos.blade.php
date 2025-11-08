@props(['eventos', 'titulo' => 'Histórico de Eventos', 'compact' => false])

@if($eventos->count() > 0)
    <div class="mt-3 {{ $compact ? 'text-xs' : 'text-sm' }}">
        <details class="border border-gray-200 rounded-lg">
            <summary class="bg-gray-50 px-3 py-2 cursor-pointer hover:bg-gray-100 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-history mr-2 text-gray-600"></i>
                        <span class="font-medium text-gray-700">{{ $titulo }}</span>
                        <span class="ml-2 bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs">
                            {{ $eventos->count() }}
                        </span>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                </div>
            </summary>

            <div class="divide-y divide-gray-100 max-h-60 overflow-y-auto">
                @foreach($eventos as $evento)
                    <div class="p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-1">
                            <div class="flex items-start">
                                @php
                                    $eventIcons = [
                                        'criacao' => 'fa-plus-circle text-blue-500',
                                        'usufruto' => 'fa-check-circle text-green-500',
                                        'interrupcao' => 'fa-pause-circle text-yellow-500',
                                        'remarcacao' => 'fa-calendar-alt text-purple-500',
                                        'cancelamento' => 'fa-times-circle text-red-500',
                                        'ajuste' => 'fa-wrench text-gray-500',
                                        'importacao' => 'fa-file-import text-indigo-500'
                                    ];

                                    $icon = $eventIcons[$evento->acao] ?? 'fa-info-circle text-gray-500';
                                @endphp

                                <i class="fas {{ $icon }} mt-0.5 mr-2 {{ $compact ? 'text-sm' : 'text-base' }}"></i>

                                <div>
                                    <p class="font-medium text-gray-800 capitalize {{ $compact ? 'text-xs' : 'text-sm' }}">
                                        {{ str_replace('_', ' ', $evento->acao) }}
                                    </p>
                                    <p class="text-gray-600 mt-1 {{ $compact ? 'text-xs' : 'text-sm' }}">
                                        {{ $evento->descricao }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="inline-block bg-gray-100 text-gray-700 {{ $compact ? 'text-xs px-1 py-0.5' : 'text-xs px-2 py-1' }} rounded">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $evento->data_acao->format('d/m/Y') }}
                                </span>
                                @if($evento->data_acao->isToday())
                                    <span class="inline-block bg-green-100 text-green-700 {{ $compact ? 'text-xs px-1 py-0.5' : 'text-xs px-2 py-1' }} rounded ml-1">
                                        Hoje
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($evento->user && !$compact)
                            <div class="flex items-center text-gray-500 mt-1 {{ $compact ? 'text-xs' : 'text-sm' }}">
                                <i class="fas fa-user mr-1"></i>
                                <span>Por: {{ $evento->user->name }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </details>
    </div>
@endif
