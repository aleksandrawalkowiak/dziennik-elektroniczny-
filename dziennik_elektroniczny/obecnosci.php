<?php
session_start();
if (!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ============================================
   DODAWANIE OBECNOŚCI
============================================ */
if(isset($_POST['dodaj'])){
    $id_ucznia = intval($_POST['id_ucznia']);
    $id_lekcji = intval($_POST['id_lekcji']);
    $status = $_POST['status'];

    $row = $conn->query("SELECT data FROM lekcje WHERE id_lekcji = $id_lekcji")->fetch_assoc();
    $data = $row['data'];

    $check = $conn->query("
        SELECT 1 
        FROM lekcje l
        JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
        WHERE l.id_lekcji = $id_lekcji
        AND p.id_nauczyciela = $id_nauczyciela
    ");

    if ($check->num_rows == 0) {
        die('❌ Brak uprawnień do dodania obecności do tej lekcji.');
    }

    $stmt = $conn->prepare("
        INSERT INTO obecnosci (id_ucznia, id_lekcji, status, data)
        VALUES (?,?,?,?)
    ");
    $stmt->bind_param("iiss", $id_ucznia, $id_lekcji, $status, $data);
    $stmt->execute();
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
   POBIERANIE LEKCJI
============================================ */
$lekcje = $conn->query("
    SELECT l.id_lekcji, l.data, l.temat,
           p.nazwa AS przedmiot,
           k.nazwa AS klasa
    FROM lekcje l
    JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
    JOIN klasy k ON l.id_klasy = k.id_klasy
    WHERE p.id_nauczyciela = $id_nauczyciela
    ORDER BY l.data DESC, l.id_lekcji DESC
");

/* ============================================
   LISTA OBECNOŚCI — TYLKO TEGO NAUCZYCIELA
============================================ */
$obecnosci = $conn->query("
    SELECT o.id_obecnosci, o.status, o.data AS data_obecnosci,
           u.imie, u.nazwisko,
           l.temat, l.data AS data_lekcji,
           p.nazwa AS przedmiot,
           k.nazwa AS klasa
    FROM obecnosci o
    JOIN uczniowie u ON o.id_ucznia = u.id_ucznia
    JOIN lekcje l ON o.id_lekcji = l.id_lekcji
    JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
    JOIN klasy k ON l.id_klasy = k.id_klasy
    WHERE p.id_nauczyciela = $id_nauczyciela
    ORDER BY o.data DESC, u.nazwisko
");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Obecności</title>

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
            background: linear-gradient(135deg,#4a90e2,#6fb3ff);
            color:white;
        }
        .logout-btn {
            float:right;
            background: linear-gradient(135deg,#ff4b4b,#ff7b7b);
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
            background: rgba(255,255,255,0.85);
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
            padding:15px;
            border-radius:10px;
            margin-bottom:30px;
            text-align:center;
        }
        form select {
            padding:8px;
            margin:5px;
            border-radius:6px;
            border:1px solid #aaa;
        }
        form button {
            padding:8px 15px;
            background:#4a90e2;
            color:white;
            border:none;
            border-radius:6px;
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
        }
        td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:center;
        }

        /* ID */
        th.col-id, td.col-id {
            text-align:left !important;
            width:60px;
            padding-left:12px;
        }

        /* Akcje */
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

    <h2>Dodaj obecność</h2>

    <form method="POST">
        <select name="id_ucznia" required>
            <option value="">-- wybierz ucznia --</option>
            <?php while($u = $uczniowie->fetch_assoc()): ?>
                <option value="<?= $u['id_ucznia'] ?>"><?= $u['imie']." ".$u['nazwisko'] ?></option>
            <?php endwhile; ?>
        </select>

        <select name="id_lekcji" required>
            <option value="">-- wybierz lekcję --</option>
            <?php while($l = $lekcje->fetch_assoc()): ?>
                <option value="<?= $l['id_lekcji'] ?>">
                    <?= $l['data']." | ".$l['przedmiot']." | ".$l['klasa']." | ".$l['temat'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="status" required>
            <option value="">-- wybierz status --</option>
            <option value="obecny">Obecny</option>
            <option value="nieobecny">Nieobecny</option>
            <option value="spóźniony">Spóźniony</option>
        </select>

        <button type="submit" name="dodaj">Zapisz</button>
    </form>

    <h2>Lista obecności</h2>

    <table>
        <tr>
            <th class="col-id">ID</th>
            <th>Uczeń</th>
            <th>Przedmiot</th>
            <th>Klasa</th>
            <th>Temat</th>
            <th>Data lekcji</th>
            <th>Data obecności</th>
            <th>Status</th>
            <th class="actions">Akcje</th>
        </tr>

        <?php while($o = $obecnosci->fetch_assoc()): ?>
            <tr>
                <td class="col-id"><?= $o['id_obecnosci'] ?></td>
                <td><?= $o['imie']." ".$o['nazwisko'] ?></td>
                <td><?= $o['przedmiot'] ?></td>
                <td><?= $o['klasa'] ?></td>
                <td><?= $o['temat'] ?></td>
                <td><?= $o['data_lekcji'] ?></td>
                <td><?= $o['data_obecnosci'] ?></td>

                <?php
                    $status = strtolower($o['status']);

                    if ($status === "obecny") {
                        $kolor = "#00cc66"; // zielony
                    } 
                    else if ($status === "nieobecny") {
                        $kolor = "#ff0000"; // czerwony
                    } 
                    else if ($status === "spóźniony" || $status === "spozniony") {
                        $kolor = "#ff9900"; // pomarańczowy
                    } 
                    else {
                        $kolor = "#000000";
                    }
                ?>

                <td style="color: <?= $kolor ?>; font-weight:bold;">
                    <?= htmlspecialchars($o['status']) ?>
                </td>

                <td class="actions">
                    <a class="action-link" href="obecnosci_edytuj.php?id=<?= $o['id_obecnosci'] ?>">Edytuj</a> |
                    <a class="action-link" href="obecnosci_usun.php?id=<?= $o['id_obecnosci'] ?>"
                       onclick="return confirm('Usunąć obecność?');">Usuń</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>



