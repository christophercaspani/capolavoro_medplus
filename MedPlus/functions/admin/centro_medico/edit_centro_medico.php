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
$stmt = $db->prepare("SELECT * FROM centro_medico WHERE ID_CentroMedico = ?");
$stmt->execute([$_SESSION['edit']['id']]);
$_SESSION['edit'] = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_edit_centro_medico']);

    $citta = ucfirst($_POST['citta']);
    $stmt = $db->prepare("SELECT Citta FROM centro_medico WHERE Citta=? AND ID_CentroMedico != ?");
    $stmt->execute([$citta, $_SESSION['edit']['ID_CentroMedico']]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_edit_centro_medico'] = 'Centro Medico già inserito!';
        $url = 'Location: edit_centro_medico.php?id='.$_SESSION['edit']['ID_CentroMedico'];
        header($url);
        exit;
    }

    $stmt = $db->prepare("UPDATE centro_medico SET Citta = ? WHERE ID_CentroMedico = ?");
    $stmt->execute([$citta, $_SESSION['edit']['ID_CentroMedico']]);

    unset($_SESSION['edit']);
    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Modifica Centro Medico</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Modifica Centro Medico</h1>
    <form class="login_form" method="post" action="edit_centro_medico.php?id=<?php echo $_SESSION['edit']['ID_CentroMedico']; ?>">
        <div>
            <label>Città</label>
            <input type="text" name="citta" value="<?php if (isset($_SESSION['edit']['Citta'])) {echo $_SESSION['edit']['Citta'];} ?>" required>
        </div>
        <input type="submit" value="Aggiorna Centro Medico">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_edit_centro_medico']))echo $_SESSION['error_message_edit_centro_medico'] ?></p>

</body>

</html>