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
    SELECT l.*, p.id_nauczyciela
    FROM lekcje l
    JOIN przedmioty p ON l.id_przedmiotu = p.id_przedmiotu
    WHERE l.id_lekcji = $id
");

if ($check->num_rows == 0) {
    die("❌ Brak uprawnień.");
}

$lekcja = $check->fetch_assoc();

if(isset($_POST['zapisz'])){
    $data = $_POST['data'];
    $temat = trim($_POST['temat']);

    $stmt = $conn->prepare("
        UPDATE lekcje SET data=?, temat=? WHERE id_lekcji=?
    ");
    $stmt->bind_param("ssi", $data, $temat, $id);
    $stmt->execute();

    header("Location: lekcje.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Edycja lekcji</title>

<style>
body {
    font-family:Arial;
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
form {
    display:flex;
    flex-direction:column;
    gap:15px;
}
input {
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
    <h2>Edycja lekcji</h2>

    <form method="POST">
        <label>Data:</label>
        <input type="date" name="data" value="<?= $lekcja['data'] ?>" required>

        <label>Temat:</label>
        <input type="text" name="temat" value="<?= htmlspecialchars($lekcja['temat']) ?>" required>

        <button type="submit" name="zapisz">Zapisz</button>
    </form>

    <a class="back" href="lekcje.php">← Powrót</a>
</div>

</body>
</html>
