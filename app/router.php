<?php

require_once(BASE_PATH."/app/controller.php");

$routes = [
    "/" => 'showLivreRapprochement',
    "/addLivariason" => 'addLivariason'
];

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($routes[$uri] == null) {
   echo 'page not found';
   die();
}

$action = $routes[$uri];
$action();

