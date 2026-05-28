<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../../auth/logout.php');
    exit;
}

if($_SESSION['user']['Ruolo'] != 'Amministratore'){
        header('Location: ../../index.php');
    exit;
}

$stmt = $db->prepare("SELECT SUM(Costo) FROM prestazione JOIN prenotazione ON prenotazione.Prestazione = prestazione.ID_Prestazione WHERE prenotazione.Stato != 'Prenotato'");
$stmt -> execute();
$fatturato_totale = $stmt->fetch(PDO::FETCH_COLUMN);

$stmt = $db->prepare("SELECT centro_medico.Citta, SUM(Costo) AS Totale FROM prestazione JOIN prenotazione ON prenotazione.Prestazione = prestazione.ID_Prestazione JOIN centro_medico ON prenotazione.CentroMedico = centro_medico.ID_CentroMedico WHERE prenotazione.Stato != 'Prenotato' GROUP BY prenotazione.CentroMedico ORDER BY Totale DESC");
$stmt -> execute();
$fatturato_per_centro_medico = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT specializzazione.NomeSpecializzazione, SUM(Costo) AS Totale FROM prestazione JOIN prenotazione ON prenotazione.Prestazione = prestazione.ID_Prestazione JOIN specializzazione ON prestazione.Specializzazione = specializzazione.ID_Specializzazione WHERE prenotazione.Stato != 'Prenotato' GROUP BY prestazione.Specializzazione ORDER BY Totale DESC");
$stmt -> execute();
$fatturato_per_specializzazione = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT centro_medico.Citta, COUNT(*) AS Totale FROM prenotazione JOIN centro_medico ON prenotazione.CentroMedico = centro_medico.ID_CentroMedico GROUP BY prenotazione.CentroMedico ORDER BY Totale DESC");
$stmt -> execute();
$prenotazioni_per_centro_medico = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT prenotazione.Ora, COUNT(*) AS Totale FROM prenotazione GROUP BY prenotazione.Ora ORDER BY Totale DESC");
$stmt -> execute();
$affluenza_fasce_orarie = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT utente.Nome, utente.Cognome , COUNT(*) AS Totale FROM prenotazione JOIN utente ON prenotazione.Medico = utente.Email GROUP BY prenotazione.Medico ORDER BY Totale DESC;");
$stmt -> execute();
$prenotazioni_per_medico = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT AVG(DATEDIFF(referto.DataPubblicazione, prenotazione.Data)) FROM prenotazione JOIN referto ON referto.Prenotazione = prenotazione.ID_Prenotazione");
$stmt -> execute();
$tempo_medio_attesa_referto = $stmt->fetch(PDO::FETCH_COLUMN);

$stmt = $db->prepare("SELECT COUNT(*) FROM (SELECT COUNT(*) FROM prenotazione GROUP BY prenotazione.Utente) AS FirstFilter;");
$stmt -> execute();
$pazienti_con_min_una_prenotazione = $stmt->fetch(PDO::FETCH_COLUMN);

$stmt = $db->prepare("SELECT COUNT(*) FROM (SELECT COUNT(*) FROM prenotazione GROUP BY prenotazione.Utente HAVING COUNT(*) > 1) AS FirstFilter;SELECT COUNT(*) FROM (SELECT COUNT(*) FROM prenotazione GROUP BY prenotazione.Utente HAVING COUNT(*) > 1) AS FirstFilter");
$stmt -> execute();
$pazienti_con_min_due_prenotazioni = $stmt->fetch(PDO::FETCH_COLUMN);

$tasso_di_ritorno = $pazienti_con_min_due_prenotazioni/$pazienti_con_min_una_prenotazione;

$stmt = $db->prepare("SELECT prestazione.NomePrestazione, COUNT(*) AS Totale FROM prenotazione JOIN prestazione ON prenotazione.Prestazione = prestazione.ID_Prestazione GROUP BY prenotazione.Prestazione ORDER BY Totale DESC;");
$stmt -> execute();
$prenotazioni_per_prestazione = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>