<?php

namespace App\Http\Controllers;

use App\Models\FeriasEvento;
use App\Models\FeriasPeriodos;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeriasPeriodosController extends Controller
{
    //
    /** */
    public function index()
    {

    }
     public function store(Request $request): JsonResponse
    {
        $request->validate([
            'ferias_id' => 'required|exists:ferias,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'dias' => 'required|integer|min:1',
            'observacao' => 'nullable|string|max:500'
        ]);

        try {
            $periodo = FeriasPeriodos::create($request->all());

            return response()->json([
                'message' => 'Período criado com sucesso!',
                'periodo' => $periodo
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar período',
                'error' => $e->getMessage()
            ], 500);
        }
    }
     public function update(Request $request, $id): JsonResponse
    {

        // dd($request->all());
        $request->validate([
            'inicio' => 'required|date',
            'fim' => 'required|date|after_or_equal:inicio',
            'justificativa' => 'nullable|string|max:500'
        ]);

        $dias = Carbon::parse($request->inicio)->diffInDays($request->fim) + 1;

        $request['dias'] = $dias;

        try {
            $periodo = FeriasPeriodos::findOrFail($id);
            $periodo->update($request->all());



            flash()->success('Período atualizado com sucesso!');

            return response()->json([
                'message' => 'Período atualizado com sucesso!',
                'periodo' => $periodo
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar período',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAjax(Request $request, $id): JsonResponse
    {
        // dd($request->all(), $id);

        $periodo = FeriasPeriodos::findOrFail($id);
        if($periodo->podeSerUsufruido()){

            $periodo->marcarComoUsufruido();
            return response()->json([
                'message' => 'Período atualizado com sucesso!',
                'periodo' => $periodo
            ]);
        }else{
            return response()->json([
                'message' => 'Período não pode ser usufruido',
                'periodo' => $periodo
            ]);
        }

    }
    public function marcarComoUsufruido($id)
    {
        $periodo = FeriasPeriodos::findOrFail($id);

        // Verificar se pode marcar como usufruído
        if (!$periodo->ativo) {
            flash()->error('Período inativo não pode ser usufruído');
            return response()->json(['error' => 'Período inativo não pode ser usufruído'], 422);
        }

        if ($periodo->usufruido) {
            flash()->error('Período já está marcado como usufruído');
            return response()->json(['error' => 'Período já está marcado como usufruído'], 422);
        }

        DB::transaction(function () use ($periodo) {
            // Marcar o período atual como usufruído
            $periodo->marcarComoUsufruido();

            // Se este período tem um pai, verificar a hierarquia completa
            if ($periodo->periodo_origem_id) {
                $this->atualizarHierarquiaAscendente($periodo->periodo_origem_id);
            }

            // Registrar evento
            // $periodo->eventos()->create([
            //     'acao' => 'Usufruto',
            //     'data_acao' => now(),
            //     'descricao' => 'Período marcado como usufruído',
            //     'user_id' => Auth::id(),
            // ]);
        });

        flash()->success('Período marcado como usufruído com sucesso!');
        return response()->json(['success' => true]);
    }

    public function desmarcarUsufruto($id)
    {
        $periodo = FeriasPeriodos::findOrFail($id);

        if (!$periodo->usufruido) {
            flash()->error('Período não está marcado como usufruído');
            return response()->json(['error' => 'Período não está marcado como usufruído'], 422);
        }

        DB::transaction(function () use ($periodo) {
            // Desmarcar o período atual
            $periodo->desmarcarUsufruto();

            // Se este período tem um pai, desmarcar toda a hierarquia ascendente
            if ($periodo->periodo_origem_id) {
                $this->desmarcarHierarquiaAscendente($periodo->periodo_origem_id);
            }

            // // Registrar evento
            // $periodo->eventos()->create([
            //     'acao' => 'Desmarcar Usufruto',
            //     'data_acao' => now(),
            //     'descricao' => 'Usufruto desmarcado do período',
            //     'user_id' => Auth::id(),
            // ]);
        });

        flash()->success('Usufruto desmarcado com sucesso!');
        return response()->json(['success' => true]);
    }

    /**
     * Atualizar hierarquia ascendente (pai, avô, bisavô, etc.)
     */
    private function atualizarHierarquiaAscendente($periodoPaiId)
    {
        $periodoPai = FeriasPeriodos::find($periodoPaiId);
        if (!$periodoPai) return;

        // Verificar se TODOS os filhos deste pai estão usufruídos
        $todosFilhosUsufruidos = FeriasPeriodos::where('periodo_origem_id', $periodoPaiId)
            ->where('ativo', true)
            ->where('usufruido', false)
            ->doesntExist();

        if ($todosFilhosUsufruidos) {
            // Marcar o pai como usufruído
            $periodoPai->marcarComoUsufruido();

            // Recursivamente verificar o avô
            if ($periodoPai->periodo_origem_id) {
                $this->atualizarHierarquiaAscendente($periodoPai->periodo_origem_id);
            }
        }
    }

    /**
     * Desmarcar hierarquia ascendente (pai, avô, bisavô, etc.)
     */
    private function desmarcarHierarquiaAscendente($periodoPaiId)
    {
        $periodoPai = FeriasPeriodos::find($periodoPaiId);
        if (!$periodoPai) return;

        // Desmarcar o pai (se um filho não está usufruído, o pai não pode estar)
        $periodoPai->desmarcarUsufruto();

        // Recursivamente desmarcar o avô
        if ($periodoPai->periodo_origem_id) {
            $this->desmarcarHierarquiaAscendente($periodoPai->periodo_origem_id);
        }
    }

    public function destroy(Request $request,  $periodo): JsonResponse
    {
        $p = FeriasPeriodos::findOrFail($periodo);

        if($p->usufruido){
            flash()->error('Período usufruido, impossível excluir');
            return response()->json(['error' => 'Período usufruido, impossível excluir'], 422);
        }
        if($p->filhos()->count() > 0){
            flash()->error('Período possui filhos, impossível excluir');
            return response()->json(['error' => 'Período possui filhos, impossível excluir'], 422);
        }

        try {

            // Excluir eventos primeiro
            FeriasEvento::where('ferias_periodo_id', $p->periodo_origem_id)->delete();


            // Marcar período Pai como ativo
            if($p->periodo_origem_id > 0){
                $p->origem()->update([
                    'ativo' => true
                ]);
            }

            $p->delete();


            flash()->success('Período excluído com sucesso!');
            return response()->json([
                'message' => 'Período excluído com sucesso!'
            ]);

        } catch (Exception $e) {

            flash()->error('Erro ao excluir período');
            return response()->json([
                'message' => 'Erro ao excluir período',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar se período pode ser convertido em abono
     */

    public function podeConverterAbono($id): JsonResponse
    {
        try{
            $periodo = FeriasPeriodos::findOrFail($id);

            return response()->json([
              'pode_converter' => $periodo->podeSerConvertidoEmAbono(),
              'periodo'   => [
                'id' => $periodo->id,
                'situacao' => $periodo->situacao,
                'usufruido' => $periodo->usufruido,
                'convertido_abono' => $periodo->convertido_abono,
                'ativo' => $periodo->ativo,
                'dias' => $periodo->dias,
                'inicio' => $periodo->inicio_formatado,
                'fim' => $periodo->fim_formatado,
              ]
            ]);

        }catch(Exception $e){
            return response()->json([
                'pode_converter' => false,
                'message' => 'Erro ao verificar período',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    /**
     * Obter informações do período para converter em abono
     */

    public function infoConversaoAbono($id): JsonResponse
    {
        try{
            $periodo = FeriasPeriodos::with(['ferias.servidor'])->findOrFail($id);

            return response()->json([
                'periodo' => [
                    'id' => $periodo->id,
                    'servidor' => $periodo->ferias->servidor->nome,
                    'servidor_matricula' => $periodo->ferias->servidor->matricula,
                    'ano_exercicio' => $periodo->ferias->ano_exercicio,
                    'dias' => $periodo->dias,
                    'inicio' => $periodo->inicio_formatado,
                    'fim' => $periodo->fim_formatado,
                    'situacao' => $periodo->situacao,
                    'pode_converter' => $periodo->podeSerConvertidoEmAbono(),
                ]
            ]);

        }catch(Exception $e){
            return response()->json([
                'error' => 'Error ao obter informações do período: '. $e->getMessage()
            ], 500);
        }

    }
}
