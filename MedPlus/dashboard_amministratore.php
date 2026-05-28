<?php
session_start();
require_once 'db.php';
include 'functions/all/commons.php';
include 'functions/admin/stats.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/logout.php');
    exit;
}
if ($_SESSION['user']['Ruolo'] != 'Amministratore') {
    header('Location: index.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM utente ORDER BY Ruolo");
$stmt->execute();
$lista_utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM prestazione JOIN specializzazione ON prestazione.Specializzazione = specializzazione.ID_Specializzazione ORDER BY Specializzazione, Costo");
$stmt->execute();
$lista_prestazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM specializzato JOIN specializzazione ON specializzato.Specializzato = specializzazione.ID_Specializzazione JOIN utente ON specializzato.Specializzante = utente.Email");
$stmt->execute();
$elenco_specializzati = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>MedPlus - Dashboard Amministratore</title>
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
    <h1 class="h1_style_two">Ciao <?php echo $_SESSION['user']['Nome']; ?>!</h1>
    <div class="dash_sections">
        <section class="dash_section">
            <h2>Lista Utenti</h2>

            <?php if (!$lista_utenti): ?>
                <p>Al momento non ci sono utenti...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Nome e Cognome</th>
                        <th>Email</th>
                        <th>Ruolo</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($lista_utenti as $l_u): ?>
                        <tr>
                            <td><?= $l_u['Nome'] ?> <?= $l_u['Cognome'] ?></td>
                            <td><?= $l_u['Email'] ?></td>
                            <td><?= $l_u['Ruolo'] ?></td>
                            <td><a class="a_orange" href="functions/admin/user/edit_user.php?email=<?php echo $l_u['Email'] ?>">Modifica</a> <a class="a_red" href="functions/admin/user/delete_user.php?email=<?php echo $l_u['Email'] ?>">Elimina</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div><a class="a_green" href="functions/admin/user/create_user.php">Nuovo Utente</a></div>
        </section>
        <section class="dash_section">
            <h2>Lista Prestazioni</h2>

            <?php if (!$elenco_prestazioni): ?>
                <p>Al momento non ci sono prestazioni...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Prestazione</th>
                        <th>Costo</th>
                        <th>Specializzazione</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($lista_prestazioni as $l_p): ?>
                        <tr>
                            <td><?= $l_p['NomePrestazione'] ?></td>
                            <td><?= $l_p['Costo'] ?> €</td>
                            <td><?= $l_p['NomeSpecializzazione'] ?></td>
                            <td><a class="a_orange" href="functions/admin/prestazione/edit_prestazione.php?id=<?php echo $l_p['ID_Prestazione'] ?>">Modifica</a> <a class="a_red" href="functions/admin/prestazione/delete_prestazione.php?id=<?php echo $l_p['ID_Prestazione'] ?>">Elimina</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div><a class="a_green" href="functions/admin/prestazione/create_prestazione.php">Nuova Prestazione</a></div>
        </section>
        <section class="dash_section">
            <h2>Lista Centri Medici</h2>

            <?php if (!$centri_medici): ?>
                <p>Al momento non ci sono centri medici...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Città</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($centri_medici as $c_m): ?>
                        <tr>
                            <td><?= $c_m['ID_CentroMedico'] ?></td>
                            <td><?= $c_m['Citta'] ?></td>
                            <td><a class="a_orange" href="functions/admin/centro_medico/edit_centro_medico.php?id=<?php echo $c_m['ID_CentroMedico'] ?>">Modifica</a> <a class="a_red" href="functions/admin/centro_medico/delete_centro_medico.php?id=<?php echo $c_m['ID_CentroMedico'] ?>">Elimina</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div><a class="a_green" href="functions/admin/centro_medico/create_centro_medico.php">Nuovo Centro Medico</a></div>
        </section>
        <section class="dash_section">
            <h2>Lista Specializzazioni</h2>

            <?php if (!$elenco_specializzazioni): ?>
                <p>Al momento non ci sono specializzazioni...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Specializzazione</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($elenco_specializzazioni as $e_s): ?>
                        <tr>
                            <td><?= $e_s['ID_Specializzazione'] ?></td>
                            <td><?= $e_s['NomeSpecializzazione'] ?></td>
                            <td><a class="a_orange" href="functions/admin/specializzazione/edit_specializzazione.php?id=<?php echo $e_s['ID_Specializzazione'] ?>">Modifica</a> <a class="a_red" href="functions/admin/specializzazione/delete_specializzazione.php?id=<?php echo $e_s['ID_Specializzazione'] ?>">Elimina</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div><a class="a_green" href="functions/admin/specializzazione/create_specializzazione.php">Nuova Specializzazione</a></div>
        </section>
        <section class="dash_section">
            <h2>Lista Specializzati</h2>

            <?php if (!$elenco_specializzati): ?>
                <p>Al momento non ci sono specializzati...</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Medico</th>
                        <th>Specializzazione</th>
                        <th>Azioni</th>
                    </tr>
                    <?php foreach ($elenco_specializzati as $e_sp): ?>
                        <tr>
                            <td><?= $e_sp['Nome'] ?> <?= $e_sp['Cognome'] ?></td>
                            <td><?= $e_sp['NomeSpecializzazione'] ?></td>
                            <td><a class="a_orange" href="functions/admin/specializzato/edit_specializzato.php?id=<?php echo $e_sp['ID_Specializzazione'] ?>&email=<?php echo $e_sp['Email'] ?>">Modifica</a> <a class="a_red" href="functions/admin/specializzato/delete_specializzato.php?id=<?php echo $e_sp['ID_Specializzazione'] ?>&email=<?php echo $e_sp['Email'] ?>">Elimina</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div><a class="a_green" href="functions/admin/specializzato/create_specializzato.php">Nuovo Specializzato</a></div>
        </section>
        <section class="dash_section">
            <h2>Statistiche Azienda</h2>
            <h3>Statistiche Economiche</h3>
            <p>Fatturato Totale: <b><?php echo $fatturato_totale . " €"; ?></b></p>
            <p><b>Fatturato Per Centro Medico</b></p>
            <?php if ($fatturato_per_centro_medico): ?>
                <?php foreach ($fatturato_per_centro_medico as $f_p_c_m): ?>
                    <span><?= $f_p_c_m['Citta'] ?>: <?= $f_p_c_m['Totale'] ?> €</span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="a_red">Data Not Available</p>
            <?php endif; ?>
            <p><b>Fatturato Per Specializzazione</b></p>
            <?php if ($fatturato_per_specializzazione): ?>
                <?php foreach ($fatturato_per_specializzazione as $f_p_s): ?>
                    <span><?= $f_p_s['NomeSpecializzazione'] ?>: <?= $f_p_s['Totale'] ?> €</span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="a_red">Data Not Available</p>
            <?php endif; ?>
            <h3>Statistiche Operative/Flusso</h3>
            <p><b>Prenotazioni Per Centro Medico</b></p>
            <?php if ($prenotazioni_per_centro_medico): ?>
                <?php foreach ($prenotazioni_per_centro_medico as $p_p_c_m): ?>
                    <span><?= $p_p_c_m['Citta'] ?>: <?= $p_p_c_m['Totale'] ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="a_red">Data Not Available</p>
            <?php endif; ?>
            <p><b>Affluenza Fasce Orarie</b></p>
            <?php if ($affluenza_fasce_orarie): ?>
                <?php foreach ($affluenza_fasce_orarie as $a_f_o): ?>
                    <span><?= $a_f_o['Ora'] ?>: <?= $a_f_o['Totale'] ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="a_red">Data Not Available</p>
            <?php endif; ?>
            <h3>Statistiche Personale</h3>
            <p><b>Prenotazioni Per Medico</b></p>
            <?php if ($prenotazioni_per_medico): ?>
                <?php foreach ($prenotazioni_per_medico as $p_p_m): ?>
                    <span><?= $p_p_m['Nome'] ?> <?= $p_p_m['Cognome'] ?>: <?= $p_p_m['Totale'] ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="a_red">Data Not Available</p>
            <?php endif; ?>
            <p>Tempo Medio Attesa Referto: <b><?php echo $tempo_medio_attesa_referto . " giorni"; ?></b></p>
            <h3>Statistiche Pazienti</h3>
            <p>Tasso di Ritorno: <?php echo $tasso_di_ritorno; ?>%</p>
            <p><b>Prenotazioni Per Prestazione</b></p>
            <?php if ($prenotazioni_per_prestazione): ?>
                <?php foreach ($prenotazioni_per_prestazione as $p_p_p): ?>
                    <span><?= $p_p_p['NomePrestazione'] ?>: <?= $p_p_p['Totale'] ?></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="a_red">Data Not Available</p>
            <?php endif; ?>
        </section>
    </div>
    <footer>
        <p>MedPlus - &copy Copyright 2026</p>
        <footer>
</body>

</html>