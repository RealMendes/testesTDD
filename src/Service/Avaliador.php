<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;

class Avaliador
 {
    private $maiorValor = -INF;
    private $menorValor = INF;
    private $maioresLances;

    public function avalia( Leilao $leilao ): void 
    {
        if ($leilao->estaFinalizado()) {
            throw new \DomainException( 'Leilão já finalizado' );
        }
        if ( empty( $leilao->getLances() ) ) {
            throw new \DomainException( 'Não é possível avaliar um leilão vazio' );
        }
        foreach ( $leilao->getLances() as $lance ) {
            if ( $lance->getValor() > $this->maiorValor ) {
                $this->maiorValor = $lance->getValor();
            }
            if ( $lance->getValor() < $this->menorValor ) {
                $this->menorValor = $lance->getValor();
            }
        }
        $this->avaliaTresMaioresLances( $leilao );
    }

    protected function avaliaTresMaioresLances( Leilao $leilao ): array
    {
        $lances = $leilao->getLances();
        usort( $lances, function ( $a, $b ) {
            return $b->getValor() - $a->getValor();
        } );
        $this->maioresLances = array_slice( $lances, 0, 3 );
        return $this->maioresLances;
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMenorValor(): float
    {
            return $this->menorValor;
    }

    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }    
}
