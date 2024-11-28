<?php

require_once __DIR__ . '/vendor/autoload.php';


use \App\Http\Router;

use \App\Utils\View;


define('URL', 'http://localhost/mvc');

View::init([
    'URL' => URL
]);

$objRota = new Router(URL);

include_once __DIR__ . '/routes/pages.php';

$objRota->run()->sendResponse();