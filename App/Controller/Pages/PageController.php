<?php

namespace App\Controller\Pages;

use App\Utils\View;

class PageController
{
    private static function getHeader()
    {
        return View::render('pages/header');
    }

    private static function getFooter()
    {
        return View::render('pages/footer');
    }

    public static function getPage($title, $contet)
    {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $contet,
            'footer' => self::getFooter()
        ]);
    }
}