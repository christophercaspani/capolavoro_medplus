<?php
session_start();
require_once 'db.php';
include 'functions/all/commons.php';
?>

<!DOCTYPE html>

<html>

<head>
    <title>MedPlus - Home</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <meta charset="utf-8">
</head>

<body>
    <header>
        <h1>MedPlus</h1>
        <a class="index_a" href="auth/login.php">
            <?php if (!isset($_SESSION['user'])) {
                echo "Login";
            } else {
                echo "Area Riservata";
            }

            ?>
        </a>
    </header>
    <div class="content">
        <h1></h1>
        <div class="home_sections">
            <section>
                <h2>I Nostri Centri</h2>
                <table>
                    <?php foreach ($centri_medici as $c): ?>
                        <tr>
                            <td><?= $c['Citta'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
            <section>
                <h2>Le Nostre Specializzazioni</h2>
                <table>
                    <?php foreach ($elenco_specializzazioni as $s): ?>
                        <tr>
                            <td><?= $s['NomeSpecializzazione'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
            <section>
                <h2>I Nostri Medici</h2>
                <table>
                    <?php foreach ($lista_medici as $m): ?>
                        <tr>
                            <td><?= $m['Nome'] ?>   <?= $m['Cognome'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
            <section>

                    <h2>Le Nostre Prestazioni</h2>
                <?php for ($i = 0; $i < count($elenco_prestazioni_per_spec); $i++): ?>
                    <h3><?php echo $elenco_specializzazioni[$i]["NomeSpecializzazione"]; ?></h3>
                    <table>
                        <tr>
                            <th class="tx_one">Prestazione</th>
                            <th>Costo</th>
                        </tr>
                        <?php foreach ($elenco_prestazioni_per_spec[$i] as $p): ?>
                            <tr>
                                <td class="tx_one"><?= $p['NomePrestazione'] ?></td>
                                <td><?= $p['Costo'] ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endfor; ?>
            </section>
        </div>
    </div>
    <footer>
        <p>MedPlus - &copy Copyright 2026</p>
    <footer>
</body>

</html>