<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'config.php';

/* ============================================
   AUTOLOGOWANIE Z COOKIE
============================================ */
if(!isset($_SESSION['id_nauczyciela']) && isset($_COOKIE['remember_teacher'])){
    $_SESSION['id_nauczyciela'] = $_COOKIE['remember_teacher'];
    header("Location: index_logged.php");
    exit;
}

/* ============================================
   LOGOWANIE
============================================ */
if(isset($_POST['login'])){
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $stmt = $conn->prepare("SELECT * FROM nauczyciele WHERE login=?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        if($haslo === $row['haslo']) {

            $_SESSION['id_nauczyciela'] = $row['id_nauczyciela'];

            // ZAPAMIĘTAJ MNIE — zapisujemy TYLKO ID nauczyciela
            if(isset($_POST['remember'])){
                setcookie("remember_teacher", $row['id_nauczyciela'], time() + (86400 * 30), "/");
            }

            header("Location: index_logged.php");
            exit;

        } else {
            $error = "Błędne hasło";
        }
    } else {
        $error = "Nie znaleziono użytkownika";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie nauczyciela</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('szkola.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.85);
            width: 350px;
            margin: 120px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.25);
            text-align: center;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #aaa;
            font-size: 15px;
        }

        button {
            width: 95%;
            padding: 10px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background: #357ac9;
        }

        .error {
            color: red;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-box">
    <span style="font-size:22px; font-weight:bold; padding:6px 14px; border-radius:8px; background: linear-gradient(135deg, #4a90e2, #6fb3ff); color:white; margin-right:25px; margin-left:20px; display:inline-block;">
        Dzienniczek+
    </span>
    <h2>Logowanie nauczyciela</h2>

<form method="POST" autocomplete="on">
    <input type="text" name="login" placeholder="Login" autocomplete="username" required>

    <input type="password" name="haslo" placeholder="Hasło" autocomplete="new-password" required>

    <label style="display:flex; align-items:center; gap:8px; margin-top:5px;">
         <input type="checkbox" name="remember" style="width:auto;">
         Zapamiętaj mnie
    </label>

    <button type="submit">Zaloguj</button>
</form>


    <?php if(isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>

</body>
</html>

