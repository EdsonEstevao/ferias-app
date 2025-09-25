<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Férias Ativas</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    {{-- <style>
        /* body {
            font-family: serif;
            font-size: 12px;
            margin: 40px;
            color: #333;
        } */

        /* .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1a237e;
        } */

        /* .servidor {
            font-family: serif;
            font-weight: bold;
            font-size: 13px;
            background-color: #f5f5f5;
            padding: 6px 10px;
            border-left: 4px solid #1a237e;
        } */

        /* .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1a237e;
        } */

        /* .header h2 {
            font-size: 14px;
            color: #444;
        }

        .header h3 {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .sub {
            font-size: 11px;
            color: #666;
        } */

        /* .servidor {
            margin-top: 30px;
            font-weight: bold;
            font-size: 13px;
            background-color: #f5f5f5;
            padding: 6px 10px;
            border-left: 4px solid #1a237e;
        } */

        /* th {
            background-color: #e8eaf6;
            font-weight: 600;
        } */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #e0e7ff;
            font-weight: 600;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #777;
        } */

        /* .logo {
            width: 80px;
            margin-bottom: 10px;
        } */
    </style> --}}

    <style>
        .page-break {
            page-break-after: always;
        }

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

        .logo {
            width: 80px;
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

        .group-title {
            background-color: #e0e7ff;
            font-weight: bold;
            padding: 4px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/Brasão_de_Rondônia.svg') }}" class="logo">
        {{-- <img src="{{ asset('images/Brasão_de_Rondônia.png') }}" alt="Logo do Governo do Estado de Rondônia" class="logo"> --}}
        <h1>Governo do Estado de Rondônia</h1>
        <h2>Secretaria de Estado da Administração</h2>
        <h3>Relatório de Férias Ativas</h3>
        <p class="sub">
            @if ($anoExercicio)
                Exercício: {{ $anoExercicio }}
            @endif
            @if ($anoInicio)
                — Ano de início: {{ $anoInicio }}
            @endif
            @if ($mesInicio)
                — Mês: {{ str_pad($mesInicio, 2, '0', STR_PAD_LEFT) }}
            @endif
        </p>
        <p class="sub">Emitido em {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    @foreach ($servidores as $servidor)
        <div class="servidor" style="margin-top: 20px;">
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
        <hr style="height: 1px; background-color: #ccc; border: none;" />
    @endforeach
    <div class="footer">
        <p>Porto Velho, {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        <br><br><br>
        <p>__________________________________________</p>
        <p>Assinatura do Gestor</p>
        <p class="sub">Secretaria de Estado da Administração — Governo dde Rondônia</p>
        {{-- <div class="page-break"></div> --}}
    </div>


    <script src="https://kit.fontawesome.com/bf39cb216e.js" crossorigin="anonymous"></script>
</body>

</html>
