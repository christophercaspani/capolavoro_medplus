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

$stmt = $db->prepare("SELECT * FROM `prenotazione` WHERE ID_Prenotazione=? AND Medico=? AND Stato='Da Refertare'");
$stmt -> execute([$_GET['id'], $_SESSION['user']['Email']]);
$find = $stmt -> fetch(PDO::FETCH_ASSOC);
if($find){

$stmt = $db->prepare("INSERT INTO `referto` (`ID_Referto`, `DataPubblicazione`, `Prenotazione`) VALUES (NULL, ?, ?)");
$stmt -> execute([date("Y-m-d"), $_GET['id']]);

$stmt = $db->prepare("UPDATE `prenotazione` SET `Stato` = 'Dimesso' WHERE `prenotazione`.`ID_Prenotazione` = ?");
$stmt -> execute([$_GET['id']]);
}
header('Location: ../../dashboard_medico.php');
exit;   

?>
 