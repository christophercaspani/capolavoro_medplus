<?php
session_start();
require_once '../../../db.php';
include '../../all/commons.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../../../auth/logout.php');
    exit;
}

if($_SESSION['user']['Ruolo'] != 'Amministratore'){
        header('Location: ../../../index.php');
    exit;
}

$_SESSION['edit']['id'] = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM prestazione JOIN specializzazione ON prestazione.Specializzazione = specializzazione.ID_Specializzazione WHERE ID_Prestazione = ?");
$stmt->execute([$_SESSION['edit']['id']]);
$_SESSION['edit'] = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_edit_prestazione']);

    $_SESSION['edit']['NomePrestazione'] = ucfirst($_POST['nome']);

    $_SESSION['edit']['Costo'] = $_POST['costo'];

    $specializzazione = $_POST['specializzazione'];

    $stmt = $db->prepare("SELECT NomePrestazione FROM prestazione WHERE NomePrestazione=? AND ID_Prestazione != ?");
    $stmt->execute([$_SESSION['edit']['NomePrestazione'], $_SESSION['edit']['ID_Prestazione']]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_edit_prestazione'] = 'Prestazione già inserita!';
            $url = 'Location: edit_prestazione.php?id=' . $_SESSION['edit']['ID_Prestazione'];
        header($url);
        exit;
    }

    $stmt = $db->prepare("UPDATE prestazione SET NomePrestazione=?, Costo=?, Specializzazione=? WHERE ID_Prestazione=?");
    $stmt->execute([$_SESSION['edit']['NomePrestazione'], $_SESSION['edit']['Costo'], $specializzazione, $_SESSION['edit']['ID_Prestazione']]);

    unset($_SESSION['edit']);
    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Modifica Prestazione</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Modifica Prestazione</h1>
    <form class="login_form" method="post" action="edit_prestazione.php?id=<?php echo $_SESSION['edit']['ID_Prestazione']; ?>">
        <div>
            <label>Nome</label>
            <input type="text" name="nome" value="<?php if(isset($_SESSION['edit']['NomePrestazione'])){ echo $_SESSION['edit']['NomePrestazione']; } ?>" required>
        </div>
        <div>
            <label>Costo (€)</label>
            <input type="number" name="costo" min="0.00" step="0.01" value="<?php if(isset($_SESSION['edit']['Costo'])){ echo $_SESSION['edit']['Costo']; } ?>" required>
        </div>
        <div>
            <label>Specializzazione</label>
            <select name="specializzazione" required>
                 <option value="<?=$_SESSION['edit']['Specializzazione'] ?>"><?= $_SESSION['edit']['NomeSpecializzazione'] ?></option>
                <?php if(isset($elenco_specializzazioni)): ?>
                <?php foreach($elenco_specializzazioni as $s): ?>
               <option value="<?= $s['ID_Specializzazione'] ?>"><?= $s['NomeSpecializzazione'] ?></option>
               <?php endforeach; ?>
               <?php endif; ?>
            </select>
        </div>
        <input type="submit" value="Aggiorna Prestazione">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_edit_prestazione']))
        echo $_SESSION['error_message_edit_prestazione'] ?></p>

    </body>

    </html>