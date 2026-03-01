<?php
session_start();
if (!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

// Pobieranie klas do filtra
$klasy = $conn->query("SELECT id_klasy, nazwa FROM klasy ORDER BY nazwa");

// Odczyt filtrów
$filtr_imie = trim($_GET['imie'] ?? '');
$filtr_nazwisko = trim($_GET['nazwisko'] ?? '');
$filtr_klasa = $_GET['klasa'] ?? '';

// Budowanie zapytania
$sql = "
    SELECT u.id_ucznia, u.imie, u.nazwisko, k.nazwa AS klasa
    FROM uczniowie u
    JOIN klasy k ON u.id_klasy = k.id_klasy
    WHERE 1=1
";

$params = [];

if ($filtr_imie !== '') {
    $sql .= " AND u.imie LIKE ? ";
    $params[] = "%$filtr_imie%";
}

if ($filtr_nazwisko !== '') {
    $sql .= " AND u.nazwisko LIKE ? ";
    $params[] = "%$filtr_nazwisko%";
}

if ($filtr_klasa !== '') {
    $sql .= " AND u.id_klasy = ? ";
    $params[] = $filtr_klasa;
}

$sql .= " ORDER BY id_ucznia ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista uczniów</title>

    <style>
        /* ====== MENU ====== */
        .menu {
            background:#f0f0f0;
            padding:10px 20px;
            border-bottom:1px solid #ccc;
        }

        .logo-box {
            font-size:22px;
            font-weight:bold;
            padding:6px 14px;
            border-radius:8px;
            background: linear-gradient(135deg, #4a90e2, #6fb3ff);
            color:white;
            display:inline-block;
        }

        .logout-btn {
            float:right;
            background: linear-gradient(135deg, #ff4b4b, #ff7b7b);
            color:white;
            padding:8px 16px;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
            box-shadow:0 2px 6px rgba(0,0,0,0.2);
            transition:0.2s;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e63a3a, #ff5c5c);
            transform:translateY(-2px);
        }

        /* ====== KAFELKI POD MENU ====== */
        .top-tiles {
            display:flex;
            gap:15px;
            padding:12px 20px;
            background: rgba(255,255,255,0.85);
            border-bottom:1px solid #ccc;
            justify-content:center;
            flex-wrap:wrap;
        }

        .top-tile {
            background:white;
            padding:10px 18px;
            border-radius:10px;
            box-shadow:0 2px 6px rgba(0,0,0,0.15);
            font-size:15px;
            font-weight:bold;
            transition:0.2s;
        }

        .top-tile:hover {
            background:#f1f7ff;
            transform:translateY(-2px);
        }

        .top-tile a {
            color:#4a90e2;
            text-decoration:none;
        }

        /* ====== STRONA ====== */
        body {
            font-family: Arial, sans-serif;
            background: url('szkola.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .overlay {
            background: rgba(255, 255, 255, 0.85);
            width: 80%;
            margin: 40px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input, select {
            padding: 8px;
            margin-right: 10px;
            border-radius: 6px;
            border: 1px solid #aaa;
        }

        button {
            padding: 8px 15px;
            border: none;
            background: #4a90e2;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #357ac9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background: #4a90e2;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f7ff;
        }
    </style>
</head>
<body>

<!-- MENU -->
<div class="menu">
    <span class="logo-box">Dzienniczek+</span>
    <a href="/dziennik_elektroniczny/logout.php" class="logout-btn">🚪 Wyloguj</a>
</div>

<!-- KAFELKI POD MENU -->
<div class="top-tiles">
    <div class="top-tile"><a href="index_logged.php">📋 Strona główna</a></div>
    <div class="top-tile"><a href="uczniowie.php">👨‍🎓 Uczniowie</a></div>
    <div class="top-tile"><a href="oceny.php">📘 Oceny</a></div>
    <div class="top-tile"><a href="obecnosci.php">🕒 Obecności</a></div>
    <div class="top-tile"><a href="lekcje.php">📚 Lekcje</a></div>
    <div class="top-tile"><a href="uwagi.php">✏️ Uwagi/Pochwały</a></div>
</div>

<div class="overlay">
    <h2>Lista uczniów</h2>

    <form method="GET">
        Imię:
        <input type="text" name="imie" value="<?= htmlspecialchars($filtr_imie) ?>">

        Nazwisko:
        <input type="text" name="nazwisko" value="<?= htmlspecialchars($filtr_nazwisko) ?>">

        Klasa:
        <select name="klasa">
            <option value="">-- wszystkie --</option>
            <?php while($row = $klasy->fetch_assoc()): ?>
                <option value="<?= $row['id_klasy'] ?>" <?= ($filtr_klasa == $row['id_klasy']) ? 'selected' : '' ?>>
                    <?= $row['nazwa'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Szukaj</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Klasa</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_ucznia'] ?></td>
                    <td><?= $row['imie'] ?></td>
                    <td><?= $row['nazwisko'] ?></td>
                    <td><?= $row['klasa'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Brak wyników</td></tr>
        <?php endif; ?>
    </table>

</div>

</body>
</html>

