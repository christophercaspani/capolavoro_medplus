<?php
session_start();
require_once '../../../db.php';
include '../../all/commons.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../../auth/logout.php');
    exit;
}

if($_SESSION['user']['Ruolo'] != 'Amministratore'){
        header('Location: ../../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_create_specializzato']);

    $medico = $_POST['medico'];

    $specializzazione = $_POST['specializzazione'];

    $stmt = $db->prepare("SELECT * FROM specializzato WHERE Specializzante=? AND Specializzato=?");
    $stmt->execute([$medico, $specializzazione]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_create_specializzato'] = 'Specializzato già inserito!';
        header('Location: create_specializzato.php');
        exit;
    }

    $stmt = $db->prepare("INSERT INTO specializzato (Specializzante, Specializzato) VALUES (?, ?)");
    $stmt->execute([$medico, $specializzazione]);


    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Nuovo Specializzato</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Nuovo Specializzato</h1>
    <form class="login_form" method="post" action="create_specializzato.php">
        <div>
            <label>Medico</label>
            <select name="medico" required>
                <?php if(isset($lista_medici)): ?>
                <?php foreach($lista_medici as $l): ?>
               <option value="<?= $l['Email'] ?>"><?= $l['Nome'] ?> <?= $l['Cognome'] ?></option>
               <?php endforeach; ?>
               <?php endif; ?>
            </select>
            <label>Specializzazione</label>
            <select name="specializzazione" required>
                <?php if(isset($elenco_specializzazioni)): ?>
                <?php foreach($elenco_specializzazioni as $s): ?>
               <option value="<?= $s['ID_Specializzazione'] ?>"><?= $s['NomeSpecializzazione'] ?></option>
               <?php endforeach; ?>
               <?php endif; ?>
            </select>
        </div>
        <input type="submit" value="Crea Nuovo Spec.t.">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_create_specializzato']))
        echo $_SESSION['error_message_create_specializzato'] ?></p>

    </body>

    </html>