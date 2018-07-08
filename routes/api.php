<?php

use Illuminate\Http\Request;

Route::get("comprar/{marca}/{modelo}/{anos}/{codigo}", "PesquisaController@comprar");
/*
    CAMPOS INTERPRETADOS

    marca: string (*)
    modelo: string (*)
    anos: string(9) "YYYY-YYYY" (*)
    codigo: int (*)
    
    (*) Campos obrigatórios
 */ 

Route::post('pesquisar', 'PesquisaController@pesquisar');
/*
    CAMPOS INTERPRETADOS

    veiculo: "carro" || "moto" || "caminhao" (*)
    estado_conservacao: "0km" || "seminovo"
    marca: string (*)
    modelo: string (*)
    cidade: int
    valor1: int
    valor2: int
    ano1: int
    ano2: int
    usuario: "particular" || "revenda"
    pagina: int  

    (*) Campos obrigatórios
    (**) Decidi utilizar o método http POST por causa da grande quantidade de parâmetros não obrigatórios, declaro a ciência de que o método http GET seria o mais conveniente para retornar dados de acordo com parâmetros providos.
    Route::get('pesquisar/{veiculo}/{marca}/{modelo}/{estado_conservacao?}/{cidade?}/{valor1?}/{valor2?}/{ano1?}/{ano2?}/{usuario?}/{pagina?}', 'PesquisaController@pesquisar');
 */ 