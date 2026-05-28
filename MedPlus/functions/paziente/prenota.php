<?php
session_start();
require_once '../../db.php';
include '../all/commons.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth/logout.php');
    exit;
}

if ($_SESSION['user']['Ruolo'] != 'Paziente') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['step'] += 1;

    switch ($_SESSION['step']) {
        case 1:
            $_SESSION['prestazione'] = $_POST['prestazione'];
            break;
        case 2:
            $_SESSION['medico'] = $_POST['medico'];
            break;
        case 3:
            $_SESSION['data'] = $_POST['data'];
            break;
        case 4:
            $num_centri = isset($centri_medici) ? $centri_medici[count($centri_medici)-1]['ID_CentroMedico'] : 1;
            do{
            $centro = rand(1, $num_centri);
            $stmt = $db->prepare("SELECT * FROM centro_medico WHERE ID_CentroMedico = ?");
            $stmt->execute([$centro]);
            $qr = $stmt->fetch(PDO::FETCH_ASSOC);
            }while(!$qr);
            $stmt = $db->prepare("INSERT INTO `prenotazione` (`ID_Prenotazione`, `Utente`, `Medico`, `Data`, `Ora`, `Prestazione`, `Stato`, `CentroMedico`) VALUES (NULL, ?, ?, ?, ?, ?, 'Prenotato', ?)");
            $stmt->execute([$_SESSION['user']['Email'], $_SESSION['medico'], $_SESSION['data'], $_POST['ora'], $_SESSION['prestazione'], $centro ]);
            unset($_SESSION['step']);
            header('Location: ../../dashboard_paziente.php');

            exit;
        default:
            header('Location: ../../index.php');
            exit;
    }

    header('Location: prenota.php');
    exit;
} else {

    if (!isset($_SESSION['step'])) {
        $_SESSION['step'] = 0;
    }
    if ($_SESSION['step'] < 3)
        unset($_SESSION['data']);
    if ($_SESSION['step'] < 2)
        unset($_SESSION['medico']);
    if ($_SESSION['step'] < 1)
        unset($_SESSION['prestazione']);

    if ($_SESSION['step'] >= 1) {
        $stmt = $db->prepare("SELECT * FROM prestazione JOIN specializzazione ON prestazione.Specializzazione = specializzazione.ID_Specializzazione WHERE ID_Prestazione = ?");
        $stmt->execute([$_SESSION['prestazione']]);
        $ris = $stmt->fetch(PDO::FETCH_ASSOC);
        $prestazione = $ris['NomePrestazione'];
        $specializzazione = $ris['NomeSpecializzazione'];

        $stmt = $db->prepare("SELECT * FROM utente JOIN specializzato ON specializzato.Specializzante = utente.Email JOIN prestazione ON specializzato.Specializzato = prestazione.Specializzazione WHERE prestazione.ID_Prestazione = ?");
        $stmt->execute([$_SESSION['prestazione']]);
        $lista_medici_per_specializzazione = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if ($_SESSION['step'] >= 2) {
        $stmt = $db->prepare("SELECT * FROM utente WHERE Email = ?");
        $stmt->execute([$_SESSION['medico']]);
        $ris = $stmt->fetch(PDO::FETCH_ASSOC);
        $nome_medico = $ris['Nome'];
        $cognome_medico = $ris['Cognome'];
    }
    if ($_SESSION['step'] >= 3) {

        $stmt = $db->prepare("SHOW COLUMNS FROM prenotazione LIKE 'Ora'");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $ore = str_replace("'", "", explode(",", substr($res['Type'], 5, -1)));
        $stmt = $db->prepare("SELECT Ora FROM prenotazione WHERE Data = ? AND medico = ?");
        $stmt->execute([$_SESSION['data'], $_SESSION['medico']]);
        $ris = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $ore = array_values(array_diff($ore, $ris));
    }
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Prenota Visita</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../style.css">
    <link rel="icon" type="image/x-icon" href="../../img/favicon.ico">
</head>

<body>
    <h1 class="h1_style_one">Prenota Visita</h1>
    <form class="login_form" method="post" action="prenota.php">
        <?php if ($_SESSION['step'] > 0): ?>
            <p class="pren_info">Prestazione Selezionata:
                <?php echo $prestazione . " (" . $specializzazione . ")"; ?><a
                    href="prenota_update_step.php?new_step=0">Modifica</a>
            </p><?php endif; ?>
        <?php if ($_SESSION['step'] > 1): ?>
            <p class="pren_info">Medico Selezionato: <?php echo $nome_medico . " " . $cognome_medico; ?><a
                    href="prenota_update_step.php?new_step=1">Modifica</a></p>
        <?php endif; ?>
        <?php if ($_SESSION['step'] > 2): ?>
            <p class="pren_info">Data Selezionata:
                <?php echo date_format(date_create($_SESSION['data']), 'd/m/Y'); ?><a
                    href="prenota_update_step.php?new_step=2">Modifica</a>
            </p><?php endif; ?>
        <div>
            <?php switch ($_SESSION['step']):
                case 0: ?>

                    <label class="text_center">Prestazione</label>
                    <select name="prestazione" required>
                        <?php if (isset($elenco_prestazioni)): ?>
                            <?php foreach ($elenco_prestazioni as $e): ?>
                                <option value="<?= $e['ID_Prestazione'] ?>"><?= $e['NomePrestazione'] ?> (<?= $e['Costo'] ?> €)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php break; ?>
                <?php
                case 1: ?>
                    <label class="text_center">Medico</label>
                    <select name="medico" required>
                        <?php if (isset($lista_medici_per_specializzazione)): ?>
                            <?php foreach ($lista_medici_per_specializzazione as $m): ?>
                                <option value="<?= $m['Email'] ?>"><?= $m['Nome'] ?> <?= $m['Cognome'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php break; ?>
                <?php
                case 2: ?>
                    <label class="text_center">Data</label>
                    <input type="date" name="data" required>
                    <?php break; ?>
                <?php
                case 3: ?>
                    <label class="text_center">Ora</label>
                    <select name="ora" required>
                        <?php if (isset($ore)): ?>
                            <?php for ($i = 0; $i < count($ore); $i++): ?>

                                <option value="<?= $ore[$i] ?>"><?= $ore[$i] ?></option>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </select>
                    <?php break; ?>
                <?php
                default: ?>
                    <?php break; ?>
            <?php endswitch; ?>
        </div>

        <input type="submit" value="<?php if ($_SESSION['step'] != 3) {
                                        echo "Avanti";
                                    } else {
                                        echo "Prenota";
                                    } ?>">
    </form>
    <div class="login_help_center">
        <a href="../../dashboard_paziente.php">Torna alla Dashboard</a>
    </div>

</body>

</html>