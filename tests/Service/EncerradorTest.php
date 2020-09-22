<?php

namespace Alura\Leilao\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\{ Lance, Leilao, Usuario };
use Alura\Leilao\Service\{ Avaliador, Encerrador };

class EncerradorTest extends TestCase
{
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $fiat147 = new Leilao(
            'Fiat 147 0km',
            new \DateTimeImmutable('8 days ago')
        );

        $variant = new Leilao(
            'Variant 1972 0km',
            new \DateTimeImmutable('10 days ago')
        );

        $leilaoDao = new LeilaoDao();
        $leilaoDao->salva($fiat147);
        $leilaoDao->salva($variant);

        $encerrar = new Encerrador();
        $encerrar->encerra();

        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(2, $leiloes);
        self::assertEquals('Fiat 147 0km', $leiloes[0]->recuperarDescricao());
        self::assertEquals('Variant 1972 0km', $leiloes[1]->recuperarDescricao());
    }
}
