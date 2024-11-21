<?php

require_once __DIR__ . '/vendor/autoload.php';

use \App\Controller\Pages\HomeController;

echo HomeController::getHome();