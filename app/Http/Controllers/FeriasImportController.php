<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\Servidor;
use App\Services\FeriasImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FeriasImportController extends Controller
{
    private $feriasImportService;
    //
    public function __construct(FeriasImportService $feriasImportService)
    {
        $this->feriasImportService = $feriasImportService;
    }

    public function index(Request $request) {
        return view('ferias.importCsv');
    }
    public function indexJson(Request $request)
    {

        return view('ferias-import.index');


    }

    public function createJson(Request $request) {
        return view('ferias-import.create');
    }

    public function storeJson(Request $request)
    {

          $request->validate([
            'dados_json' => 'required|string',
            'ano_exercicio' => 'required|integer|min:2020|max:2030',
        ]);

        try {
            // Decodificar JSON
            $dados = json_decode($request->dados_json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'JSON inválido: ' . json_last_error_msg()
                ], 422);
            }

            // Validar estrutura básica
            if (!$this->validarEstruturaJson($dados)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estrutura do JSON inválida. Verifique o formato.'
                ], 422);
            }

            // Processar importação
            $resultado = $this->feriasImportService->importarDeJson(
                $dados,
                $request->ano_exercicio
            );

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro no processamento: ' . $e->getMessage()
            ], 500);
        }

    }
      /**
     * Validar estrutura básica do JSON
     */
    private function validarEstruturaJson($dados)
    {
        if (!is_array($dados)) {
            return false;
        }

        // Verificar se é uma lista de servidores
        foreach ($dados as $item) {
            if (!isset($item['nome']) || !isset($item['matricula'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Template de JSON para ajudar os usuários
     */
    public function template()
    {
        $template = [
            [
                "nome" => "FULANO DE TAL SILVA",
                "matricula" => "300023456",
                "periodos_ferias" => [
                    [
                        "inicio" => "2024-01-10",
                        "fim" => "2024-01-19",
                        "dias" => 10
                    ],
                    [
                        "inicio" => "2024-07-15",
                        "fim" => "2024-07-24",
                        "dias" => 10
                    ]
                ],
                "periodos_abono" => [
                    [
                        "inicio" => "2024-02-01",
                        "fim" => "2024-02-10",
                        "dias" => 10
                    ]
                ]
            ],
            [
                "nome" => "BELTRANA SANTOS OLIVEIRA",
                "matricula" => "300023457",
                "periodos_ferias" => [
                    [
                        "inicio" => "2024-03-01",
                        "fim" => "2024-03-30",
                        "dias" => 30
                    ]
                ],
                "periodos_abono" => []
            ]
        ];

        return response()->json($template, 200, [], JSON_PRETTY_PRINT);
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
