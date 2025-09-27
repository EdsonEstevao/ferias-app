<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Arial';
            font-size: 12px;
        }

        .titulo {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .registro {
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="titulo">Relatório de Movimentações Funcionais</div>

    @foreach ($movimentacoes as $mov)
        <div class="registro">
            <strong>{{ $mov->tipo_movimentacao }} — {{ $mov->cargo }}</strong><br>
            Servidor: {{ $mov->servidor->nome }}<br>
            Secretaria: {{ $mov->secretaria }}<br>
            Data: {{ \Carbon\Carbon::parse($mov->data_movimentacao)->format('d/m/Y') }}<br>
            Ato: {{ $mov->ato_normativo }}<br>
            @if ($mov->observacao)
                Obs: {{ $mov->observacao }}
            @endif
        </div>
    @endforeach
</body>

</html>
