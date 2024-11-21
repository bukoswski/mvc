<?php

namespace App\Controller\Pages;

use App\Utils\View;

class HomeController extends PageController // home é uma pagina
{
    public static function getHome()
    {
        $content = View::render('pages/home', [
            'nome' => 'Pedro Henrique',
            'descricao' => 'Engenheiro de Software Sênior at Google',
            'idade' => 23
        ]);
        return parent::getPage('Pedro ok', contet: $content);
    }
}