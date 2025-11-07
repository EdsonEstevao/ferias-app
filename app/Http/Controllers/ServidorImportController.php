<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use App\Models\VinculoFuncional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServidorImportController extends Controller
{
    public function showImportForm()
    {
        return view('servidores.import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'arquivo_json' => 'required|file|mimes:json,txt|max:10240'
        ]);

        try {
            $conteudo = file_get_contents($request->file('arquivo_json')->getPathname());
            $dados = json_decode($conteudo, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()
                    ->with('error', 'JSON inválido: ' . json_last_error_msg());
            }

            // Se for um array de servidores
            if (isset($dados[0])) {
                $validados = $this->validarDadosJSON($dados);
            } else {
                // Se for um objeto com servidores
                $validados = $this->validarDadosJSON($dados['servidores'] ?? []);
            }

            return view('servidores.import', [
                'preview' => $validados['validos'],
                'erros' => $validados['erros'],
                'total' => count($dados),
                'validos' => count($validados['validos']),
                'invalidos' => count($validados['erros'])
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao ler arquivo: ' . $e->getMessage());
        }
    }

    public function process(Request $request)
    {
        $dados = json_decode($request->dados, true);

        if (empty($dados)) {
            return redirect()->route('servidores.import')
                ->with('error', 'Nenhum dado válido para importar.');
        }

        $resultado = $this->processarImportacaoJSON($dados);

        return redirect()->route('servidores.index')
            ->with('success', $this->formatarMensagemResultado($resultado));
    }

    public function downloadJsonTemplate()
    {
        $template = [
            [
                "lotacao" => "CASA CIVIL",
                "servidor" => "JOÃO SILVA",
                "matricula" => "300000001",
                "cargo" => "Assessor IX",
                "sexo" => "Masculino",
                "tipo_servidor" => ["interno"],
                "departamento" => "COMUNICAÇÃO",
                "email" => "joao.silva@example.com",
                "telefone" => "61999999999",
                "cpf" => "12345678901"
            ],
            [
                "lotacao" => "CASA CIVIL",
                "servidor" => "MARIA SANTOS",
                "matricula" => "300000002",
                "cargo" => "Assessor VIII",
                "sexo" => "Feminino",
                "tipo_servidor" => ["interno", "cedido"],
                "departamento" => "JURIDICO",
                "email" => "maria.santos@example.com",
                "telefone" => "61988888888",
                "cpf" => "98765432109"
            ]
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="template_servidores.json"',
        ];

        return response()->json($template, 200, $headers);
    }

    private function validarDadosJSON($dados)
    {
        $validos = [];
        $erros = [];

        foreach ($dados as $index => $linha) {
            $validator = Validator::make($linha, [
                'lotacao' => 'required|string|max:255',
                'servidor' => 'required|string|max:255',
                'matricula' => 'required|string|max:50',
                'cargo' => 'required|string|max:255',
                'sexo' => 'required|in:Masculino,Feminino,Outro',
                'tipo_servidor' => 'required|array',
                'tipo_servidor.*' => 'in:federal,cedido,interno,disponibilizado,regional',
                'departamento' => 'nullable|string|max:255',
                'email' => 'nullable|email',
                'telefone' => 'nullable|string|max:20',
                'cpf' => 'nullable|string|max:14'
            ], [
                'tipo_servidor.required' => 'O campo tipo_servidor é obrigatório e deve ser um array',
                'tipo_servidor.array' => 'O campo tipo_servidor deve ser um array',
                'tipo_servidor.*.in' => 'Tipo de servidor inválido. Valores permitidos: federal, cedido, interno, disponibilizado, regional'
            ]);

            if ($validator->fails()) {
                $erros[] = [
                    'linha' => $index + 1,
                    'dados' => $linha,
                    'erros' => $validator->errors()->all()
                ];
            } else {
                $validos[] = $linha;
            }
        }

        return [
            'validos' => $validos,
            'erros' => $erros
        ];
    }

    private function processarImportacaoJSON($dados)
    {
        $resultado = [
            'criados' => 0,
            'atualizados' => 0,
            'vinculos' => 0,
            'erros' => []
        ];

        DB::beginTransaction();

        try {
            foreach ($dados as $index => $dado) {
                try {
                    // Buscar ou criar servidor pela matrícula
                    $servidor = Servidor::firstOrNew(['matricula' => $dado['matricula']]);

                    $isNovo = !$servidor->exists;

                    // Atualizar dados do servidor
                    $servidor->fill([
                        'nome' => $dado['servidor'],
                        'email' => $dado['email'] ?? $servidor->email,
                        'telefone' => $dado['telefone'] ?? $servidor->telefone,
                        'cpf' => $dado['cpf'] ?? $servidor->cpf,
                    ])->save();

                    // Criar vínculo funcional
                    VinculoFuncional::create([
                        'servidor_id' => $servidor->id,
                        'lotacao' => $dado['lotacao'],
                        'cargo' => $dado['cargo'],
                        'sexo' => $dado['sexo'],
                        'tipo_servidor' => $dado['tipo_servidor'], // Já é array - cast cuida do JSON
                        'departamento' => $dado['departamento'] ?? null,
                        // Adicione outros campos conforme necessário
                    ]);

                    if ($isNovo) {
                        $resultado['criados']++;
                    } else {
                        $resultado['atualizados']++;
                    }
                    $resultado['vinculos']++;

                } catch (\Exception $e) {
                    $resultado['erros'][] = [
                        'linha' => $index + 1,
                        'matricula' => $dado['matricula'],
                        'erro' => $e->getMessage()
                    ];
                }
            }

            DB::commit();
            return $resultado;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function formatarMensagemResultado($resultado)
    {
        $mensagem = "Importação JSON concluída! ";
        $mensagem .= "✅ {$resultado['criados']} novos servidores, ";
        $mensagem .= "🔄 {$resultado['atualizados']} servidores atualizados, ";
        $mensagem .= "📋 {$resultado['vinculos']} vínculos criados.";

        if (!empty($resultado['erros'])) {
            $mensagem .= " ❌ " . count($resultado['erros']) . " erros encontrados.";
        }

        return $mensagem;
    }

    // Método alternativo para receber JSON via POST direto
    public function importarViaAPI(Request $request)
    {
        $request->validate([
            'dados' => 'required|array',
            'dados.*.lotacao' => 'required|string',
            'dados.*.servidor' => 'required|string',
            'dados.*.matricula' => 'required|string',
            // ... outras validações
        ]);

        $resultado = $this->processarImportacaoJSON($request->dados);

        return response()->json([
            'success' => true,
            'message' => $this->formatarMensagemResultado($resultado),
            'data' => $resultado
        ]);
    }
}
