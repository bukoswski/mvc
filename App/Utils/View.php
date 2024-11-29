<?php

namespace App\Utils;

class View
{
    private static $vars = [];

    public static function init($vars = [])
    {
        self::$vars = $vars;
    }
    // Metodo reposnavel por recebr o conteudo da view 
    private static function getContentView($view)
    {
        $file = __DIR__ . '/../../Resouces/View/' . $view . '.html';
        return file_exists($file) ? file_get_contents($file) : 'hhhh';
    }

    // metodo responsavel por renderizar a view
    public static function render($view, $vars = [])
    {

        $vars = array_merge(self::$vars, $vars);

        $contentView = self::getContentView($view);

        $key = array_keys($vars);
        $key = array_map(function ($item) {
            return '{{' . $item . '}}';
        }, $key);


        return str_replace($key, array_values($vars), $contentView);
    }
}
