<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Relatório de Férias - Filtro</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #2C5F91;
        }

        .filtros {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .filtros strong {
            color: #2C5F91;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #2C5F91;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 15px;
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .badge-ferias {
            background: #d4edda;
            color: #155724;
        }

        .badge-abono {
            background: #fff3cd;
            color: #856404;
        }

        .badge-usufruido {
            background: #44e868;
            color: #06501d;
            font-weight: bold;
        }

        .badge-planejado {
            background: #9addff;
            color: #022949;
            font-weight: bold;
        }

        .badge-remarcado {
            background: #fd6c05;
            color: #ffffff;
            font-weight: bold;
        }

        .badge-interrompido {
            background: #fc6868;
            color: #ffffff;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Relatório de Férias</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Filtros Aplicados -->


    @if (request()->anyFilled(['tipo', 'ano_exercicio', 'mes', 'situacao', 'busca']))
        <div class="filtros">
            <strong>Filtros Aplicados:</strong><br>
            @if (request('tipo'))
                <strong>Tipo:</strong> {{ request('tipo') == 'ferias' ? 'Férias' : 'Abono' }}
            @endif

            @if (request('ano_exercicio'))
                | <strong>Exercício:</strong> {{ request('ano_exercicio') }}
            @endif

            @if (request('mes'))
                | <strong>Mês:</strong> {{ $nomeMes }}
            @endif

            @if (request('situacao'))
                | <strong>Situação:</strong> {{ request('situacao') }}
            @endif

            @if (request('busca'))
                | <strong>Busca:</strong> "{{ request('busca') }}"
            @endif
        </div>
    @endif

    @if ($periodos->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Servidor</th>
                    <th>Matrícula</th>
                    <th>Exercício</th>
                    {{-- <th>Tipo</th> --}}
                    <th>Período</th>
                    <th>Dias</th>
                    <th>Situação</th>
                    <th>Usufruído</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($periodos as $periodo)
                    <tr>
                        <td>{{ $periodo->ferias->servidor->nome ?? 'N/A' }}</td>
                        <td>{{ $periodo->ferias->servidor->matricula ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $periodo->ferias->ano_exercicio ?? 'N/A' }}</td>
                        {{-- <td>
                            <span class="badge {{ $periodo->tipo == 'Abono' ? 'badge-abono' : 'badge-ferias' }}">
                                {{ $periodo->tipo }}
                            </span>
                        </td> --}}
                        <td style="text-align: center;">
                            {{ $periodo->inicio->format('d/m/Y') }} até<br>
                            {{ $periodo->fim->format('d/m/Y') }} </br>
                            <span class="badge {{ $periodo->tipo == 'Abono' ? 'badge-abono' : 'badge-ferias' }}">
                                {{ $periodo->tipo }}
                            </span>

                        </td>
                        <td style="text-align: center;">{{ $periodo->dias }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($periodo->situacao) }}" {{-- class="badge {{ $periodo->situacao == 'Usufruído' ? 'badge-usufruido' : 'badge-planejado' }} {{ $periodo->situacao == 'Interrompido' ? 'badge-interrompido' : $periodo->situacao }} "> --}}>
                                {{ $periodo->situacao }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if ($periodo->usufruido)
                                <span style="color: green; font-size: 20px;">✔</span>
                            @else
                                <span style="color: red; font-size: 20px;">✘</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <strong>Total de registros: {{ $periodos->count() }}</strong> |
            <strong>Total de dias: {{ $periodos->sum('dias') }}</strong> |
            <strong>Dias usufruídos: {{ $periodos->where('usufruido', true)->sum('dias') }}</strong>
        </div>
    @else
        <div style="text-align: center; padding: 20px; color: #666;">
            <p>Nenhum período encontrado com os filtros aplicados.</p>
        </div>
    @endif
</body>

</html>
