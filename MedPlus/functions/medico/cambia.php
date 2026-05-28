<?php
session_start();
require_once '../../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/logout.php');
    exit;
}

if($_SESSION['user']['Ruolo'] != 'Medico'){
    header('Location: index.php');
    exit;
}

if(!isset($_GET['id'])){
    header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM `prenotazione` WHERE ID_Prenotazione=? AND Medico=?");
$stmt -> execute([$_GET['id'], $_SESSION['user']['Email']]);
$find = $stmt -> fetch(PDO::FETCH_ASSOC);
if($find){
switch($find["Stato"]){
    case 'Prenotato':
    $nuovo_stato = 'Arrivato';
    break;
    case 'Arrivato':
    $nuovo_stato = 'Da Refertare';
    break;
    default:
    header('Location: ../../dashboard_medico.php');
    exit;
}
$stmt = $db->prepare("UPDATE `prenotazione` SET `Stato` = ? WHERE `prenotazione`.`ID_Prenotazione` = ?");
$stmt -> execute([$nuovo_stato ,$_GET['id']]);
}
header('Location: ../../dashboard_medico.php');
exit;
?>