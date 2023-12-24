<?php

namespace RobEc\Tests;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RobEc\BuscadorCursos\Buscador;
use Symfony\Component\DomCrawler\Crawler;
use Override;

class BuscadorDeCursosTest extends TestCase
{
    private ClientInterface $clienteMock;
    private string $url = "url-test";

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $html = <<<FIM
        <html>
            <body>
                <span class="card-curso__nome">Curso teste 1</span>
                <span class="card-curso__nome">Curso teste 2</span>
                <span class="card-curso__nome">Curso teste 3</span>
            </body>
        </html>
        FIM;

        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method("__toString")
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method("getBody")
            ->willReturn($stream);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method("request")
            ->with("GET", $this->url)
            ->willReturn($response);

        $this->clienteMock = $httpClient;
    }

    public function testBuscadorDeveRetornarCursos(): void
    {
        $crawler = new Crawler();
        $buscador = new Buscador($this->clienteMock, $crawler);

        $cursos = $buscador->buscar($this->url);

        $this->assertCount(3, $cursos);
        $this->assertEquals("Curso teste 1", $cursos[0]);
        $this->assertEquals("Curso teste 2", $cursos[1]);
        $this->assertEquals("Curso teste 3", $cursos[2]);
    }
}
