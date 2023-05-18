<?php

namespace Alura\Leilao\Tests\Model;

use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        $leilao = new Leilao('Brasília Amarela');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao->receberLance(new Lance($joao, 1000));
        $leilao->receberLance(new Lance($maria, 1500));
        $leilao->receberLance(new Lance($joao, 2000));
        $leilao->receberLance(new Lance($maria, 2500));
        $leilao->receberLance(new Lance($joao, 3000));
        $leilao->receberLance(new Lance($maria, 3500));
        $leilao->receberLance(new Lance($joao, 4000));
        $leilao->receberLance(new Lance($maria, 4500));
        $leilao->receberLance(new Lance($joao, 5000));
        $leilao->receberLance(new Lance($maria, 5500));
        $leilao->receberLance(new Lance($joao, 6000));

    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        static::expectException(\DomainException::class);
        static::expectExceptionMessage('Usuário não pode propor 2 lances consecutivos');

        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');
        $leilao->receberLance(new Lance($ana, 1000));
        $leilao->receberLance(new Lance($ana, 1500));
    }

    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(
        int    $qtdLances,
        Leilao $leilao,
        array  $valores
    )
    {
        static::assertCount($qtdLances, $leilao->getLances());

        foreach ($valores as $i => $valorEsperado) {
            static::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public static function geraLances()
    {
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat 147 0KM');
        $leilaoCom2Lances->receberLance(new Lance($joao, 1000));
        $leilaoCom2Lances->receberLance(new Lance($maria, 2000));

        $leilaoCom1Lance = new Leilao('Fusca 1972 0KM');
        $leilaoCom1Lance->receberLance(new Lance($maria, 5000));

        return [
            'leilao-Com2Lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            'leilao-Com1Lance' => [1, $leilaoCom1Lance, [5000]]
        ];

    }



}