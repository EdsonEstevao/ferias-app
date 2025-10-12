<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoSecretariaSimbologia;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    //
    public function getCargosBySecretaria($secretariaId)
    {
        // $cargos = Cargo::whereHas('CargoSecretariaSimbologias', function ($query) use ($secretariaId) {
        //     $query->where('secretaria_id', $secretariaId);
        // })->get();
        // $cargos = CargoSecretariaSimbologia::with(['cargo', 'simbologia'])
        //     ->where('secretaria_id', $secretariaId)
        //     ->get()
        //     ->pluck('cargo')
        //     ->unique('id')
        //     ->values();
        $cargos = CargoSecretariaSimbologia::with(['cargo', 'simbologia'])
            ->where('secretaria_id', $secretariaId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->cargo->id,
                    'nome' => $item->cargo->nome,
                    'simbologia' => $item->simbologia ? $item->simbologia->nome : null,
                ];
            })
            ->unique('id')
            ->values();

        // dd($cargos);

        return response()->json(['cargos' => $cargos]);
    }
}