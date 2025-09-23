<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Férias Ativas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .servidor {
            margin-top: 30px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

    <div class="header">
        <p>
            @if ($anoExercicio)
                Exercício: {{ $anoExercicio }}
            @endif

            @if ($anoInicio)
                — Ano de início: {{ $anoInicio }}
            @endif

            @if ($mesInicio)
                — Mês de início: {{ str_pad($mesInicio, 2, '0', STR_PAD_LEFT) }}
            @endif
        </p>
        <h1>Governo do Estado de Rondônia</h1>
        <h2>🗓️ Relatório de Férias Ativas</h2>
        <p>Emitido em {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    @foreach ($servidores as $servidor)
        <div class="servidor">
            {{ $servidor->nome }} — Matrícula: {{ $servidor->matricula }}
        </div>

        @foreach ($servidor->ferias as $ferias)
            <table>
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Dias</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ferias->periodos->sortBy('ordem') as $p)
                        <tr>
                            <td>{{ $p->ordem }}º</td>
                            <td>{{ \Carbon\Carbon::parse($p->inicio)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->fim)->format('d/m/Y') }}</td>
                            <td>{{ $p->dias }}</td>
                            <td>{{ $p->situacao }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach
    <script src="https://kit.fontawesome.com/bf39cb216e.js" crossorigin="anonymous"></script>
</body>

</html>
