<?php

namespace Alura\Leilao\Tests\Service;

use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;

    protected function setUp() : void
    {
       $this->leiloeiro = new Avaliador();
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->receberLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    public function testleilaoVazioNaoPodeSerAvaliado()
    {

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão vazio');

        $leilao = new Leilao('Fusca Azul');


        $this->leiloeiro->avalia($leilao);
    }

    /**
     * @dataProvider entregaLeilaoCrescente
     * @dataProvider entregaLeilaoDecrescente
     * @dataProvider entregaLeilaoAleatorio
     */
    public function testAvaliadordoMaiorValor(Leilao $leilao)
    {
        //Criamos um cenário de testes - Given
        $valorEsperado = 8000;

        //Executamos o código a ser testado - When
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->getMaiorValor();

        //E verificamos se a saída é a esperada - Then
        $this->assertEquals($valorEsperado, $maiorValor);
    }

    /**
     * @dataProvider entregaLeilaoCrescente
     * @dataProvider entregaLeilaoDecrescente
     * @dataProvider entregaLeilaoAleatorio
     */
    public function testAvaliadordoMenorValor(Leilao $leilao)
    {
        //Criamos um cenário de testes - Given
        $valorEsperado = 2500;

        //Executamos o código a ser testado - When
        $this->leiloeiro->avalia($leilao);
        $menorValor = $this->leiloeiro->getMenorValor();

        //E verificamos se a saída é a esperada - Then
        $this->assertEquals($valorEsperado, $menorValor);
    }

    /**
     * @dataProvider entregaLeilaoCrescente
     * @dataProvider entregaLeilaoDecrescente
     * @dataProvider entregaLeilaoAleatorio
     */
    public function testAvaliadorDeTresMaioresValores(Leilao $leilao)
    {
        //Criamos um cenário de testes - Given

        //Executamos o código a ser testado - When
        $this->leiloeiro->avalia($leilao);
        $maioresLances = $this->leiloeiro->getMaioresLances();

        //E verificamos se a saída é a esperada - Then
        $this->assertCount(3, $maioresLances);
        $this->assertEquals(8000, $maioresLances[0]->getValor());
        $this->assertEquals(7000, $maioresLances[1]->getValor());
        $this->assertEquals(5000, $maioresLances[2]->getValor());
    }

    public static function entregaLeilaoCrescente(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');
        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $jose = new Usuario('José');

        $leilao->receberLance(new Lance($joao, 2500));
        $leilao->receberLance(new Lance($maria, 5000));
        $leilao->receberLance(new Lance($ana, 7000));
        $leilao->receberLance(new Lance($jose, 8000));


        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    public static function entregaLeilaoDecrescente(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');
        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $jose = new Usuario('José');

        $leilao->receberLance(new Lance($ana, 8000));
        $leilao->receberLance(new Lance($jose, 7000));
        $leilao->receberLance(new Lance($maria, 5000));
        $leilao->receberLance(new Lance($joao, 2500));

        return [
           'ordem-decrescente' => [$leilao]
        ];
    }

    public static function entregaLeilaoAleatorio(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');
        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');
        $jose = new Usuario('José');

        $leilao->receberLance(new Lance($maria, 5000));
        $leilao->receberLance(new Lance($ana, 8000));
        $leilao->receberLance(new Lance($joao, 2500));
        $leilao->receberLance(new Lance($maria, 7000));

        return [
        'ordem-aleatorio' =>[$leilao]
        ];
    }


}
