<?php
session_start();

if (!isset($_SESSION['reg_complete'])) {
    header('Location: ../index.php');
    exit;
}

unset($_SESSION['reg_complete']);

?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Registrazione Completata</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
</head>

<body>
    <div class="registration_complete">
        <h1>Registrati</h1>
        <h2>Registrazione Completata con Successo!</h2>
        <a href="login.php">Vai a Login</a>
    </div>

</body>

</html>