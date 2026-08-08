<?php
define('PANIER', 'panier');
function startSession(){
    if(session_status() === PHP_SESSION_NONE){
        session_start();
       addToCart([
    'produit 1' => [
        'qty' => 2,
        'prix' => 100
    ],
    'produit 5' => [
        'qty' => 2,
        'prix' => 100

    ],
]) ;
    }
}
function addToCart(array $data){
    $_SESSION[PANIER][] = $data;
}

function removeToCart(){}





