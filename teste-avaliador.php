<?php

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

//Criamos um cenário de testes 
$leilao = new Leilao('Fiat 147 0KM');

$maria = new Usuario('Maria');
$joao = new Usuario('João');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();
$valorEsperado = 2500; 

//Executamos o código a ser testado
$leiloeiro->avalia($leilao);
$maiorValor = $leiloeiro->getMaiorValor();

//E verificamos se a saída é a esperada
if ($maiorValor == $valorEsperado){
    echo "TESTE OK";
} else {
    echo "TESTE FALHOU";
}
