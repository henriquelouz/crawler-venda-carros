<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    public static function pesquisaTodos($request){

        //Monta a raiz da url
        $externa = "https://www.seminovosbh.com.br/resultadobusca/index/";

        //Filtro veículo
        if($request->veiculo == "carro")
            $externa .= "veiculo/carro/";
        if($request->veiculo == "moto")
            $externa .= "veiculo/moto/";
        if($request->veiculo == "caminhao")
            $externa .= "veiculo/caminhao/";
        
        //Filtro estado de conservação
        if($request->estado_conservacao == "0km")
            $externa .= "estado-conservacao/0km/";
        if($request->estado_conservacao == "seminovo")
            $externa .= "estado-conservacao/seminovo/";

        //Filtro de marca e modelo
        $externa .= "marca/".$request->marca."/";
        $externa .= "modelo/".$request->modelo."/";

        //Filtro de cidade
        if(isset($request->cidade))
            $externa .= "cidade/".$request->cidade."/";
        
        //Filtro de valores
        if(isset($request->valor1) && isset($request->valor2))
            $externa .= "valor1/".$request->valor1."/valor2/".$request->valor2."/";
        
        //Filtro de anos
        if(isset($request->ano1) && isset($request->ano2))
            $externa .= "ano1/".$request->ano1."/ano2/".$request->ano2."/";
        
        //Filtro de tipo de usuário
        if($request->usuario == "particular")
            $externa .= "usuario/particular/";
        else if($request->usuario == "revenda")
            $externa .= "usuario/revenda/";
        else 
            $externa .= "usuario/todos/";
        
        //Maior quantidade de registros possíveis
        $externa .= "qtdpag/50/";

        //Escolhe a página
        if(isset($request->pagina))
            $externa .= "pagina/".$request->pagina."/";
        
        //Extrai os dados da página e retira deles a lista de carros resultante
        $resultados = file_get_contents($externa);
        $resultados_filtro = explode('<dd class="titulo-busca">', $resultados);
        unset($resultados_filtro[0]);

        //Pega a quantidade total de páginas da consulta
        $aux1 = explode('<strong class="total">', $resultados);
        $aux2 = explode('</strong>', $aux1[1]);
        $totalPaginas = $aux2[0];

        //Cria o array que será utilizado como retorno e informa na primeira posição qual a quantidade de páginas para o usuário navegar entre os resultados
        $compilado = array();
        $compilado[0] = array("totalpaginas" => $totalPaginas);
        $i = 1;

        //Para cada resultado, separa-se o título e o código do anúncio para utilizar no outro endpoint 
        foreach($resultados_filtro as $resultado){
            $resto = explode('</dd>', $resultado);
            $linha = $resto[0];

            $arrayCodigo = explode('/', $linha);
            $codigo = $arrayCodigo[1]."/".$arrayCodigo[2]."/".$arrayCodigo[3]."/".$arrayCodigo[4]."/".$arrayCodigo[5]."/";

            $aux1 = explode('<h4>', $linha);
            $aux2 = explode('</h4>', $aux1[1]);
            $titulo = strip_tags($aux2[0]);

            $compilado[$i] = array("codigo"=> $codigo, "titulo" => $titulo);
            $i++;
        }

        return response()->json($compilado, 200);
    }

    public static function comprar($request){

        //Monta a url
        $externa = "https://www.seminovosbh.com.br/comprar/";
        $externa = $externa.$request->marca."/".$request->modelo."/".$request->anos."/".$request->codigo;
        
        //Extrai a página
        $resultados = file_get_contents($externa);

        //Extrai as imagens do carro
        $aux1 = explode('<div id="conteudoVeiculo">', $resultados);
        $aux2 = explode('<div id="fotosSlide"', $aux1[1]);
        $imagens = explode('<img src="h', $aux2[0]);
        unset($imagens[0]);
        $arrayImagens = array();
        $i = 0;

        foreach($imagens as $imagem){
            $linha = explode('" class="img_borda', $imagem);
            $arrayImagens[$i] = "h".$linha[0];
            $i++;
        }
        $compilado['imagens'] = $arrayImagens;

        //Extrai os detalhes do anúncio
        $aux1 = explode('<div id="infDetalhes" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $detalhes = explode('<li>', $aux2[0]);
        unset($detalhes[0]);
        $arrayDetalhes = array();
        $i = 0;

        foreach($detalhes as $detalhe){
            $linha = explode('</li>', $detalhe);
            $arrayDetalhes[$i] = $linha[0];
            $i++;
        }
        $compilado['detalhes'] = $arrayDetalhes;

        //Extrai os acessórios do carro
        $aux1 = explode('<div id="infDetalhes2" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $acessorios = explode('<li>', $aux2[0]);
        unset($acessorios[0]);
        $arrayAcessorios = array();
        $i = 0;

        foreach($acessorios as $acessorio){
            $linha = explode('</li>', $acessorio);
            $arrayAcessorios[$i] = $linha[0];
            $i++;
        }
        $compilado['acessorios'] = $arrayAcessorios;

        //Extrai as observações do anúncio
        $aux1 = explode('<div id="infDetalhes3" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $observacoes = explode('<p>', $aux2[0]);
        $linha = explode('</p>', $observacoes[1]);
        $observacao = $linha[0];

        $compilado['observacoes'] = $observacao;

        //Extrai as informações do anúncio
        $aux1 = explode('<div id="infDetalhes4" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $informacoes = explode('<li', $aux2[0]);
        unset($informacoes[0]);
        $arrayInformacoes = array();
        $i = 0;

        foreach($informacoes as $informacao){
            $linha = explode('</li>', $informacao);
            $linhaInformacao = "<li".$linha[0];
            $linhaInformacao = strip_tags($linhaInformacao);
            $linhaInformacao = str_replace("\n","", $linhaInformacao);
            $linhaInformacao = str_replace("&nbsp;","", $linhaInformacao);
            $linhaInformacao = trim($linhaInformacao);
            $arrayInformacoes[$i] = $linhaInformacao;
            $i++;
        }
        $compilado['informacoes'] = $arrayInformacoes;

        return response()->json($compilado, 200);
    }
}

