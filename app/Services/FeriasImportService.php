<?php

namespace App\Services;

use App\Models\Servidor;
use App\Models\Ferias;
use App\Models\FeriasPeriodos;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FeriasImportService
{
    public function importarDeJson($dados, $anoExercicio)
    {
        DB::beginTransaction();

        try {
            $importados = 0;
            $erros = [];

            foreach ($dados as $index => $linha) {
                try {
                    $this->processarLinha($linha, $anoExercicio);
                    $importados++;
                } catch (\Exception $e) {
                    $erros[] = [
                        'linha' => $index + 1,
                        'servidor' => $linha['nome'] ?? 'N/A',
                        'erro' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'importados' => $importados,
                'erros' => $erros,
                'total' => count($dados),
                'message' => "Importação concluída: {$importados} de " . count($dados) . " registros processados."
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Erro na transação: ' . $e->getMessage()
            ];
        }
    }

    private function processarLinha($linha, $anoExercicio)
    {
        // Buscar servidor
        $servidor = Servidor::where('matricula', $linha['matricula'])
                           ->orWhere('nome', 'LIKE', "%{$linha['nome']}%")
                           ->first();
        $url = $linha['url'] ?? null;
        $title = $linha['portaria'] ?? null;

        if (!$servidor) {
            throw new \Exception("Servidor não encontrado");
        }

        // Verificar se já existe férias para este ano
        $feriasExistente = Ferias::where('servidor_id', $servidor->id)
                                ->where('ano_exercicio', $anoExercicio)
                                ->first();

        if ($feriasExistente) {
            throw new \Exception("Já existem férias cadastradas para este ano");
        }

        // Criar férias
        $ferias = Ferias::create([
            'servidor_id' => $servidor->id,
            'ano_exercicio' => $anoExercicio,
            'situacao' => 'Planejado',
        ]);

        // Criar períodos de férias
        if (!empty($linha['periodos_ferias'])) {
            $this->criarPeriodos($ferias, $linha['periodos_ferias'], 'ferias', $url, $title);
        }

        // Criar períodos de abono
        if (!empty($linha['periodos_abono'])) {
            $this->criarPeriodos($ferias, $linha['periodos_abono'], 'abono', $url, $title);
        }
    }

    private function criarPeriodos($ferias, $periodos, $tipo, $url, $title)
    {
        foreach ($periodos as $index => $periodo) {
            // Validar dados do período
            if (empty($periodo['inicio']) || empty($periodo['fim'])) {
                continue;
            }

            $inicio = $periodo['inicio'];
            $fim = $periodo['fim'];

            // Calcular dias se não informado
            $dias = $periodo['dias'] ?? Carbon::parse($inicio)->diffInDays(Carbon::parse($fim)) + 1;

            FeriasPeriodos::create([
                'ferias_id' => $ferias->id,
                'ordem' => $index + 1,
                'tipo' => $tipo === 'abono' ? 'Abono' : 'Férias',
                'dias' => $dias,
                'inicio' => $inicio,
                'fim' => $fim,
                'ativo' => true,
                'situacao' => 'Planejado',
                'usufruido' => false,
                'title' => $title ?? null,
                'url' => $url ?? null,
                'justificativa' => 'Planejamento Anual de Férias de '. $ferias->ano_exercicio,
            ]);
        }
    }
}