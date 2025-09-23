{{-- <!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Férias Fracionadas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>Férias Fracionadas</h2>
    <p><strong>Servidor:</strong> {{ $servidor->nome }}</p>
    <p><strong>Matrícula:</strong> {{ $servidor->matricula }}</p>
    <p><strong>Departamento:</strong> {{ $servidor->departamento }}</p>
    @foreach ($ferias as $registro)
        <h3>Ano: {{ $registro->ano_exercicio }}</h3>

        @foreach ($registro->periodosAgrupados as $origemId => $grupo)
            <h4 style="margin-top: 20px; font-weight: bold;">
                {{ $origemId ? 'Fracionamento do período original #' . $origemId : 'Período original' }}
            </h4>

            <table>
                <thead>
                    <tr>
                        <th>Ordem</th>
                        <th>Período</th>
                        <th>Dias</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grupo as $periodo)
                        <tr>
                            <td>{{ $periodo->ordem }}</td>
                            <td>{{ date('d/m/Y', strtotime($periodo->inicio)) }} a
                                {{ date('d/m/Y', strtotime($periodo->fim)) }}</td>
                            <td>{{ $periodo->dias }}</td>
                            <td>{{ $periodo->situacao }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach

</body>

</html> --}}


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Férias Fracionadas</title>
    {{-- icons fontwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

    {{-- Cabeçalho institucional --}}
    <div class="header">
        {{-- <img src="{{ public_path('logo.png') }}" class="logo"> --}}
        <h1>Governo do Estado de Rondônia</h1>
        <h2>Departamento de Recursos Humanos</h2>
        {{-- imagem de férias --}}

        <h3> <i fa-solid fa-sun fa-2xl></i> Relatório de Férias Fracionadas</h3>
    </div>

    {{-- Dados do servidor --}}
    <p><strong>Servidor:</strong> {{ $servidor->nome }}</p>
    <p><strong>Matrícula:</strong> {{ $servidor->matricula }}</p>
    <p><strong>CPF:</strong> {{ $servidor->cpf }}</p>
    <p><strong>Departamento:</strong> {{ $servidor->departamento }}</p>

    {{-- Períodos agrupados --}}
    @foreach ($ferias as $registro)
        <h4 style="margin-top: 20px;">Ano de exercício: {{ $registro->ano_exercicio }}</h4>

        @foreach ($registro->periodos->sortBy('ordem')->groupBy('periodo_origem_id') as $origemId => $grupo)
            <div class="group-title">
                {{ $origemId ? 'Fracionamento do período original #' . $origemId : 'Período original' }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Período</th>
                        <th>Tipo</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Dias</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grupo as $p)
                        <tr>
                            <td>{{ $p->ordem }}º</td>
                            <td>{{ $p->tipo }}</td>
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

    {{-- Rodapé --}}
    <div class="footer">
        <p>Porto Velho, {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        <br><br><br>
        <p>__________________________________________</p>
        <p>Assinatura do Gestor</p>
    </div>
    <script src="https://kit.fontawesome.com/bf39cb216e.js" crossorigin="anonymous"></script>
</body>

</html>
