<?php

namespace RobEc\BuscadorCursos;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    private ClientInterface $cliente;
    private Crawler $crawler;

    public function __construct(ClientInterface $cliente, Crawler $crawler)
    {
        $this->cliente = $cliente;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): array
    {
        $cursos = [];

        $resposta = $this->cliente->request("GET", $url);
        $html = $resposta->getBody();

        $this->crawler->addHtmlContent($html);
        $cursosDOM = $this->crawler->filter("span.card-curso__nome");

        foreach ($cursosDOM as $cursoDOM) {
            $cursos[] = $cursoDOM->textContent;
        }

        return $cursos;
    }
}
