<?php
session_start();
require_once '../../../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../../auth/logout.php');
    exit;
}

if($_SESSION['user']['Ruolo'] != 'Amministratore'){
        header('Location: ../../../index.php');
    exit;
}

$id = $_GET['id'];
$stmt = $db->prepare("DELETE FROM centro_medico WHERE ID_CentroMedico = ?");
$stmt->execute([$id]);

header('Location: ../../../dashboard_amministratore.php');
exit;
?>