<?php
session_start();
if (!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];

if (!isset($_GET['id'])) {
    die("Brak ID obecności.");
}

$id = intval($_GET['id']);

/* ============================================
   SPRAWDZENIE UPRAWNIEŃ
============================================ */
$check = $conn->query("
    SELECT o.status
    FROM obecnosci o
    JOIN lekcje l ON o.id_lekcji = l.id_lekcji
    JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
    WHERE o.id_obecnosci = $id
    AND p.id_nauczyciela = $id_nauczyciela
");

if ($check->num_rows == 0) {
    die("❌ Brak uprawnień do edycji tej obecności.");
}

$row = $check->fetch_assoc();
$aktualny_status = $row['status'];

/* ============================================
   ZAPIS ZMIAN
============================================ */
if (isset($_POST['zapisz'])) {
    $nowy_status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE obecnosci 
        SET status = ?
        WHERE id_obecnosci = ?
    ");
    $stmt->bind_param("si", $nowy_status, $id);
    $stmt->execute();

    header("Location: obecnosci.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edycja obecności</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background:url('szkola.jpg') no-repeat center center fixed;
            background-size:cover;
            margin:0;
        }

        .overlay {
            background:rgba(255,255,255,0.85);
            width:50%;
            margin:60px auto;
            padding:25px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align:center;
            margin-bottom:25px;
        }

        form {
            display:flex;
            flex-direction:column;
            gap:15px;
        }

        select {
            padding:10px;
            border-radius:6px;
            border:1px solid #aaa;
        }

        button {
            padding:10px;
            background:#4a90e2;
            color:white;
            border:none;
            border-radius:6px;
            cursor:pointer;
            font-weight:bold;
        }

        button:hover {
            background:#357ac9;
        }

        .back {
            display:block;
            margin-top:15px;
            text-align:center;
            color:#4a90e2;
            text-decoration:underline;
            font-weight:bold;
        }
    </style>
</head>
<body>

<div class="overlay">

    <h2>Edycja obecności</h2>

    <form method="POST">

        <label>Status:</label>

        <select name="status" required>
            <option value="">-- wybierz status --</option>
            <option value="obecny"     <?= ($aktualny_status == "obecny" ? "selected" : "") ?>>Obecny</option>
            <option value="nieobecny"  <?= ($aktualny_status == "nieobecny" ? "selected" : "") ?>>Nieobecny</option>
            <option value="spóźniony"  <?= ($aktualny_status == "spóźniony" ? "selected" : "") ?>>Spóźniony</option>
        </select>

        <button type="submit" name="zapisz">Zapisz</button>
    </form>

    <a class="back" href="obecnosci.php">← Powrót do listy obecności</a>

</div>

</body>
</html>

