<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{
    //

    public function index(Request $request)
    {
        $secretarias = Secretaria::with('secretaria_origem')->orderBy('sigla')->get();

        // dd($secretarias->first());

        return view('secretarias.index', compact('secretarias')); // response()->json($secretarias);
    }
    // view create
    public function create()
    {
        $data = [];
        $data['secretarias'] = Secretaria::orderBy('sigla')->get();

        return view('secretarias.create', $data);
    }
    // store secretaria
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:10|unique:secretarias,sigla',
            'secretaria_origem_id' => 'nullable|exists:secretarias,id',
        ]);

        $secretaria = Secretaria::create($request->all());
        flash()->success('Secretaria criada com sucesso!');
        return redirect()->route('secretarias.index');
    }

    public function edit($secretaria)
    {
        $secretaria = Secretaria::findOrFail($secretaria);

        if (!$secretaria) {
            flash()->error('Secretaria não encontrada.');
            return redirect()->route('secretarias.index');
            // abort(404);
        }

        $data = [];
        $data['secretarias'] = Secretaria::where('id', '!=', $secretaria->id)->orderBy('sigla')->get();
        $data['secretaria'] = $secretaria;

        return view('secretarias.edit', $data);
    }

    public function update(Request $request, $secretaria)
    {
        $secretaria = Secretaria::findOrFail($secretaria);

        $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:50|unique:secretarias,sigla,' . $secretaria->id,
            'secretaria_origem_id' => 'nullable|exists:secretarias,id|not_in:' . $secretaria->id,
        ]);

        $secretaria->update($request->all());
        flash()->success('Secretaria atualizada com sucesso!');
        return redirect()->route('secretarias.index');
    }

    public function destroy($secretaria)
    {
        $secretaria = Secretaria::findOrFail($secretaria);

        // Verifique se há vínculos antes de excluir
        if ($secretaria->vinculos()->exists() || $secretaria->filhos()->exists()) {
            return response()->json(['error' => 'Secretaria vinculada a outros dados.'], 400);
        }

        $secretaria->delete();

        return response()->json(['success' => true, 'messagem' => 'Secretaria excluída com sucesso.']);
    }
}
