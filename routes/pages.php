<?php

use \App\Http\Response;

use \App\Controller\Pages;

//home
$objRota->get('/', [
    function () {
        return new Response('200', pages\HomeController::getHome());
    }
]);

//sobre
$objRota->get('/sobre', [
    function () {
        return new Response('200', pages\AboutController::getHome());
    }
]);
$objRota->get('pagina{idPagina}/{acao}', [
    function ($idPagina, $acao) {
        return new Response('200', "Pagina " . $idPagina . '-' . $acao);
    }
]);