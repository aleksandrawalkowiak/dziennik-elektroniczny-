<?php
function getUczniowie($conn) {
    $sql = "SELECT uczniowie.id_ucznia, uczniowie.imie, uczniowie.nazwisko, klasy.nazwa AS klasa
            FROM uczniowie
            JOIN klasy ON uczniowie.id_klasy = klasy.id_klasy";
    return $conn->query($sql);
}
?>
