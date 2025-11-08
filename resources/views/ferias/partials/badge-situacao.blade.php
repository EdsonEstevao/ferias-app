@props(['situacao'])

@php
    $config = [
        'Planejado' => [
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-800',
            'border' => 'border-blue-200',
            'icon' => 'fa-calendar-plus',
        ],
        'Usufruido' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-800',
            'border' => 'border-green-200',
            'icon' => 'fa-check-circle',
        ],
        'Interrompido' => [
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-800',
            'border' => 'border-yellow-200',
            'icon' => 'fa-pause-circle',
        ],
        'Remarcado' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-800',
            'border' => 'border-purple-200',
            'icon' => 'fa-calendar-alt',
        ],
    ];

    $style = $config[$situacao] ?? $config['Planejado'];
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {$style['bg']} {$style['text']} {$style['border']}"]) }}>
    <i class="fas {{ $style['icon'] }} mr-1"></i>
    {{ $situacao }}
</span>
