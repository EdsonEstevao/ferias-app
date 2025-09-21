<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use Illuminate\Http\Request;

class ServidorController extends Controller
{
    //
    public function index(){
        $data = [];
        $servidores = Servidor::all();

        $data = [
            'servidores' => $servidores
        ];

        return view('servidores.index', $data);

    }

}