<?php
if (!isset($_SESSION)) session_start();
?>

<div class="menu">
    <span class="logo-box">Dzienniczek+</span>
    <a href="/dziennik_elektroniczny/logout.php" class="logout-btn">🚪 Wyloguj</a>
</div>

<?php if (!isset($hideTopTiles)): ?>
<div class="top-tiles">
    <div class="top-tile"><a href="index_logged.php">📋 Strona główna</a></div>
    <div class="top-tile"><a href="uczniowie.php">👨‍🎓 Uczniowie</a></div>
    <div class="top-tile"><a href="oceny.php">📘 Oceny</a></div>
    <div class="top-tile"><a href="obecnosci.php">🕒 Obecności</a></div>
    <div class="top-tile"><a href="lekcje.php">📚 Lekcje</a></div>
    <div class="top-tile"><a href="uwagi.php">✏️ Uwagi/Pochwały</a></div>
</div>
<?php endif; ?>
