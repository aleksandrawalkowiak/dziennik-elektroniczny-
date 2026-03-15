**Dziennik Elektroniczny**

Projekt Dziennik Elektroniczny to aplikacja webowa umożliwiająca prowadzenie elektronicznego dziennika dla uczniów i nauczycieli. Aplikacja pozwala na zarządzanie ocenami, obecnościami oraz notatkami w prostym i intuicyjnym interfejsie.

Technologie
Projekt został stworzony z użyciem:
- HTML5– struktura stron
- CSS3 – stylizacja i responsywność
- JavaScript– interaktywność po stronie klienta
- PHP – logika po stronie serwera
- MySQL – baza danych do przechowywania informacji o uczniach, ocenach i obecnościach

Funkcje
- Logowanie dla nauczycieli i uczniów
- Dodawanie i edycja ocen
- Rejestrowanie obecności
- Przeglądanie historii ocen i obecności
- Responsywny interfejs dla komputerów i urządzeń mobilnych

Wymagania
Do uruchomienia aplikacji potrzebne są:
• Program XAMPP (wersja z PHP 8.x)
• Przeglądarka internetowa 
• Pliki projektu: folder z aplikacją (folder o nazwie
dziennik_elektroniczny)
• Plik bazy danych: dziennik_elektroniczny.sql

Instalacja i uruchomienie
1. Zainstaluj XAMPP z domyślnymi ustawieniami.
2. Uruchom XAMPP Control Panel.
3. Włącz moduły:
o Apache (Start)
o MySQL (Start)
Po uruchomieniu oba moduły powinny świecić się na zielono.
4. Skopiowanie aplikacji do katalogu htdocs
5. Skopiuj cały folder projektu dziennik_elektroniczny
6. Wklej go do katalogu:
C:\xampp\htdocs\
Efekt końcowy:
C:\xampp\htdocs\dziennik_elektroniczny
7. Import bazy danych
8. Otwórz przeglądarkę i wejdź na: http://localhost/phpmyadmin
9. Kliknij Nowa → utwórz bazę danych o nazwie:
dziennik_elektroniczny
10. Wejdź do tej bazy → zakładka Import.
11. Wybierz plik:
dziennik_elektroniczny.sql
12. Kliknij Importuj.
Po imporcie baza powinna zawierać tabele oraz przykładowe dane.
13. Konfiguracja połączenia z bazą danych
W pliku:
config.php
znajduje się konfiguracja:
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
14. Uruchomienie aplikacji
W przeglądarce wpisz:
http://localhost/dziennik_elektroniczny/
Aplikacja powinna się uruchomić.
15. Logowanie do aplikacji
Dane logowania testowego nauczyciela:
• Login: akowalska
• Hasło: haslo1

Struktura projektu
Najważniejsze pliki:
• config.php – testowanie połączenia
• dziennik_elektroniczny.sql – baza danych
• functions.php - funkcje
• index.php – strona logowania
• index_logged.php – panel główny
• logout.php – strona po wylogowaniu
• menu.php – menu górne
• uczniowie.php – lista uczniów
• obecnosci – lista obecności
• obecnosci_edytuj.php – edytowanie obecności
• obecnosci_usun.php – usuwanie obecności
• oceny.php – lista ocen
• oceny_usun.php – usuwanie ocen
• oceny_edytuj.php – edytowanie ocen
• lekcje.php – lista lekcji danego nauczyciela
• lekcje_edytuj.php – edytowanie lekcji
• lekcje_usun.php – usuwanie lekcji
• uwagi.php – lista uwag/pochwał
• uwagi_usun.php – usuwanie uwag/pochwał
• uwagi_edytuj.php – edytowanie uwag/pochwał
• szkola.png – obraz szkoły na stronie głównej





