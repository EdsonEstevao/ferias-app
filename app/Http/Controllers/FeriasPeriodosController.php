<?php

namespace App\Http\Controllers;

use App\Models\FeriasPeriodos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        } catch (\Exception $e) {
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
            'dias' => 'required|integer|min:1',
            'justificativa' => 'nullable|string|max:500'
        ]);

        try {
            $periodo = FeriasPeriodos::findOrFail($id);
            $periodo->update($request->all());

            return response()->json([
                'message' => 'Período atualizado com sucesso!',
                'periodo' => $periodo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar período',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $periodo = FeriasPeriodos::findOrFail($id);
            $periodo->delete();

            return response()->json([
                'message' => 'Período excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir período',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
