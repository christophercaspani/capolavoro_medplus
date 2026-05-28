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

    unset($_SESSION['error_message_create_centro_medico']);

    $citta = ucfirst($_POST['citta']);

    $stmt = $db->prepare("SELECT Citta FROM centro_medico WHERE Citta=?");
    $stmt->execute([$citta]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_create_centro_medico'] = 'Centro Medico già inserito!';
        header('Location: create_centro_medico.php');
        exit;
    }

    $stmt = $db->prepare("INSERT INTO centro_medico (ID_CentroMedico, Citta) VALUES (NULL, ?)");
    $stmt->execute([$citta]);

    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Nuovo Centro Medico</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Nuovo Centro Medico</h1>
    <form class="login_form" method="post" action="create_centro_medico.php">
        <div>
            <label>Città</label>
            <input type="text" name="citta" value="<?php if(isset($_POST['citta'])){ echo $_POST['citta']; } ?>" required>
        </div>
        <input type="submit" value="Crea Nuovo Centro Med.">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_create_centro_medico']))
        echo $_SESSION['error_message_create_centro_medico'] ?></p>

    </body>

    </html>