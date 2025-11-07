<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use App\Models\VinculoFuncional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExoneracaoController extends Controller
{
    /*
     * Display a listing of the resource.
     * Lista de servidores exonerados
     */
    public function index(Request $request)
    {
        $exonerados = VinculoFuncional::with(['servidor'])
            ->whereNotNull('data_saida')
            ->where('data_saida', '<=', now())
            ->latest('data_saida')
            ->paginate(10)->withQueryString();

        $estatisticas = [
            'total_exonerados' => VinculoFuncional::whereNotNull('data_saida')->count(),
            'exonerados_mes' => VinculoFuncional::whereNotNull('data_saida')
                ->whereMonth('data_saida', now()->month)
                ->whereYear('data_saida', now()->year)
                ->count(),
            'exonerados_ano' => VinculoFuncional::whereNotNull('data_saida')
                ->whereYear('data_saida', now()->year)
                ->count(),
        ];

        $data = [
            'exonerados' => $exonerados,
            'estatisticas' => $estatisticas,
        ];

        return view('servidores.exoneracao.index', $data);

    }


    /**
     * Formulário de exoneração de servidor
     */
    public function create(Servidor $servidor)
    {
        $vinculoAtual = $servidor->vinculos()
                                ->whereNull('data_saida')
                                ->orWhere('data_saida', '>', now())
                                ->latest()
                                ->first();

        if(!$vinculoAtual) {
            flash()->error('Servidor não possui vínculo funcional ativo para exoneração.');
            return redirect()->route('servidores.show', $servidor);
        }

        $data =[
            'servidor' => $servidor,
            'vinculoAtual' => $vinculoAtual,
         ];

        return view('servidores.exoneracao.create', $data);
    }

    /**
     * Processar exoneração de servidor
     */

    public function store(Request $request, Servidor $servidor)
    {
        $request->validate([
            'data_saida' => 'required|date|before_or_equal:today',
            'numero_memorando' => 'nullable|string|max:255',
            'ato_normativo' => 'nullable|string|max:255',
            'observacao' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $vinculoAtual = $servidor->vinculos()
                                    ->whereNull('data_saida')
                                    ->orWhere('data_saida', '>', now())
                                    ->latest()
                                    ->first();

            if(!$vinculoAtual) {
                flash()->error('Servidor não possui vínculo funcional ativo para exoneração.');
                return redirect()->route('servidores.show', $servidor);
            }

            //Atualizar o vinculo atual
            $vinculoAtual->update([
                'data_saida' => $request->data_saida,
                'data_movimentacao' => $request->data_saida,
                'status' => 'Inativo',
                'tipo_movimentacao' => 'Exoneração',
                'numero_memorando' => $request->numero_memorando,
                'ato_normativo' => $request->ato_normativo,
            'observacao' => $request->observacao . ($request->observacao ? "\nMotivo: ". $request->motivo : 'Motivo: '. $request->motivo),
            ]);

            DB::commit();

            flash()->success('Servidor exonerado com sucesso!');

            return redirect()->route('servidores.show', $servidor);

        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Erro ao processar exoneração: ' . $e->getMessage());
            return redirect()->route('servidores.show', $servidor);
        }

    }

    /**
     * Restaurar servidor exonerado
     */
    public function restaurar(Request $request, VinculoFuncional $vinculo)
    {
        DB::beginTransaction();

        try {
            // Remover a data de saída para restaurar o vínculo
            $vinculo->update([
                'data_saida' => null,
                'status' => 'Ativo',
                'tipo_movimentacao' => 'Tornado sem efeito',
                'observacao' => ($vinculo->observacao ?: '') . "\nTornado sem efeito em: " . now()->format('d/m/Y'),
            ]);

            DB::commit();

            flash()->success('Servidor restaurado com sucesso!');

            return redirect()->route('servidores.show', $vinculo->servidor);

        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Erro ao restaurar servidor: ' . $e->getMessage());
            return redirect()->route('servidores.show', $vinculo->servidor);
        }
    }
}