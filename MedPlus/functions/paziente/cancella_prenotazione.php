<?php
session_start();
require_once '../../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/logout.php');
    exit;
}

if($_SESSION['user']['Ruolo'] != 'Paziente'){
    header('Location: index.php');
    exit;
}
if(!isset($_GET['id'])){
    header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM `prenotazione` WHERE ID_Prenotazione=? AND utente=?");
$stmt -> execute([$_GET['id'], $_SESSION['user']['Email']]);
$find = $stmt -> fetch(PDO::FETCH_ASSOC);
if($find){
$stmt = $db->prepare("DELETE FROM `prenotazione` WHERE `prenotazione`.`ID_Prenotazione` = ?  ");
$stmt -> execute([$_GET['id']]);
}
header('Location: ../../dashboard_paziente.php');
exit;
?>