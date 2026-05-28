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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_create_specializzazione']);

    $specializzazione = ucfirst($_POST['specializzazione']);
    $stmt = $db->prepare("SELECT NomeSpecializzazione FROM specializzazione WHERE NomeSpecializzazione=?");
    $stmt->execute([$specializzazione]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_create_specializzazione'] = 'Specializzazione già inserita!';
        header('Location: create_specializzazione.php');
        exit;
    }

    $stmt = $db->prepare("INSERT INTO specializzazione (ID_Specializzazione, NomeSpecializzazione) VALUES (NULL, ?)");
    $stmt->execute([$specializzazione]);

    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Nuova Specializzazione</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Nuova Specializzazione</h1>
    <form class="login_form" method="post" action="create_specializzazione.php">
        <div>
            <label>Specializzazione</label>
            <input type="text" name="specializzazione" value="<?php if(isset($_POST['specializzazione'])){ echo $_POST['specializzazione']; } ?>" required>
        </div>
        <input type="submit" value="Crea Nuova Spec.n.">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_create_specializzazione']))
        echo $_SESSION['error_message_create_specializzazione'] ?></p>

    </body>

    </html>