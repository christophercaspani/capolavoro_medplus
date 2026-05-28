<?php
session_start();
require_once '../db.php';

unset($_SESSION['error_message_login']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_register']);

    $_SESSION['register']['nome'] = ucfirst($_POST['nome']);

    $_SESSION['register']['cognome'] = ucfirst($_POST['cognome']);

    $_SESSION['register']['email'] = strtolower($_POST['email']);

    $stmt = $db->prepare("SELECT Email FROM utente WHERE Email=?");
    $stmt->execute([$_SESSION['register']['email']]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_register'] =  'Email già in uso';
        header('Location: register.php');
        exit;
    }

    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];


    if ($password != $password_2) {
        $_SESSION['error_message_register'] =  'Errore! Le Password non corrispondono!';
        header('Location: register.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error_message_register'] =  'Errore! La Password deve contenere almeno 8 caratteri!';
        header('Location: register.php');
        exit;
        }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $db->prepare("INSERT INTO utente (Nome, Cognome, Email, Password, Ruolo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['register']['nome'], $_SESSION['register']['cognome'], $_SESSION['register']['email'], $hashed_password, 'Paziente']);

    unset($_SESSION['register']);
    $_SESSION['reg_complete'] = 1;
    header('Location: registration_complete.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Registrazione</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <script src="../scripts/show_password.js" defer></script>
</head>

<body>
    <h1 class="h1_style_one">Registrati</h1>
    <form class="login_form" method="post" action="register.php">
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
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <input type="button" id="pwd_btn_1" value="Mostra Password">
        </div>
        <div>
            <label>Conferma Password</label>
            <input type="password" name="password_2" id="password_2" required>
            <input type="button" id="pwd_btn_2" value="Mostra Password">
        </div>
        <input type="submit" value="Registrati">
    </form>
    <div class="login_help">
        <a href="login.php">Torna a Login</a>
        <a href="../index.php">Home</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_register'])) echo $_SESSION['error_message_register'] ?></p>

</body>

</html>