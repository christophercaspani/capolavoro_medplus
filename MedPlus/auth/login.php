<?php
session_start();
require_once '../db.php';

unset($_SESSION['error_message_register']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_login']);

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM utente WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user'] = $user;
    } else {
        $_SESSION['error_message_login'] = "Errore! Credenziali non valide!";
        header('Location: login.php');
        exit;
    }
}

if(isset($_SESSION['user']['Ruolo'])){
        switch($_SESSION['user']['Ruolo']){
            case 'Paziente':
                header('Location: ../dashboard_paziente.php');
                exit;
                break;
            case 'Medico':
                header('Location: ../dashboard_medico.php');
                exit;
            case 'Amministratore':
                header('Location: ../dashboard_amministratore.php');
                exit;
        }
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Login</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    <script src="../scripts/show_password.js" defer></script>
</head>

<body>
    <h1 class="h1_style_one">Login</h1>
    <form class="login_form" method="post" action="login.php">
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php if(isset($email)){ echo $email; } ?>" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <input type="button" id="pwd_btn_1" value="Mostra Password">
        </div>

        <input type="submit" value="Login">
    </form>
    <div class="login_help">
        <a href="register.php">Nuovo Utente?</a>
        <a href="../index.php">Home</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_login'])) echo $_SESSION['error_message_login'] ?></p>

</body>

</html>