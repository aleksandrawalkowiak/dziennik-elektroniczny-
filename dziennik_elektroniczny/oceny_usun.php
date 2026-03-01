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
   BLOKADA — sprawdzamy, czy ocena należy do nauczyciela
============================================ */
$check = $conn->query("
    SELECT o.id_oceny 
    FROM oceny o
    JOIN przedmioty p ON o.id_przedmiotu = p.id_przedmiotu
    WHERE o.id_oceny = $id
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
        ❌ Brak uprawnień do usunięcia tej oceny.<br><br>
        <a href=\"oceny.php\" style=\"
            color:#4a90e2;
            text-decoration:underline;
            font-weight:bold;
        \">← Powrót</a>
    </div>";
    exit;
}

/* ============================================
   USUWANIE
============================================ */
$conn->query("DELETE FROM oceny WHERE id_oceny = $id");

header("Location: oceny.php");
exit;

