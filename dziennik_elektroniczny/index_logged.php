<?php
session_start();
$hideTopTiles = true;

if (!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];
$log = [];

/* OSTATNIE LEKCJE */
$q1 = $conn->query("
    SELECT 'Dodano lekcję: ' AS opis, temat AS szczegol, data
    FROM lekcje
    WHERE id_przedmiotu IN (
        SELECT id_przedmiotu FROM przedmioty WHERE id_nauczyciela = $id_nauczyciela
    )
    ORDER BY data DESC
    LIMIT 3
");
while ($row = $q1->fetch_assoc()) $log[] = $row;

/* OSTATNIE OCENY */
$q2 = $conn->query("
    SELECT 'Wprowadzono ocenę: ' AS opis, CONCAT(ocena, ' (waga ', waga, ')') AS szczegol, data
    FROM oceny
    WHERE id_przedmiotu IN (
        SELECT id_przedmiotu FROM przedmioty WHERE id_nauczyciela = $id_nauczyciela
    )
    ORDER BY data DESC
    LIMIT 3
");
while ($row = $q2->fetch_assoc()) $log[] = $row;

/* OSTATNIE OBECNOŚCI */
$q3 = $conn->query("
    SELECT 'Zapisano obecność: ' AS opis, status AS szczegol, data
    FROM obecnosci
    WHERE id_lekcji IN (
        SELECT id_lekcji FROM lekcje
        WHERE id_przedmiotu IN (
            SELECT id_przedmiotu FROM przedmioty WHERE id_nauczyciela = $id_nauczyciela
        )
    )
    ORDER BY data DESC
    LIMIT 3
");
while ($row = $q3->fetch_assoc()) $log[] = $row;

/* OSTATNIE UWAGI */
$q4 = $conn->query("
    SELECT 'Dodano uwagę: ' AS opis, tresc AS szczegol, data
    FROM uwagi
    WHERE id_nauczyciela = $id_nauczyciela
    ORDER BY data DESC
    LIMIT 3
");
while ($row = $q4->fetch_assoc()) $log[] = $row;

/* SORTOWANIE */
usort($log, function($a, $b) {
    return strtotime($b['data']) - strtotime($a['data']);
});

$log = array_slice($log, 0, 5);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dzienniczek+ — Panel nauczyciela</title>

    <style>
        /* MENU STYLE */
        .menu {
            background:#f0f0f0;
            padding:10px;
            margin-bottom:20px;
            border-bottom:1px solid #ccc;
        }

        .menu a {
            color:black;
            font-weight:bold;
            text-decoration:none;
            margin-right:15px;
        }

        .logo-box {
            font-size:22px;
            font-weight:bold;
            padding:6px 14px;
            border-radius:8px;
            background: linear-gradient(135deg, #4a90e2, #6fb3ff);
            color:white;
            margin-right:25px;
            display:inline-block;
        }

        .mini-tiles {
            display:flex;
            gap:15px;
            margin-top:10px;
            flex-wrap:wrap;
        }

        .mini-tile {
            background:white;
            padding:10px 15px;
            border-radius:8px;
            box-shadow:0 2px 6px rgba(0,0,0,0.15);
            font-size:14px;
            font-weight:bold;
            transition:0.2s;
        }

        .mini-tile:hover {
            background:#f1f7ff;
            transform:translateY(-2px);
        }

        .mini-tile a {
            color:#4a90e2;
            text-decoration:none;
        }

        .logout-btn {
            float:right;
            background: linear-gradient(135deg, #ff4b4b, #ff7b7b);
            color:white;
            padding:8px 16px;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
            box-shadow:0 2px 6px rgba(0,0,0,0.2);
            transition:0.2s;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e63a3a, #ff5c5c);
            transform:translateY(-2px);
        }

        /* STRONA GŁÓWNA */
        body {
            font-family: Arial, sans-serif;
            background: url('szkola.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.25);
            z-index: -1;
        }

        .overlay {
            background: rgba(255, 255, 255, 0.85);
            width: 85%;
            margin: 40px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        h1 {
            text-align: center;
            font-size: 36px;
            color: #333;
            margin-bottom: 30px;
        }

        .tiles {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }

        .tile {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            transition: 0.2s;
            font-size: 18px;
            font-weight: bold;
        }

        .tile:hover {
            background: #f1f7ff;
            transform: translateY(-3px);
        }

        .tile a {
            text-decoration: none;
            color: #4a90e2;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-box {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            min-height: 120px;
        }

        .section-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="overlay">

    <h1>Dzienniczek+</h1>

    <div class="tiles">
        <div class="tile"><a href="uczniowie.php">👨‍🎓 Uczniowie</a></div>
        <div class="tile"><a href="oceny.php">📘 Oceny</a></div>
        <div class="tile"><a href="obecnosci.php">🕒 Obecności</a></div>
        <div class="tile"><a href="lekcje.php">📚 Lekcje</a></div>
        <div class="tile"><a href="uwagi.php">✏️ Uwagi / Pochwały</a></div>
    </div>

    <div class="info-row">

        <div class="info-box">
            <div class="section-title">📝 Ostatnie działania</div>

            <?php if (empty($log)): ?>
                <p>Brak ostatnich działań.</p>
            <?php else: ?>
                <?php foreach ($log as $entry): ?>
                    <p>• <?= $entry['opis'] ?>
                        <strong><?= htmlspecialchars($entry['szczegol']) ?></strong>
                        (<?= $entry['data'] ?>)
                    </p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="info-box">
            <div class="section-title">⚡ Szybkie skróty</div>
            <p><a href="oceny.php" style="color:#4a90e2;">➤ Dodaj ocenę</a></p>
            <p><a href="obecnosci.php" style="color:#4a90e2;">➤ Zapisz obecność</a></p>
            <p><a href="lekcje.php" style="color:#4a90e2;">➤ Dodaj lekcję</a></p>
        </div>

    </div>

</div>

</body>
</html>


