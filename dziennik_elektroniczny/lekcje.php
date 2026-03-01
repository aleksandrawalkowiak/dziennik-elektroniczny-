<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_nauczyciela = $_SESSION['id_nauczyciela'];

/* ============================================
   DODAWANIE LEKCJI
============================================ */
if(isset($_POST['dodaj'])){
    $id_przedmiotu = intval($_POST['id_przedmiotu']);
    $id_klasy = intval($_POST['id_klasy']);
    $data = $_POST['data'];
    $temat = trim($_POST['temat']);

    $check = $conn->query("
        SELECT 1 FROM przedmioty 
        WHERE id_przedmiotu = $id_przedmiotu
        AND id_nauczyciela = $id_nauczyciela
    ");

    if ($check->num_rows == 0) {
        die("❌ Brak uprawnień do dodania lekcji.");
    }

    if($temat !== ''){
        $stmt = $conn->prepare("
            INSERT INTO lekcje (id_przedmiotu, id_klasy, data, temat)
            VALUES (?,?,?,?)
        ");
        $stmt->bind_param("iiss", $id_przedmiotu, $id_klasy, $data, $temat);
        $stmt->execute();
    }
}

/* ============================================
   POBIERANIE PRZEDMIOTÓW
============================================ */
$przedmioty = $conn->query("
    SELECT id_przedmiotu, nazwa 
    FROM przedmioty 
    WHERE id_nauczyciela = $id_nauczyciela
    ORDER BY nazwa
");

/* ============================================
   POBIERANIE KLAS
============================================ */
$klasy = $conn->query("
    SELECT id_klasy, nazwa 
    FROM klasy 
    ORDER BY nazwa
");

/* ============================================
   LISTA LEKCJI
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
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lekcje</title>

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

        form {
            background:#f7f9fc;
            padding:15px;
            border-radius:10px;
            margin-bottom:30px;
        }

        select, input {
            padding:8px;
            margin:5px;
            border-radius:6px;
            border:1px solid #aaa;
        }

        button {
            padding:8px 15px;
            background:#4a90e2;
            color:white;
            border:none;
            border-radius:6px;
        }

        /* ====== TABELA  ====== */
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

        /* WYŚRODKOWANA KOLUMNA AKCJE */
        th.actions, td.actions {
            width:150px;
            text-align:center;
        }

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

    <h2>Dodaj lekcję</h2>

    <form method="POST">
        <select name="id_przedmiotu" required>
            <option value="">-- wybierz przedmiot --</option>
            <?php while($p = $przedmioty->fetch_assoc()): ?>
                <option value="<?= $p['id_przedmiotu'] ?>"><?= $p['nazwa'] ?></option>
            <?php endwhile; ?>
        </select>

        <select name="id_klasy" required>
            <option value="">-- wybierz klasę --</option>
            <?php while($k = $klasy->fetch_assoc()): ?>
                <option value="<?= $k['id_klasy'] ?>"><?= $k['nazwa'] ?></option>
            <?php endwhile; ?>
        </select>

        <input type="date" name="data" required>
        <input type="text" name="temat" placeholder="Temat lekcji" required>

        <button type="submit" name="dodaj">Zapisz</button>
    </form>

    <h2>Lista lekcji</h2>

    <table>
        <tr>
            <th class="col-id">ID</th>
            <th>Data</th>
            <th>Temat</th>
            <th>Przedmiot</th>
            <th>Klasa</th>
            <th class="actions">Akcje</th>
        </tr>

        <?php while($l = $lekcje->fetch_assoc()): ?>
            <tr>
                <td class="col-id"><?= $l['id_lekcji'] ?></td>
                <td><?= $l['data'] ?></td>
                <td><?= htmlspecialchars($l['temat']) ?></td>
                <td><?= htmlspecialchars($l['przedmiot']) ?></td>
                <td><?= htmlspecialchars($l['klasa']) ?></td>

                <td class="actions">
                    <a class="action-link" href="lekcje_edytuj.php?id=<?= $l['id_lekcji'] ?>">Edytuj</a> |
                    <a class="action-link" href="lekcje_usun.php?id=<?= $l['id_lekcji'] ?>"
                       onclick="return confirm('Usunąć lekcję?');">Usuń</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>




