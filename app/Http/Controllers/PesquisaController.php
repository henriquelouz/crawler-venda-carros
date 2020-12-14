<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Crawler;

class PesquisaController extends Controller
{
    public function pesquisar(Request $request){
        $crawler = new Crawler();
        $resultados = $crawler->pesquisaTodos($request);
        
        return $resultados;
    }

    public function comprar(Request $request){
        $crawler = new Crawler();
        $resultado = $crawler->comprar($request);

        return $resultado;
    }
}
