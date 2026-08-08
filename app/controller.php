<?php
require_once(BASE_PATH."/app/model/approvisionnement.model.php");
require_once(BASE_PATH."/app/model/produit.model.php");

function showLivreRapprochement(): void {
    $listeBl = getLivreRapprochement();
    $produitEnRupture = getProduitQteAlert();
    $approvisionnements = getAllApprovisionnement();

    require_once(BASE_PATH."/app/views/appro.html.php");
}

function addLivariason(): void{
    $data = [$_POST, $_GET];
    addApprovisionnement($_POST);
    var_dump($data);
}
