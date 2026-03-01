<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];
$id = intval($_GET['id']);

/* Sprawdzenie uprawnień */
$check = $conn->query("
    SELECT 1 FROM uwagi
    WHERE id_uwagi = $id
    AND id_nauczyciela = $id_nauczyciela
");

if ($check->num_rows == 0) {
    die("❌ Brak uprawnień do usunięcia tej uwagi.");
}

/* Usuwanie */
$conn->query("DELETE FROM uwagi WHERE id_uwagi = $id");

header("Location: uwagi.php");
exit;
