@props(['eventos'])

@foreach ($eventos as $evento)
    <div class="p-2 mt-1 text-xs rounded bg-blue-50">
        {{ $evento->acao }} -
        {{ date('d/m/Y', strtotime($evento->data_acao)) }}
        @if ($evento->descricao)
            : {{ $evento->descricao }}
        @endif
    </div>
@endforeach
