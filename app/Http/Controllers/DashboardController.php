<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\FeriasPeriodos;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Últimos lançamentos de férias (ordenados por data de criação)
        $ultimosLancamentos = Ferias::with('servidor', 'periodos')
        ->orderByDesc('created_at')
        ->take(10)
        ->get();


        $meses = collect(range(1, 12))->map(function ($mes) {
            return Carbon::create()->month($mes)->translatedFormat('F');
        });

        // dd($meses);

        $dadosPorMes = collect(range(1, 12))->map(function ($mes) {
            return FeriasPeriodos::whereMonth('inicio', $mes)->count();
        });

        $data = [
            'ultimos' => $ultimosLancamentos,
            'meses' => $meses,
            'dadosPorMes' => $dadosPorMes
        ];

        return view('dashboard', $data);
        // return view('dashboard');
    }
}