<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Models\Entity\Entidade;

class HomeController extends PageController // home é uma pagina
{
    public static function getHome()
    {
        $Entidade = new Entidade;

        // echo "<pre>";
        // print_r($Entidade);
        // echo "</pre>";

        $content = View::render('pages/Home', [
            'nome' => $Entidade->name

        ]);
        return parent::getPage('Home', contet: $content);
    }
}