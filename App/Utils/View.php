<?php

namespace App\Utils;

class View
{

    // Metodo reposnavel por recebr o conteudo da view 
    private static function getContentView($view)
    {
        $file = __DIR__ . '/../../Resouces/View/' . $view . '.html';
        return file_exists($file) ? file_get_contents($file) : 'hhhh';
    }

    // metodo responsavel por renderizar a view
    public static function render($view, $dados = [])
    {
        $contentView = self::getContentView($view);

        $key = array_keys($dados);
        $key = array_map(function ($item) {
            return '{{' . $item . '}}';
        }, $key);

        echo "<pre>";
        print_r($key);
        echo "</pre>";

        return str_replace($key, array_values($dados), $contentView);
    }
}