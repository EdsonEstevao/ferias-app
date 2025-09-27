<?php

namespace App\Exports;

use App\Models\VinculoFuncional;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MovimentacoesExport implements FromCollection, WithHeadings
{
    protected $movimentacoes;

    public function __construct($movimentacoes)
    {
        $this->movimentacoes = $movimentacoes;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return VinculoFuncional::all();
        return collect($this->movimentacoes)->map(function ($movimentacao) {
            return [
                'servidor' => $movimentacao->servidor->nome,
                'cargo' => $movimentacao->cargo,
                'secretaria' => $movimentacao->secretaria,
                'tipo_movimentacao' => $movimentacao->tipo_movimentacao,
                'data_movimentacao' => Carbon::parse($movimentacao->data_movimentacao)->format('d/m/Y'), //  $movimentacao->data_movimentacao,
                'ato_normativo' => $movimentacao->ato_normativo,
                'observacao' => $movimentacao->observacao
            ];
        });
    }

    public function headings(): array
    {
        return ['Servidor', 'Cargo', 'Secretaria', 'Tipo', 'Data', 'Ato Normativo', 'Observação'];
    }
}
