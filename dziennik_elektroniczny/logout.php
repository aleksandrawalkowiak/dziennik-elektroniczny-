<?php
session_start();

// Usuwamy wszystkie dane sesji
session_unset();
session_destroy();

// Usuwamy cookie "Zapamiętaj mnie"
setcookie("remember_teacher", "", time() - 3600, "/");

// Przekierowanie na stronę logowania
header("Location: /dziennik_elektroniczny/index.php");
exit;
