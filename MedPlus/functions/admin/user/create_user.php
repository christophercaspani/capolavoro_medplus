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

    unset($_SESSION['error_message_create_user']);

    $_SESSION['register']['nome'] = ucfirst($_POST['nome']);

    $_SESSION['register']['cognome'] = ucfirst($_POST['cognome']);

    $_SESSION['register']['email'] = strtolower($_POST['email']);

    $ruolo = $_POST['ruolo'];

    $stmt = $db->prepare("SELECT Email FROM utente WHERE Email=?");
    $stmt->execute([$_SESSION['register']['email']]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_create_user'] = 'Email già in uso';
        header('Location: create_user.php');
        exit;
    }

    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];


    if ($password != $password_2) {
        $_SESSION['error_message_create_user'] = 'Errore! Le Password non corrispondono!';
        header('Location: create_user.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error_message_create_user'] = 'Errore! La Password deve contenere almeno 8 caratteri!';
        header('Location: create_user.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $db->prepare("INSERT INTO utente (Nome, Cognome, Email, Password, Ruolo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['register']['nome'], $_SESSION['register']['cognome'], $_SESSION['register']['email'], $hashed_password, $ruolo]);

    unset($_SESSION['register']);
    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Nuovo Utente</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
    <script src="../../../scripts/show_password.js" defer></script>
</head>

<body>
    <h1 class="h1_style_one">Nuovo Utente</h1>
    <form class="login_form" method="post" action="create_user.php">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php if(isset($_SESSION['register']['nome'])){ echo $_SESSION['register']['nome']; } ?>" required>
        </div>
        <div>
            <label>Cognome</label>
            <input type="text" name="cognome" value="<?php if(isset($_SESSION['register']['cognome'])){ echo $_SESSION['register']['cognome']; } ?>" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php if(isset($_SESSION['register']['email'])){ echo $_SESSION['register']['email']; } ?>" required>
        </div>
        <div>
            <label>Ruolo</label>
            <select name="ruolo" required>
               <option value="Medico">Medico</option>
               <option value="Amministratore">Amministratore</option>
            </select>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <input type="button" id="pwd_btn_1" value="Mostra Password">
        </div>
        <div>
            <label>Conferma Password</label>
            <input type="password" name="password_2" id="password_2" required>
            <input type="button" id="pwd_btn_2" value="Mostra Password">
        </div>
        <input type="submit" value="Crea Nuovo Utente">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_create_user']))
        echo $_SESSION['error_message_create_user'] ?></p>

    </body>

    </html>

