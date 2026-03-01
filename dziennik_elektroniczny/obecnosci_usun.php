<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];
$id = intval($_GET['id']);

/* ============================================
   BLOKADA UPRAWNIEŃ
============================================ */
$check = $conn->query("
    SELECT o.id_obecnosci
    FROM obecnosci o
    JOIN lekcje l ON o.id_lekcji = l.id_lekcji
    JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
    WHERE o.id_obecnosci = $id
    AND p.id_nauczyciela = $id_nauczyciela
");

if ($check->num_rows == 0) {
    echo "<div style='
        margin:50px auto;
        width:50%;
        padding:20px;
        background:#ffe6e6;
        border:2px solid #ff4b4b;
        border-radius:10px;
        font-family:Arial;
        text-align:center;
        font-size:18px;
        color:#cc0000;
    '>
        ❌ Brak uprawnień do usunięcia tej obecności.<br><br>
        <a href='obecnosci.php' style='color:#4a90e2; text-decoration:underline; font-weight:bold;'>
            ← Powrót
        </a>
    </div>";
    exit;
}

/* ============================================
   USUWANIE
============================================ */
$conn->query("DELETE FROM obecnosci WHERE id_obecnosci = $id");

header("Location: obecnosci.php");
exit;
