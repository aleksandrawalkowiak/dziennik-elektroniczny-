<?php
$host = "localhost";
$user = "root";       
$pass = "";           
$db   = "dziennik_elektroniczny";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobieranie uczniów
$sql = "SELECT * FROM uczniowie";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: ".$row["id_ucznia"]." - ".$row["imie"]." ".$row["nazwisko"]."<br>";
    }
} else {
    echo "Brak uczniów w bazie.";
}

$conn->close();
?>