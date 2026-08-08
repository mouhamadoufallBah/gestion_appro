<?php

require_once(BASE_PATH . "/app/database.php");


function getProduitQteAlert(): array {
    $sql = "select p.libelle, p.qte_stock, qte_seuil, p.id,
            f.nom
            from produits p
            inner join fournisseurs f on f.id = p.fournisseur_id
            where qte_stock <= qte_seuil";

    $db = deconnecteDB();
    $produitEnRupture = query($db, $sql, false);
    
    return $produitEnRupture;
}