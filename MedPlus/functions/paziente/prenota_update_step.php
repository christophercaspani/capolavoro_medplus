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
if(!isset($_GET['new_step'])){
    header('Location: index.php');
    exit;
}

$_SESSION['step'] = $_GET['new_step'];

header('Location: prenota.php');
exit;
?>