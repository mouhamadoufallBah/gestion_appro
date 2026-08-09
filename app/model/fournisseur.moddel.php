<?php

require_once(BASE_PATH . "/app/database.php");

function getAllFournisseurs(): array
{
    $sql = "SELECT nom, id FROM fournisseurs";

    $db = deconnecteDB();

    $listeBl = query($db, $sql, false);

    return $listeBl;
}