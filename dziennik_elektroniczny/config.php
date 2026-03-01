<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "dziennik_elektroniczny";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $database);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

echo "";
?>




