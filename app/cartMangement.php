<?php
define('PANIER', 'panier');
function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();

        if (!isset($_SESSION[PANIER])) {
            $_SESSION[PANIER] = [];
        }
    }
}
function addToCart(array $data)
{

    $key = $data['produit']['id'];

    if (isset($_SESSION[PANIER][$key])) {
        $_SESSION[PANIER][$key]['qte'] += $data['qte'];
    } else {
        $_SESSION[PANIER][$key] = $data;
    }

    // echo '<pre>';
    // var_dump($key);
    // var_dump($_SESSION[PANIER]);
    // echo '</pre>';
    // die;
}

function getCart(): array
{
    return $_SESSION[PANIER];
}

function removeToCart(int $produit_id) {
    unset($_SESSION[PANIER][$produit_id]);
}
