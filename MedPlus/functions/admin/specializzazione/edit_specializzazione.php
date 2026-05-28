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

$_SESSION['edit']['id'] = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM specializzazione WHERE ID_Specializzazione = ?");
$stmt->execute([$_SESSION['edit']['id']]);
$_SESSION['edit'] = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_edit_specializzazione']);

    $specializzazione = ucfirst($_POST['specializzazione']);
    $stmt = $db->prepare("SELECT NomeSpecializzazione FROM specializzazione WHERE NomeSpecializzazione=? AND ID_Specializzazione != ?");
    $stmt->execute([$specializzazione, $_SESSION['edit']['ID_Specializzazione']]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_edit_specializzazione'] = 'Specializzazione già inserita!';
        $url = 'Location: edit_specializzazione.php?id='.$_SESSION['edit']['ID_Specializzazione'];
        header($url);
        exit;
    }

    $stmt = $db->prepare("UPDATE specializzazione SET NomeSpecializzazione = ? WHERE ID_Specializzazione = ?");
    $stmt->execute([$specializzazione, $_SESSION['edit']['ID_Specializzazione']]);

    unset($_SESSION['edit']);
    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Modifica Specializzazione</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Modifica Specializzazione</h1>
    <form class="login_form" method="post" action="edit_specializzazione.php?id=<?php echo $_SESSION['edit']['ID_Specializzazione']; ?>">
        <div>
            <label>Specializzazione</label>
            <input type="text" name="specializzazione" value="<?php if (isset($_SESSION['edit']['NomeSpecializzazione'])) {echo $_SESSION['edit']['NomeSpecializzazione'];} ?>" required>
        </div>
        <input type="submit" value="Aggiorna Specializzazione">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_edit_specializzazione']))echo $_SESSION['error_message_edit_specializzazione'] ?></p>

</body>

</html>