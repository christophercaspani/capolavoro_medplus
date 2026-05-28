<?php

$stmt = $db->prepare("SELECT * FROM centro_medico ORDER BY ID_CentroMedico ASC");
$stmt -> execute();
$centri_medici = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM specializzazione ORDER BY ID_Specializzazione ASC");
$stmt -> execute();
$elenco_specializzazioni = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM utente WHERE Ruolo = 'Medico' ORDER BY Nome ASC");
$stmt -> execute();
$lista_medici = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM prestazione");
$stmt -> execute();
$elenco_prestazioni = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$elenco_prestazioni_per_spec = array();
for($i = 0; $i < count($elenco_specializzazioni); $i++){
$stmt = $db->prepare("SELECT * FROM prestazione WHERE Specializzazione=? ORDER BY Costo");
$stmt -> execute([$i+1]);
$elenco_prestazioni_per_spec[$i] = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

?>