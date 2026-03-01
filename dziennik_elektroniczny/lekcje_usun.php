<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id = intval($_GET['id']);
$id_nauczyciela = $_SESSION['id_nauczyciela'];

$check = $conn->query("
    SELECT l.id_lekcji
    FROM lekcje l
    JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
    WHERE l.id_lekcji = $id
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
        ❌ Brak uprawnień do usunięcia tej lekcji.<br><br>
        <a href='lekcje.php' style='color:#4a90e2; text-decoration:underline; font-weight:bold;'>← Powrót</a>
    </div>";
    exit;
}

$conn->query("DELETE FROM lekcje WHERE id_lekcji = $id");

header("Location: lekcje.php");
exit;
