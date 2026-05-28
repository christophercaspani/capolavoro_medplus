<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/logout.php');
    exit;
}
if($_SESSION['user']['Ruolo'] != 'Medico'){
        header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT prenotazione.*, utente.*, prestazione.NomePrestazione, specializzazione.NomeSpecializzazione, centro_medico.Citta FROM prenotazione JOIN prestazione ON prenotazione.prestazione = prestazione.ID_Prestazione JOIN utente ON prenotazione.utente = utente.Email JOIN specializzazione ON specializzazione.ID_Specializzazione = prestazione.ID_Prestazione JOIN centro_medico ON centro_medico.ID_CentroMedico = prenotazione.CentroMedico WHERE prenotazione.Medico = ? AND Stato != 'Dimesso' AND Stato != 'Da Refertare'");
$stmt -> execute([$_SESSION['user']['Email']]);
$lista_pazienti_check = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT prenotazione.*, utente.*, prestazione.NomePrestazione, specializzazione.NomeSpecializzazione, centro_medico.Citta FROM prenotazione JOIN prestazione ON prenotazione.prestazione = prestazione.ID_Prestazione JOIN utente ON prenotazione.utente = utente.Email JOIN specializzazione ON specializzazione.ID_Specializzazione = prestazione.ID_Prestazione JOIN centro_medico ON centro_medico.ID_CentroMedico = prenotazione.CentroMedico WHERE prenotazione.Medico = ? AND prenotazione.Stato = 'Da Refertare'");
$stmt -> execute([$_SESSION['user']['Email']]);
$lista_pazienti_refertare = $stmt -> fetchAll(PDO::FETCH_ASSOC);  
?>

<!DOCTYPE html>
<html>

<head>
    <title>MedPlus - Dashboard Medico</title>
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
            <h2>Check-in/Check-out</h2>

            <?php if (!$lista_pazienti_check): ?>
                <p>Al momento non hai pazienti prenotati o accettati...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Nome e Cognome Paziente</th>
                        <th>Data e Ora Visita</th>
                        <th>Prestazione</th>
                        <th>Centro Medico</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($lista_pazienti_check as $p): ?>
                        <tr>
                            <td><?= $p['Nome'] ?> <?= $p['Cognome'] ?></td>
                            <td><?= date_format(date_create($p['Data']), 'd/m/Y') ?> alle <?= $p['Ora'] ?></td>
                            <td><?=  $p['NomePrestazione'] ?> (<?=  $p['NomeSpecializzazione'] ?>)</td> 
                            <td><?=  $p['Citta'] ?></td> 
                            <td><?=  $p['Stato'] ?></td> 
                            <td><a class="a_orange" href="functions/medico/cambia.php?id=<?php echo $p['ID_Prenotazione'] ?>">Cambia</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </section>
        <section class="dash_section">
            <h2>Refertazione</h2>

            <?php if (!$lista_pazienti_refertare): ?>
                <p>Al momento non hai pazienti da refertare...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Nome e Cognome Paziente</th>
                        <th>Data Visita</th>
                        <th>Prestazione</th>
                        <th>Centro Medico</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($lista_pazienti_refertare as $r): ?>
                        <tr>
                            <td><?= $r['Nome'] ?> <?= $r['Cognome'] ?></td>
                            <td><?= date_format(date_create($r['Data']), 'd/m/Y') ?> </td>
                            <td><?=  $r['NomePrestazione'] ?> (<?=  $r['NomeSpecializzazione'] ?>)</td>
                            <td><?=  $r['Citta'] ?></td>  
                            <td><a class="a_orange" href="functions/medico/referta.php?id=<?php echo $r['ID_Prenotazione'] ?>">Referta</a></td>
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