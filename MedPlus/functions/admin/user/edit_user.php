<?php
session_start();
require_once '../../../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../../auth/logout.php');
    exit;
}

if ($_SESSION['user']['Ruolo'] != 'Amministratore') {
    header('Location: ../../../index.php');
    exit;
}

$_SESSION['edit']['email'] = $_GET['email'];
$stmt = $db->prepare("SELECT * FROM utente WHERE Email = ?");
$stmt->execute([$_SESSION['edit']['email']]);
$utente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_edit_user']);

    $_SESSION['edit']['nome'] = ucfirst($_POST['nome']);
    
    $_SESSION['edit']['cognome'] = ucfirst($_POST['cognome']);

    $stmt = $db->prepare("UPDATE utente SET Cognome = ?, Nome = ? WHERE Email = ?");
    $stmt->execute([$_SESSION['edit']['cognome'], $_SESSION['edit']['nome'], $_SESSION['edit']['email']]);

    if ($_POST['password'] != "" && $_POST['password_2'] != "") {

        $password = $_POST['password'];
        $password_2 = $_POST['password_2'];

        if ($password != $password_2) {
            $_SESSION['error_message_edit_user'] = 'Errore! Le Password non corrispondono!';
            $url = 'Location: edit_user.php?email=' . $_SESSION['edit']['email'];
            header($url);
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error_message_edit_user'] = 'Errore! La Password deve contenere almeno 8 caratteri!';
            $url = 'Location: edit_user.php?email=' . $_SESSION['edit']['email'];
            header($url);
            exit;
        }
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE Password = ? WHERE Email = ?");
        $stmt->execute([$hashed_password, $_SESSION['edit']['email']]);
    }
    unset($_SESSION['edit']);
    header('Location: ../../../dashboard_amministratore.php');
    exit;
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Modifica Utente</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
    <script src="../../../scripts/show_password.js" defer></script>
</head>

<body>
    <h1 class="h1_style_one">Modifica Utente</h1>
    <form class="login_form" method="post" action="edit_user.php?email=<?php echo $_SESSION['edit']['email']; ?>">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($utente['Nome']) ?>">
        </div>
        <div>
            <label>Cognome</label>
            <input type="text" name="cognome" value="<?= htmlspecialchars($utente['Cognome']) ?>">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($utente['Email']) ?>" readonly>
        </div>
        <div>
            <label>Ruolo</label>
            <input type="text" name="ruolo" value="<?= htmlspecialchars($utente['Ruolo']) ?>" readonly>
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
        <input type="submit" value="Aggiorna Utente">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_edit_user']))
                                echo $_SESSION['error_message_edit_user'] ?></p>

</body>

</html>