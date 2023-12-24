#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";

use GuzzleHttp\Client;
use RobEc\BuscadorCursos\Buscador;
use Symfony\Component\DomCrawler\Crawler;

$uri = "https://www.alura.com.br";

// $client = new Client();
// $resposta = $client->request("GET", $uri);

// $html = $resposta->getBody();

// $crawler = new Crawler();
// $crawler->addHtmlContent($html);

// $cursos = $crawler->filter("span.card-curso__nome");

// foreach($cursos as $curso) {
//     echo $curso->textContent . PHP_EOL;
// }

$buscador = new Buscador(
    new Client(["base_uri" => $uri]),
    new Crawler()
);

var_dump($buscador->buscar("/cursos-online-programacao/php"));
