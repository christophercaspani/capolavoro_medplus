<?php
session_start();
require_once '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_update']);

    $nome = ucfirst($_POST['nome']);

    $cognome = ucfirst($_POST['cognome']);

    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];

    if($password == "" && $password_2 == ""){
    $stmt = $db->prepare("UPDATE `utente` SET `Nome` = ?, `Cognome` = ? WHERE `utente`.`Email` = ?");
    $stmt->execute([$nome, $cognome, $_SESSION['user']['Email']]);

    }else{

    if (strlen($password) == 0 && strlen($password_2) == 0) {
        $_SESSION['error_message_update'] =  'Errore! Le Password non corrispondono!';
        header('Location: account.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error_message_update'] =  'Errore! La Password deve contenere almeno 8 caratteri!';
        header('Location: account.php');
        exit;
        }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("UPDATE `utente` SET `Nome` = ?, `Cognome` = ?, `Password` = ?  WHERE `utente`.`Email` = ?");
    $stmt->execute([$nome, $cognome, $hashed_password, $_SESSION['user']['Email']]);
    }

    $stmt = $db->prepare("SELECT * FROM `utente` WHERE `utente`.`Email` = ?");
    $stmt->execute([$_SESSION['user']['Email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user){
    $_SESSION['user'] = $user;
    $url = "Location: ../../dashboard_".strtolower($_SESSION['user']['Ruolo']).".php";
    header($url);
    exit;
    }else{
    header('Location: account.php');
    exit;
    }


}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Modifica Account</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../style.css">
    <link rel="icon" type="image/x-icon" href="../../img/favicon.ico">
    <script src="../../scripts/show_password.js" defer></script>
</head>

<body>
    <h1 class="h1_style_one">Modifica Account</h1>
    <form class="login_form" method="post" action="account.php">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php echo $_SESSION['user']['Nome']; ?>" required>
        </div>
        <div>
            <label>Cognome</label>
            <input type="text" name="cognome" value="<?php echo $_SESSION['user']['Cognome']; ?>" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" value="<?php echo $_SESSION['user']['Email']; ?>" readonly>
        </div>
        <div>
            <label>Ruolo</label>
            <input type="text" value="<?php echo $_SESSION['user']['Ruolo']; ?>" readonly>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" id="password">
            <input type="button" id="pwd_btn_1" value="Mostra Password">
        </div>
        <div>
            <label>Conferma Password</label>
            <input type="password" name="password_2" id="password_2">
            <input type="button" id="pwd_btn_2" value="Mostra Password">
        </div>
        <input type="submit" value="Modifica Account">
    </form>
    <div class="login_help_center">
        <a href="../../dashboard_<?php echo strtolower($_SESSION['user']['Ruolo']); ?>.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_update'])) echo $_SESSION['error_message_update'] ?></p>
</body>

</html>