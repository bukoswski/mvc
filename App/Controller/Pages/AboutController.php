<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Models\Entity\Entidade;

class AboutController extends PageController // home é uma pagina
{
    public static function getHome()
    {
        $Entidade = new Entidade;

        // echo "<pre>";
        // print_r($Entidade);
        // echo "</pre>";

        $content = View::render('pages/About', [
            'nome' => $Entidade->name,
            'descricao' => $Entidade->descricao,
            'endereco' => $Entidade->address
        ]);
        return parent::getPage('Sobre', contet: $content);
    }
}