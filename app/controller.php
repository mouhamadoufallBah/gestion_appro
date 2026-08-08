<?php
require_once(BASE_PATH . "/app/model/approvisionnement.model.php");
require_once(BASE_PATH . "/app/model/produit.model.php");
require_once(BASE_PATH . "/app/model/fournisseur.moddel.php");
require_once(BASE_PATH . "/app/model/produit.model.php");

function onShowLivreRapprochement(): void
{
    $listeBl = getLivreRapprochement();
    $produitEnRupture = getProduitQteAlert();
    $approvisionnements = getAllApprovisionnement();
    $fournisseurs = getAllFournisseurs();
    $produits = getAllProduits();

    require_once(BASE_PATH . "/app/views/appro.html.php");
}

function onAddLivariason(): void
{
    $data['id_approvisionnment'] = $_GET['id_approvisionnment'];
    $data['detail_approvisionnement'] = $_POST;
    $results =  addReception($data);
    if ($results) {
        header('Location: http://localhost:8000/');
        exit;
    } else {
        var_dump('erreur lors de la recption');
        die();
    }
}

function onAddPanier(): void
{
    var_dump($_POST); echo '</br>';
    // $_SESSION[PANIER][$data['libelle']][] = $data;
    var_dump($_SESSION[PANIER]);die();
}

function onRemovePanier():void
{
    var_dump('add cart');die;
}


function onAddApprovisionnement(): void {}
