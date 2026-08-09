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
}

function getCart(): array
{
    $data["panier"] = $_SESSION[PANIER];
    $data["montantTotal"] = 0;
    foreach ($data["panier"] as $item) {
        $data["montantTotal"] += $item['qte'] * $item['produit']['prix_achat'];
    }

    // echo '<pre>';
    // var_dump($data);
    // echo '</pre>';
    // die;
    return $data;
}

function removeToCart(int $produit_id)
{
    unset($_SESSION[PANIER][$produit_id]);
}

function viderCart()
{
    $_SESSION[PANIER] = [];
}
