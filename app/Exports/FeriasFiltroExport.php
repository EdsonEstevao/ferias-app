<?php

namespace App\Exports;

use App\Models\FeriasPeriodos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class FeriasFiltroExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = FeriasPeriodos::with(['ferias.servidor'])
            ->where('ativo', true);

        // Aplicar os mesmos filtros da view
        if ($this->request->filled('tipo')) {
            if ($this->request->tipo == 'ferias') {
                $query->where('tipo', '!=', 'Abono');
            } else {
                $query->where('tipo', 'Abono');
            }
        }

        if ($this->request->filled('ano_exercicio')) {
            $query->whereHas('ferias', function($q) {
                $q->where('ano_exercicio', $this->request->ano_exercicio);
            });
        }

        if ($this->request->filled('mes')) {
            $query->where(function($q) {
                $q->whereMonth('inicio', $this->request->mes)
                  ->orWhereMonth('fim', $this->request->mes);
            });
        }

        if ($this->request->filled('situacao')) {
            $query->where('situacao', $this->request->situacao);
        }

        if ($this->request->filled('busca')) {
            $busca = $this->request->busca;
            $query->whereHas('ferias.servidor', function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('matricula', 'like', "%{$busca}%")
                  ->orWhere('cpf', 'like', "%{$busca}%");
            });
        }

        return $query->orderBy('inicio', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Servidor',
            'Matrícula',
            'CPF',
            'Ano Exercício',
            'Tipo',
            'Período Início',
            'Período Fim',
            'Dias',
            'Situação',
            'Usufruído',
            'Data Usufruto',
            'Observações'
        ];
    }

    public function map($periodo): array
    {
        return [
            $periodo->ferias->servidor->nome,
            $periodo->ferias->servidor->matricula,
            $periodo->ferias->servidor->cpf,
            $periodo->ferias->ano_exercicio,
            $periodo->tipo,
            $periodo->inicio->format('d/m/Y'),
            $periodo->fim->format('d/m/Y'),
            $periodo->dias,
            $periodo->situacao,
            $periodo->usufruido ? 'Sim' : 'Não',
            $periodo->data_usufruto ? $periodo->data_usufruto->format('d/m/Y') : '',
            $periodo->justificativa
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para o cabeçalho
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2C5F91']]
            ],
            // Estilo para as células de dados
            'A:L' => [
                'alignment' => ['vertical' => 'center']
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Servidor
            'B' => 15, // Matrícula
            'C' => 15, // CPF
            'D' => 12, // Ano Exercício
            'E' => 10, // Tipo
            'F' => 12, // Período Início
            'G' => 12, // Período Fim
            'H' => 8,  // Dias
            'I' => 15, // Situação
            'J' => 12, // Usufruído
            'K' => 15, // Data Usufruto
            'L' => 40, // Observações
        ];
    }
}
