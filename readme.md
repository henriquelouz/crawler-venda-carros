Teste Backend Alpes One

## Proposta

Desenvolver endpoints RESTful em PHP com a finalidade de extrair dados do site [seminovosbh.com.br].

Os endpoints propostos devem:
- Procurar por carros de acordo com os filtros existentes
- Consultar os detalhes do anúncio escolhido

## Especificações

-Feito em PHP 7.1.3
-Laravel 5.6

## Como testar

1. Após baixar o código deste repositório, extraia os arquivos e mova-os para onde desejar
2. Abra o terminal de comandos, aponte para a pasta escolhida e rode o comando [composer install]
3. Rode o comando [php artisan serve] para que o servidor de desenvolvimento do Laravel seja iniciado
4. Utilize um ambiente de desenvolvimento de APIs (como o Postman) para efetuar requisições para o endereço [localhost:8000/api/]

## Rotas

[GET] api/comprar/{marca}/{modelo}/{anos}/{codigo}
Rota utilizada para buscar os detalhes de um anúncio específico.

-Entrada
marca: string
modelo: string
anos: string(9) "YYYY-YYYY"
codigo: int

-Saída
Json: {
    "imagens": [string],
    "detalhes": [string],
    "acessorios": [string],
    "observacoes": string,
    "informacoes": [string]
}


[POST] api/pesquisar
Rota utilizada para buscar a lista de anúncios que correspondem aos parâmetros fornecidos

-Entrada
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

-Saída
Json: {
    {
        "totalpaginas": string
    },
    {
        "codigo": string,
        "titulo": string
    }...
}

## Agradecimentos
À equipe Alpes One pela oportunidade e pelo desafio, foi no mínimo interessante realizá-lo.