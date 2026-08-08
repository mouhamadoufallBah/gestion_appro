<?php

require_once(BASE_PATH . "/app/database.php");

function getLivreRapprochement(): array
{
    $sql = "SELECT a.id, a.ref_bl, f.nom, da.prix_achat_reel,
        case when da.qte_recu = 0 then da.qte_recu else da.qte_recu end as qte_recu,
        da.qte_appro,
        sum(prix_achat_reel * qte_appro) as montant_facture, sum(prix_achat_reel * coalesce(qte_recu, 0)) as montant_receptionne, p.prix_achat as prix_achat,
        case when da.qte_recu != da.qte_appro then 'Ecart: ' || sum(prix_achat_reel * qte_appro) - sum(prix_achat_reel * coalesce(qte_recu, 0)) else 'concorde' end as diagnostic,
        da.qte_recu = da.qte_appro as statut

        FROM approvisionnements a
        inner join detail_approvisionnements da on da.approvisionnement_id = a.id
        inner join fournisseurs f on f.id = a.fournisseur_id
        inner join statut s on s.id = a.statut_id
        inner join produits p on p.id = da.produit_id
        where a.statut_id =2
    group by a.id, f.nom, p.prix_achat,da.prix_achat_reel, da.qte_recu, da.qte_appro";

    $db = deconnecteDB();

    $listeBl = query($db, $sql, false);

    return $listeBl;
}

function getAllApprovisionnement(): array
{
    $sql = "SELECT a.id, a.ref_bl, a.dateappro, s.libelle as statut, f.nom, sum(da.prix_achat_reel * case when da.qte_recu = 0 then da.qte_appro else da.qte_recu end ) as montant
            FROM approvisionnements a
            inner join detail_approvisionnements da on da.approvisionnement_id = a.id
            inner join fournisseurs f on f.id = a.fournisseur_id
            inner join statut s on s.id = a.statut_id
            inner join produits p on p.id = da.produit_id
            group by a.id, a.ref_bl, a.dateappro, s.libelle, f.nom
            order by a.id desc";


    $db = deconnecteDB();

    $approvisionnements = query($db, $sql, false);

    foreach ($approvisionnements as &$appro) {
        $sql_ligne = "select 
            da.approvisionnement_id,
            da.id,
            p.libelle, p.id as produit_id,
            case 
            when da.qte_recu = 0 
            then da.qte_appro 
            else da.qte_recu 
            end as qte, 
            prix_achat_reel,
            case 
            when da.qte_recu = 0 
            then da.qte_appro * prix_achat_reel
            else da.qte_recu * prix_achat_reel
            end as montant
            from detail_approvisionnements da
            inner join produits p on p.id = da.produit_id 
        where da.approvisionnement_id = :approvisionnement_id";

        $db = deconnecteDB();

        $ligneApprovisionnements = executeQuery($db, $sql_ligne, ["approvisionnement_id" => $appro["id"]], false);


        $appro["ligneAppro"] = [...$ligneApprovisionnements];
    }

    // echo "<pre>";
    // var_dump($approvisionnements);
    // echo "</pre>";
    // die();


    return $approvisionnements;
}

function addReception(array $data): int
{
    $db = deconnecteDB();
    try {

        $db->beginTransaction();

        $sql_update_appro = "update approvisionnements set statut_id = :statut_id where id = :id";
        $resAppro = executeUpdate($db, $sql_update_appro, ['statut_id' => 2, 'id' => $data['id_approvisionnment']]);

        if ($resAppro == 0) {
            throw new Exception('approvisonnement non modifier');
        }

        foreach ($data["detail_approvisionnement"]["ligne"] as $detail_approvisionnement) {
            $sql_update_detail = "update detail_approvisionnements set qte_recu = :qte_recu, prix_achat_reel = :prix_achat_reel where id = :id";

            $resDetail =  executeUpdate($db, $sql_update_detail, ['qte_recu' => $detail_approvisionnement['qte_recu'], 'prix_achat_reel' => $detail_approvisionnement['prix_achat_reel'], 'id' => $detail_approvisionnement['id']]);

            if ($resDetail == 0) {
                throw new Exception('detail non modifier');
            }

            $sql_update_produit = "update produits set qte_stock = qte_stock + :qte_recu where id = :id";
            $resProd = executeUpdate($db, $sql_update_produit, ['qte_recu' => $detail_approvisionnement['qte_recu'], 'id' => $detail_approvisionnement['produit_id']]);
            if ($resProd == 0) {
                throw new Exception('produit non modifier');
            }
        }

        $db->commit();
    } catch (\Throwable $th) {
        $th->getMessage();
        $db->rollBack();
        return false;
    }

    return true;
}

function addApprovisionnement(array $data): int
{

    return 0;
}
