<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoSecretariaSimbologia;
use App\Models\Secretaria;
use App\Models\Simbologia;
use Illuminate\Http\Request;

class VinculoController extends Controller
{
    //

    public function index()
    {
        $data = [];

        $data['secretarias'] = Secretaria::orderBy('sigla')->get();
        $data['cargos'] = Cargo::all();
        $data['simbologias'] = Simbologia::all();
        $data['vinculos'] = CargoSecretariaSimbologia::with(['cargo', 'secretaria', 'simbologia'])->get();

        return view('vinculos.index', $data);
    }

    public function store(Request $request)
    {
        $vinculo = CargoSecretariaSimbologia::updateOrCreate(
        [
            'secretaria_id' => intval($request->secretaria_id),
            'cargo_id' => intval($request->cargo_id),
        ],
        [
            'simbologia_id' => intval($request->simbologia_id),
        ],
        );

        $status = $vinculo->wasRecentlyCreated ? 'created' : 'updated';

        return response()->json([
            'status' => $status,
            'vinculo' => $vinculo->load(['cargo', 'secretaria', 'simbologia'])
        ]);

        // return CargoSecretariaSimbologia::with(['cargo', 'secretaria', 'simbologia'])->find($vinculo->id);
    }
    public function indexJson()
    {
        $vinculos = CargoSecretariaSimbologia::with(['cargo', 'secretaria', 'simbologia'])->get();

        return response()->json($vinculos);
    }

    // public function

    // Route::get('/api/secretarias', fn() => Secretaria::orderBy('sigla')->get());
    // Route::get('/api/cargos', fn() => Cargo::all());{
    // Route::get('/api/simbologias', fn() => Simbologia::all());
    // Route::get('/api/vinculos', fn() => CargoSecretariaSimbologia::with(['cargo', 'secretaria', 'simbologia'])->get());
    // Route::post('/api/vinculos', [VinculoController::class, 'store']);}
}