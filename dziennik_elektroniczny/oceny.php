<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ============================================
   DODAWANIE OCENY
============================================ */
if(isset($_POST['dodaj'])){
    $id_ucznia = intval($_POST['id_ucznia']);
    $id_przedmiotu = intval($_POST['id_przedmiotu']);
    $ocena = $_POST['ocena'];
    $waga = intval($_POST['waga']);
    $opis = trim($_POST['opis']);

    if($ocena !== ''){
        $stmt = $conn->prepare("
            INSERT INTO oceny (id_ucznia, id_przedmiotu, ocena, waga, opis)
            VALUES (?,?,?,?,?)
        ");
        $stmt->bind_param("iisis", $id_ucznia, $id_przedmiotu, $ocena, $waga, $opis);
        $stmt->execute();
    }
}

/* ============================================
   POBIERANIE UCZNIÓW
============================================ */
$uczniowie = $conn->query("
    SELECT id_ucznia, imie, nazwisko 
    FROM uczniowie 
    ORDER BY nazwisko, imie
");

/* ============================================
   POBIERANIE PRZEDMIOTÓW NAUCZYCIELA
============================================ */
$przedmioty = $conn->query("
    SELECT id_przedmiotu, nazwa 
    FROM przedmioty 
    WHERE id_nauczyciela = $id_nauczyciela
    ORDER BY nazwa
");

/* ============================================
   LISTA OCEN
============================================ */
$oceny = $conn->query("
    SELECT o.id_oceny, o.ocena, o.waga, o.opis,
           u.imie, u.nazwisko,
           p.nazwa AS przedmiot,
           k.nazwa AS klasa
    FROM oceny o
    JOIN uczniowie u ON o.id_ucznia = u.id_ucznia
    JOIN przedmioty p ON o.id_przedmiotu = p.id_przedmiotu
    JOIN klasy k ON u.id_klasy = k.id_klasy
    WHERE p.id_nauczyciela = $id_nauczyciela
    ORDER BY o.id_oceny DESC
");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Oceny</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background:url('szkola.jpg') no-repeat center center fixed;
            background-size:cover;
            margin:0;
        }

        /* MENU */
        .menu {
            background:#f0f0f0;
            padding:10px 20px;
            border-bottom:1px solid #ccc;
        }
        .logo-box {
            font-size:22px;
            font-weight:bold;
            padding:6px 14px;
            border-radius:8px;
            background:linear-gradient(135deg,#4a90e2,#6fb3ff);
            color:white;
        }
        .logout-btn {
            float:right;
            background:linear-gradient(135deg,#ff4b4b,#ff7b7b);
            color:white;
            padding:8px 16px;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
        }

        /* KAFELKI */
        .top-tiles {
            display:flex;
            gap:15px;
            padding:12px 20px;
            background:rgba(255,255,255,0.85);
            border-bottom:1px solid #ccc;
            justify-content:center;
            flex-wrap:wrap;
        }
        .top-tile {
            background:white;
            padding:10px 18px;
            border-radius:10px;
            box-shadow:0 2px 6px rgba(0,0,0,0.15);
            font-size:15px;
            font-weight:bold;
            transition:0.2s;
        }
        .top-tile:hover {
            background:#f1f7ff;
            transform:translateY(-2px);
        }
        .top-tile a {
            color:#4a90e2;
            text-decoration:none;
        }

        /* STRONA */
        .overlay {
            background:rgba(255,255,255,0.85);
            width:80%;
            margin:40px auto;
            padding:25px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.2);
        }

        /* FORMULARZ */
        form {
            background:#f7f9fc;
            padding:20px;
            border-radius:10px;
            margin-bottom:30px;
            text-align:center;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }
        form select, form input, form textarea {
            width:80%;
            max-width:600px;
            padding:10px;
            margin:10px auto;
            border-radius:6px;
            border:1px solid #aaa;
            display:block;
        }
        form button {
            padding:10px 20px;
            background:#4a90e2;
            color:white;
            border:none;
            border-radius:6px;
            cursor:pointer;
        }

        /* TABELA */
        table {
            width:100%;
            border-collapse:collapse;
            background:white;
            border-radius:8px;
            overflow:hidden;
            table-layout:fixed;
        }
        th {
            background:#4a90e2;
            color:white;
            padding:12px;
            text-align:center;
            font-weight:bold;
        }
        td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:center;
            vertical-align:middle;
            word-wrap:break-word;
        }

        /* ID */
        th.col-id, td.col-id {
            text-align:left !important;
            width:60px;
            padding-left:12px;
        }

        /* Akcje wyśrodkowane */
        th.actions, td.actions {
            width:150px;
            text-align:center;
        }

        /* Linki Edytuj / Usuń */
        .action-link {
            color:#4a90e2;
            text-decoration:underline;
            font-weight:bold;
            margin:0 6px;
        }
        .action-link:hover {
            color:#357ac9;
        }
    </style>
</head>
<body>

<!-- MENU -->
<div class="menu">
    <span class="logo-box">Dzienniczek+</span>
    <a class="logout-btn" href="logout.php">Wyloguj</a>
</div>

<!-- KAFELKI -->
<div class="top-tiles">
    <div class="top-tile"><a href="index_logged.php">📋 Strona główna</a></div>
    <div class="top-tile"><a href="uczniowie.php">👨‍🎓 Uczniowie</a></div>
    <div class="top-tile"><a href="oceny.php">📘 Oceny</a></div>
    <div class="top-tile"><a href="obecnosci.php">🕒 Obecności</a></div>
    <div class="top-tile"><a href="lekcje.php">📚 Lekcje</a></div>
    <div class="top-tile"><a href="uwagi.php">✏️ Uwagi/Pochwały</a></div>
</div>

<div class="overlay">

    <h2>Dodaj ocenę</h2>

    <form method="POST">
        <select name="id_ucznia" required>
            <option value="">-- wybierz ucznia --</option>
            <?php while($u = $uczniowie->fetch_assoc()): ?>
                <option value="<?= $u['id_ucznia'] ?>"><?= $u['imie']." ".$u['nazwisko'] ?></option>
            <?php endwhile; ?>
        </select>

        <select name="id_przedmiotu" required>
            <option value="">-- wybierz przedmiot --</option>
            <?php while($p = $przedmioty->fetch_assoc()): ?>
                <option value="<?= $p['id_przedmiotu'] ?>"><?= $p['nazwa'] ?></option>
            <?php endwhile; ?>
        </select>

        <input type="number" name="ocena" min="1" max="6" step="0.5" placeholder="Ocena" required>
        <input type="number" name="waga" min="1" max="10" placeholder="Waga" required>
        <textarea name="opis" placeholder="Opis (opcjonalnie)"></textarea>

        <button type="submit" name="dodaj">Zapisz</button>
    </form>

    <h2>Lista ocen</h2>

    <table>
        <tr>
            <th class="col-id">ID</th>
            <th>Uczeń</th>
            <th>Przedmiot</th>
            <th>Klasa</th>
            <th>Ocena</th>
            <th>Waga</th>
            <th>Opis</th>
            <th class="actions">Akcje</th>
        </tr>

        <?php while($o = $oceny->fetch_assoc()): ?>
            <tr>
                <td class="col-id"><?= $o['id_oceny'] ?></td>
                <td><?= $o['imie']." ".$o['nazwisko'] ?></td>
                <td><?= $o['przedmiot'] ?></td>
                <td><?= $o['klasa'] ?></td>

                <?php
                    // Pobieramy ocenę jako liczbę zmiennoprzecinkową
                    $oc = floatval(str_replace(',', '.', $o['ocena']));

                    if ($oc <= 1.5) $kolor = "#ff0000";        // czerwony
                    else if ($oc <= 2.5) $kolor = "#ff4d4d";   // jasnoczerwony
                    else if ($oc <= 3.5) $kolor = "#ff9900";   // pomarańczowy
                    else if ($oc <= 4.5) $kolor = "#0099ff";   // niebieski
                    else if ($oc <= 5.5) $kolor = "#00cc66";   // jasnozielony
                    else $kolor = "#009933";                   // ciemnozielony
                ?>

                <td style="color: <?= $kolor ?>; font-weight:bold;">
                    <?= htmlspecialchars($o['ocena']) ?>
                </td>

                <td><?= $o['waga'] ?></td>
                <td><?= $o['opis'] ?></td>

                <td class="actions">
                    <a class="action-link" href="oceny_edytuj.php?id=<?= $o['id_oceny'] ?>">Edytuj</a> |
                    <a class="action-link" href="oceny_usun.php?id=<?= $o['id_oceny'] ?>"
                       onclick="return confirm('Usunąć ocenę?');">Usuń</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>




