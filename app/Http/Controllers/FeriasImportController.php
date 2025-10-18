<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\Servidor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FeriasImportController extends Controller
{
    //
    public function index(Request $request) {
        return view('ferias.importCsv');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv' => 'required',
        ],
        [
            'csv.required' => 'Selecione um arquivo CSV',
        ]);

        $file = $request->file('csv');

        if(!$file->isValid() || $file->getClientOriginalExtension() != 'csv' || !$file) {
            return response()->json([
                'message' => 'Arquivo inválido',
                'status' => 422,
            ]);
        }

        $csv = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($csv); // ignora o cabeçalho

        // $servidor = Servidor::where('matricula', $header[0])->first();
        // if(!$servidor) {
        //     return response()->json([
        //         'message' => 'Matrícula inválida',
        //         'status' => 422,
        //     ]);
        // }
        $tipos = [
            "Trinta dias" => "Férias",
            "Vinte dias"  => "Férias",
            "Dez dias" => "Férias",
            "Quinze dias" => "Férias",
            "Abono" => "Abono",
        ];
        $ordem = 1;
        while(($row = fgetcsv($csv)) !== false) {
            // pegar o ano do inicio aquisitivo

            $ano = Carbon::createFromFormat('d/m/Y', $row[2])->format('Y');
            $dias = Carbon::createFromFormat('d/m/Y', $row[4])->diffInDays(Carbon::createFromFormat('d/m/Y', $row[5])) + 1;
            $inicio = Carbon::createFromFormat('d/m/Y', $row[4])->format('Y-m-d');
            $fim = Carbon::createFromFormat('d/m/Y', $row[5])->format('Y-m-d');

            $ferias = Ferias::create([
                // 'servidor_id' => $servidor->id,
                'ano_exercicio' => $ano,
            ]);
            $periodo = $ferias->periodos()->create([
                'data_inicio' => $inicio,
                'data_fim' => $fim,
                'dias' => $dias,
                'ordem' => $ordem,
            ]);

            $periodo->ferias()->create([
                'ferias_id' => $ferias->id,
                'ordem' => $ordem,
                'tipo' => $tipos[$row[6]],// $row[0],
                'dias' => $dias,
                'inicio' => $inicio,
                'fim' => $fim,
                'situacao' => "Planejado",
                'justificativa' => "Importado pelo gestor via csv",

            ]);




            $ordem++;
        }

        fclose($csv);

        flash()->success('Férias importadas com sucesso!');
        return redirect()->route('ferias.index');
    }
}
