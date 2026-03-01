<?php
session_start();
if(!isset($_SESSION['id_nauczyciela'])) {
    header("Location: index.php");
    exit;
}

require 'config.php';

$id_nauczyciela = $_SESSION['id_nauczyciela'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ============================================
   DODAWANIE UWAGI / POCHWAŁY
============================================ */
if(isset($_POST['dodaj'])){
    $id_ucznia = intval($_POST['id_ucznia']);
    $typ = $_POST['typ']; // uwaga / pochwala
    $tresc = trim($_POST['tresc']);

    if($tresc !== ''){
        $stmt = $conn->prepare("
            INSERT INTO uwagi (id_ucznia, id_nauczyciela, typ, tresc, data)
            VALUES (?,?,?,?,NOW())
        ");
        $stmt->bind_param("iiss", $id_ucznia, $id_nauczyciela, $typ, $tresc);
        $stmt->execute();
    }
}

/* ============================================
   POBIERANIE UCZNIÓW
============================================ */
$uczniowie = $conn->query("
    SELECT id_ucznia, imie, nazwisko 
    FROM uczniowie 
    ORDER BY nazwisko, imie
");

/* ============================================
   LISTA UWAG — TYLKO TEGO NAUCZYCIELA
============================================ */
$uwagi = $conn->query("
    SELECT u.id_uwagi, u.typ, u.tresc, u.data,
           s.imie, s.nazwisko, k.nazwa AS klasa
    FROM uwagi u
    JOIN uczniowie s ON u.id_ucznia = s.id_ucznia
    JOIN klasy k ON s.id_klasy = k.id_klasy
    WHERE u.id_nauczyciela = $id_nauczyciela
    ORDER BY u.id_uwagi DESC
");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Uwagi i pochwały</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background:url('szkola.jpg') no-repeat center center fixed;
            background-size:cover;
            margin:0;
        }

        /* MENU */
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
            background:linear-gradient(135deg,#4a90e2,#6fb3ff);
            color:white;
        }
        .logout-btn {
            float:right;
            background:linear-gradient(135deg,#ff4b4b,#ff7b7b);
            color:white;
            padding:8px 16px;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
        }

        /* KAFELKI */
        .top-tiles {
            display:flex;
            gap:15px;
            padding:12px 20px;
            background:rgba(255,255,255,0.85);
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

        /* STRONA */
        .overlay {
            background:rgba(255,255,255,0.85);
            width:80%;
            margin:40px auto;
            padding:25px;
            border-radius:12px;
            box-shadow:0 4px 15px rgba(0,0,0,0.2);
        }

        /* FORMULARZ */
        form {
            background:#f7f9fc;
            padding:20px;
            border-radius:10px;
            margin-bottom:30px;
            text-align:center;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }
        form select, form textarea {
            width:80%;
            max-width:600px;
            padding:10px;
            margin:10px auto;
            border-radius:6px;
            border:1px solid #aaa;
            display:block;
        }
        form textarea {
            resize:vertical;
            min-height:80px;
        }
        form button {
            padding:10px 20px;
            background:#4a90e2;
            color:white;
            border:none;
            border-radius:6px;
            cursor:pointer;
        }

        /* TABELA */
        table {
            width:100%;
            border-collapse:collapse;
            background:white;
            border-radius:8px;
            overflow:hidden;
            table-layout:fixed;
        }
        th {
            background:#4a90e2;
            color:white;
            padding:12px;
            text-align:center;
            font-weight:bold;
        }
        td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:center;
            vertical-align:middle;
            word-wrap:break-word;
        }

        /* ID */
        th.col-id, td.col-id {
            text-align:left !important;
            width:60px;
            padding-left:12px;
        }

        /* Akcje */
        th.actions, td.actions {
            width:150px;
            text-align:center;
        }

        /* Linki Edytuj / Usuń */
        .action-link {
            color:#4a90e2;
            text-decoration:underline;
            font-weight:bold;
            margin:0 6px;
        }
        .action-link:hover {
            color:#357ac9;
        }
    </style>
</head>
<body>

<!-- MENU -->
<div class="menu">
    <span class="logo-box">Dzienniczek+</span>
    <a class="logout-btn" href="logout.php">Wyloguj</a>
</div>

<!-- KAFELKI -->
<div class="top-tiles">
    <div class="top-tile"><a href="index_logged.php">📋 Strona główna</a></div>
    <div class="top-tile"><a href="uczniowie.php">👨‍🎓 Uczniowie</a></div>
    <div class="top-tile"><a href="oceny.php">📘 Oceny</a></div>
    <div class="top-tile"><a href="obecnosci.php">🕒 Obecności</a></div>
    <div class="top-tile"><a href="lekcje.php">📚 Lekcje</a></div>
    <div class="top-tile"><a href="uwagi.php">✏️ Uwagi/Pochwały</a></div>
</div>

<div class="overlay">

    <h2>Dodaj uwagę / pochwałę</h2>

    <form method="POST">
        <select name="id_ucznia" required>
            <option value="">-- wybierz ucznia --</option>
            <?php while($s = $uczniowie->fetch_assoc()): ?>
                <option value="<?= $s['id_ucznia'] ?>"><?= $s['imie']." ".$s['nazwisko'] ?></option>
            <?php endwhile; ?>
        </select>

        <select name="typ" required>
            <option value="">-- wybierz typ --</option>
            <option value="uwaga">Uwaga</option>
            <option value="pochwala">Pochwała</option>
        </select>

        <textarea name="tresc" placeholder="Treść uwagi lub pochwały..." required></textarea>

        <button type="submit" name="dodaj">Zapisz</button>
    </form>

    <h2>Lista uwag i pochwał</h2>

    <table>
        <tr>
            <th class="col-id">ID</th>
            <th>Uczeń</th>
            <th>Klasa</th>
            <th>Typ</th>
            <th>Treść</th>
            <th>Data</th>
            <th class="actions">Akcje</th>
        </tr>

        <?php while($u = $uwagi->fetch_assoc()): ?>
            <tr>
                <td class="col-id"><?= $u['id_uwagi'] ?></td>
                <td><?= $u['imie']." ".$u['nazwisko'] ?></td>
                <td><?= $u['klasa'] ?></td>

                <?php
                    $typ = strtolower($u['typ']);
                    $kolor = ($typ === "uwaga") ? "#ff0000" : "#00cc66";
                    $tekst = ($typ === "uwaga") ? "Uwaga" : "Pochwała";
                ?>

                <td style="color: <?= $kolor ?>; font-weight:bold;">
                    <?= $tekst ?>
                </td>

                <td><?= $u['tresc'] ?></td>
                <td><?= $u['data'] ?></td>

                <td class="actions">
                    <a class="action-link" href="uwagi_edytuj.php?id=<?= $u['id_uwagi'] ?>">Edytuj</a> |
                    <a class="action-link" href="uwagi_usun.php?id=<?= $u['id_uwagi'] ?>"
                       onclick="return confirm('Usunąć wpis?');">Usuń</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>

