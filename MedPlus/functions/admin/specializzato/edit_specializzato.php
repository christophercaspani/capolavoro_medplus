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
$_SESSION['edit']['email'] = $_GET['email'];
$stmt = $db->prepare("SELECT * FROM specializzato AS S JOIN utente ON S.specializzante = utente.Email JOIN specializzazione on S.specializzato = specializzazione.ID_Specializzazione  WHERE S.Specializzante = ? AND S.Specializzato=?");
$stmt->execute([$_SESSION['edit']['email'], $_SESSION['edit']['id']]);
$_SESSION['edit'] = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    unset($_SESSION['error_message_edit_specializzato']);

    $medico = $_POST['medico'];

    $specializzazione = $_POST['specializzazione'];

    if(!($_SESSION['edit']['Specializzante'] == $medico && $_SESSION['edit']['Specializzato'] == $specializzazione)){
    $stmt = $db->prepare("SELECT * FROM specializzato WHERE Specializzante=? AND Specializzato=?");
    $stmt->execute([$medico, $specializzazione]);
    $results = $stmt->fetch();
    if ($results) {
        $_SESSION['error_message_edit_specializzato'] = 'Specializzato già inserito!';
        $url = 'Location: edit_specializzato.php?id='.$_SESSION['edit']['Specializzato'].'&email='.$_SESSION['edit']['Specializzante'];
        header($url);
        exit;
    }
    $stmt = $db->prepare("UPDATE specializzato (Specializzante, Specializzato) VALUES (?, ?)");
    $stmt->execute([$medico, $specializzazione]);
    }

    header('Location: ../../../dashboard_amministratore.php');
    exit;
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Modifica Specializzato</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../style.css">
    <link rel="icon" type="image/x-icon" href="../../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Modifica Specializzato</h1>
    <form class="login_form" method="post" action="edit_specializzato.php?id=<?php echo $_SESSION['edit']['Specializzato']; ?>&email=<?php echo $_SESSION['edit']['Specializzante']; ?>">
        <div>
            <label>Medico</label>
            <select name="medico" required>
                <option value="<?= $_SESSION['edit']['Specializzante'] ?>"><?= $_SESSION['edit']['Nome'] ?> <?= $_SESSION['edit']['Cognome'] ?></option>
                <?php if(isset($lista_medici)): ?>
                <?php foreach($lista_medici as $l): ?>
               <option value="<?= $l['Email'] ?>"><?= $l['Nome'] ?> <?= $l['Cognome'] ?></option>
               <?php endforeach; ?>
               <?php endif; ?>
            </select>
            <label>Specializzazione</label>
            <select name="specializzazione" required>
                <option value="<?= $_SESSION['edit']['Specializzato'] ?>"><?= $_SESSION['edit']['NomeSpecializzazione'] ?></option>
                <?php if(isset($elenco_specializzazioni)): ?>
                <?php foreach($elenco_specializzazioni as $s): ?>
               <option value="<?= $s['ID_Specializzazione'] ?>"><?= $s['NomeSpecializzazione'] ?></option>
               <?php endforeach; ?>
               <?php endif; ?>
            </select>
        </div>
        <input type="submit" value="Aggiorna Specalizzato">
    </form>
    <div class="login_help_center">
        <a href="../../../dashboard_amministratore.php">Torna alla Dashboard</a>
    </div>
    <p class="login_error"><?php if (isset($_SESSION['error_message_edit_specializzato']))
        echo $_SESSION['error_message_edit_specializzato'] ?></p>

    </body>

    </html>