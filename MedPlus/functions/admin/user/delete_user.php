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

$email = $_GET['email'];
$stmt = $db->prepare("DELETE FROM utente WHERE Email = ?");
$stmt->execute([$email]);

header('Location: ../../../dashboard_amministratore.php');
exit;
?>