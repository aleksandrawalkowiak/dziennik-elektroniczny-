<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];
$id = intval($_GET['id']);

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ============================================
   SPRAWDZENIE UPRAWNIEŃ
============================================ */
$check = $conn->query("
    SELECT o.*, p.id_nauczyciela 
    FROM oceny o
    JOIN przedmioty p ON o.id_przedmiotu = p.id_przedmiotu
    WHERE o.id_oceny = $id
");

if ($check->num_rows == 0) {
    die("❌ Taka ocena nie istnieje.");
}

$ocena = $check->fetch_assoc();

if ($ocena['id_nauczyciela'] != $id_nauczyciela) {
    die("❌ Nie masz uprawnień do edycji tej oceny.");
}

/* ============================================
   ZAPIS ZMIAN (PRG)
============================================ */
if(isset($_POST['zapisz'])){
    $nowa = $_POST['ocena'];
    $waga = $_POST['waga'];
    $opis = $_POST['opis'];

    $stmt = $conn->prepare("
        UPDATE oceny SET ocena=?, waga=?, opis=? WHERE id_oceny=?
    ");
    $stmt->bind_param("dssi", $nowa, $waga, $opis, $id);
    $stmt->execute();

    header("Location: oceny.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edycja oceny</title>

    <style>
        /* ====== MENU ====== */
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
            background: linear-gradient(135deg, #4a90e2, #6fb3ff);
            color:white;
            display:inline-block;
        }
        .logout-btn {
            float:right;
            background: linear-gradient(135deg, #ff4b4b, #ff7b7b);
            color:white;
            padding:8px 16px;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
        }

        /* ====== KAFELKI ====== */
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

        /* ====== STRONA ====== */
        body {
            font-family: Arial, sans-serif;
            background: url('szkola.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }
        .overlay {
            background: rgba(255, 255, 255, 0.85);
            width: 50%;
            margin: 60px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #aaa;
            font-size: 15px;
        }

        button {
            padding: 10px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #357ac9;
        }

        /* ====== LINK POWROTNY  ====== */
        .back {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #4a90e2;
            text-decoration: underline;
            font-weight: bold;
            font-size: 16px;
        }

        .back:hover {
            color: #357ac9;
        }
    </style>
</head>
<body>

<!-- MENU -->
<div class="menu">
    <span class="logo-box">Dzienniczek+</span>
    <a href="/dziennik_elektroniczny/logout.php" class="logout-btn">🚪 Wyloguj</a>
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
    <h2>Edycja oceny</h2>

    <form method="POST">
        <label>Ocena:</label>
        <input type="number" step="0.5" name="ocena" value="<?= $ocena['ocena'] ?>" required>

        <label>Waga:</label>
        <input type="number" name="waga" value="<?= $ocena['waga'] ?>" required>

        <label>Opis:</label>
        <input type="text" name="opis" value="<?= $ocena['opis'] ?>">

        <button type="submit" name="zapisz">Zapisz</button>
    </form>

    <a class="back" href="oceny.php">← Powrót do listy ocen</a>
</div>

</body>
</html>



