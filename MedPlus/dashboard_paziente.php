<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/logout.php');
    exit;
}
if($_SESSION['user']['Ruolo'] != 'Paziente'){
        header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT prenotazione.*, utente.*, prestazione.NomePrestazione, specializzazione.NomeSpecializzazione, centro_medico.Citta FROM prenotazione JOIN prestazione ON prenotazione.prestazione = prestazione.ID_Prestazione JOIN utente ON prenotazione.Medico = utente.Email JOIN specializzazione ON specializzazione.ID_Specializzazione = prestazione.Specializzazione JOIN centro_medico ON centro_medico.ID_CentroMedico = prenotazione.CentroMedico WHERE prenotazione.utente = ? AND Stato != 'Dimesso' ORDER BY prenotazione.Data DESC, prenotazione.Ora DESC");
$stmt -> execute([$_SESSION['user']['Email']]);
$prenotazioni_future = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT prenotazione.*, utente.*, prestazione.NomePrestazione, specializzazione.NomeSpecializzazione, centro_medico.Citta, referto.DataPubblicazione FROM prenotazione JOIN prestazione ON prenotazione.prestazione = prestazione.ID_Prestazione JOIN utente ON prenotazione.Medico = utente.Email JOIN specializzazione ON specializzazione.ID_Specializzazione = prestazione.Specializzazione JOIN centro_medico ON centro_medico.ID_CentroMedico = prenotazione.CentroMedico JOIN referto ON referto.Prenotazione = prenotazione.ID_Prenotazione WHERE prenotazione.utente = ? AND Stato = 'Dimesso'");
$stmt -> execute([$_SESSION['user']['Email']]);
$prenotazioni_passate = $stmt -> fetchAll(PDO::FETCH_ASSOC);  
?>

<!DOCTYPE html>
<html>

<head>
    <title>MedPlus - Dashboard Paziente</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
</head>

<body>
    <header>
        <h1>MedPlus</h1>
        <div>
            <a class="dash_a" href="functions/all/account.php"><?php echo $_SESSION['user']['Nome'] . " " . $_SESSION['user']['Cognome'] . " (" . $_SESSION['user']['Ruolo'] . ") " ?></a>
            <a class="dash_a" href="index.php">Home</a>
            <a class="dash_a" href="auth/logout.php">Logout</a>
        </div>
            </header>
        <h1 class="h1_style_two">Ciao <?php echo $_SESSION['user']['Nome'];?>!</h1>
        <div class="dash_sections">
        <section class="dash_section">
            <h2>Prenotazioni in Corso</h2>

            <?php if (!$prenotazioni_future): ?>
                <p>Al momento non hai prenotazioni in corso...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Data e Ora</th>
                        <th>Nome e Cognome Medico</th>
                        <th>Prestazione</th>
                        <th>Centro Medico</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($prenotazioni_future as $p_f): ?>
                        <tr>
                            <td><?= date_format(date_create($p_f['Data']), 'd/m/Y') ?> alle <?= $p_f['Ora'] ?></td>
                            <td><?= $p_f['Nome'] ?> <?= $p_f['Cognome'] ?></td>
                            <td><?=  $p_f['NomePrestazione'] ?> (<?=  $p_f['NomeSpecializzazione'] ?>)</td> 
                            <td><?=  $p_f['Citta'] ?></td> 
                            <td><?=  $p_f['Stato'] ?></td> 
                            <td><?php if($p_f['Stato'] == 'Prenotato'): ?><a class="a_red" href="functions/paziente/cancella_prenotazione.php?id=<?= $p_f['ID_Prenotazione'] ?>">Elimina</a><?php endif; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div><a class="a_green" href="functions/paziente/prenota.php">Nuova Prenotazione</a></div>
        </section>
        <section class="dash_section">
            <h2>Cronologia Prenotazioni</h2>

            <?php if (!$prenotazioni_passate): ?>
                <p>Al momento non hai prenotazioni passate...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Data Visita</th>
                        <th>Nome e Cognome Medico</th>
                        <th>Prestazione</th>
                        <th>Centro Medico</th>
                        <th>Data Referto</th>
                    </tr>
                    <?php foreach ($prenotazioni_passate as $p_p): ?>
                        <tr>
                            <td><?= date_format(date_create($p_p['Data']), 'd/m/Y') ?> (<?= $p_p['Ora'] ?>)</td>
                            <td><?= $p_p['Nome'] ?> <?= $p_p['Cognome'] ?></td>
                            <td><?=  $p_p['NomePrestazione'] ?> (<?=  $p_p['NomeSpecializzazione'] ?>)</td> 
                            <td><?=  $p_p['Citta'] ?></td> 
                            <td><?= date_format(date_create($p_p['DataPubblicazione']), 'd/m/Y') ?></td> 
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>
        </div>
    <footer>
        <p>MedPlus - &copy Copyright 2026</p>
    <footer>    
</body>

</html>