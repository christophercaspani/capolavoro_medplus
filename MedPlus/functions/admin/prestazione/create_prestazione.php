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

    unset($_SESSION['error_message_create_prestazione']);

    $_SESSION['create_prestazione']['nome'] = ucfirst($_POST['nome']);

    $_SESSION['create_prestazione']['costo'] = $_POST['costo'];

    $specializzazione = $_POST['specializzazione'];

    $stmt = $db->prepare("SELECT NomePrestazione FROM prestazione WHERE NomePrestazione=?");
    $stmt->execute([$_SESSION['create_prestazione']['nome']]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_create_prestazione'] = 'Prestazione già inserita!';
        header('Location: create_prestazione.php');
        exit;
    }

    $stmt = $db->prepare("INSERT INTO prestazione (ID_Prestazione, NomePrestazione, Costo, Specializzazione) VALUES (NULL, ?, ?, ?)");
    $stmt->execute([$_SESSION['create_prestazione']['nome'], $_SESSION['create_prestazione']['costo'], $specializzazione]);

    unset($_SESSION['create_prestazione']);
    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Nuova Prestazione</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Nuova Prestazione</h1>
    <form class="login_form" method="post" action="create_prestazione.php">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php if(isset($_SESSION['create_prestazione']['nome'])){ echo $_SESSION['create_prestazione']['nome']; } ?>" required>
        </div>
        <div>
            <label>Costo (€)</label>
            <input type="number" name="costo" min="0.00" step="0.01" value="<?php if(isset($_SESSION['create_prestazione']['costo'])){ echo $_SESSION['create_prestazione']['costo']; } ?>" required>
        </div>
        <div>
            <label>Specializzazione</label>
            <select name="specializzazione" required>
                <?php if(isset($elenco_specializzazioni)): ?>
                <?php foreach($elenco_specializzazioni as $s): ?>
               <option value="<?= $s['ID_Specializzazione'] ?>"><?= $s['NomeSpecializzazione'] ?></option>
               <?php endforeach; ?>
               <?php endif; ?>
            </select>
        </div>
        <input type="submit" value="Crea Nuova Prestazione">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_create_prestazione']))
        echo $_SESSION['error_message_create_prestazione'] ?></p>

    </body>

    </html>