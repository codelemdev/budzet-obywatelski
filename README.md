# "Budżet Obywatelski" - dokumentacja

Aplikacja "Budżet Obywatelski" to platforma internetowa umożliwiająca mieszkańcom zgłaszanie pomysłów na projekty obywatelskie, przeglądanie ich, dyskutowanie oraz głosowanie. Projekt został zrealizowany jako aplikacja webowa przy użyciu frameworka Laravel oraz biblioteki Livewire.

## Screenshoty:

* Ekran główny:

![](/screenshots/ekran-glowny.png)

* Rejestracja:

![](/screenshots/rejestracja.png)

* Zmiana statusu (admin) / dodawanie komentarza:

![](/screenshots/zmiania-statusu.png)

* Wersja mobilna:

![](/screenshots/wersja-mobilna.png)

---

## Instrukcja uruchomienia

1. **Pobranie repozytorium:**
   
   ```bash
   git clone https://github.com/codelemdev/budzet-obywatelski.git
   cd budzet-obywatelski
   ```
   
2. **Kontenery:**
   
   ```bash
   docker-compose up -d
   ```
   
3. **PHP/Laravel + DB:**
   
   ```bash
   docker exec -it bo_app composer install
   docker exec -it bo_app php artisan migrate --seed
   ```
   
4. **Front-end:**
   
   ```bash
   docker exec -it bo_node npm run build
   ```

Aplikacja dostępna pod adresem: [http://127.0.0.1:8000](http://127.0.0.1:8000)

**Dostępowe dane testowe:**
* admin: admin@budzet-obywatelski.pl
* user: jan.kowalski@test.pl
* hasło dla wszystkich: password

---

## Wykorzystane Technologie i Narzędzia

### Back-end
*   **Laravel**: Aktualna wersja frameworka PHP, zapewniająca nowoczesne funkcje, bezpieczeństwo i wydajność.
*   **Laravel Breeze**: Lekki i prosty system uwierzytelniania. Zapewnia gotowe widoki i kontrolery do logowania, rejestracji, resetowania hasła i weryfikacji email.
*   **Laravel Livewire 3**: Kluczowa technologia w projekcie. Pozwala na tworzenie dynamicznych interfejsów (jak w React/Vue) przy użyciu samego PHP. Komponenty Livewire są elementami interaktywnymi na stronie.
*   **Eloquent Sluggable**: Automatycznie generuje unikalne, czytelne dla człowieka fragmenty URL (slugi) z tytułów pomysłów (np. "moj-pomysl" zamiast ID).

### Frontend
*   **Tailwind CSS**: Framework CSS. Cały styl aplikacji oparty jest na klasach utility (np. `bg-blue-500`, `p-4`), co przyspiesza development i zapewnia spójność. Wykorzystano również wtyczkę `@tailwindcss/line-clamp` do ucinania długich tekstów.
*   **Alpine.js**: Minimalistyczny framework JS. Używany np. do obsługi prostych zdarzeń w przeglądarce, bez wysyłania zapytań do serwera.
*   **Blade**: Silnik szablonów, umożliwia dziedziczenie layoutów (`layouts/app.blade.php`), wstrzykiwanie komponentów i wyświetlanie zmiennych PHP.

---

## Architektura MVC i rozwiązania techniczne

Aplikacja ściśle realizuje wzorzec **MVC (Model-View-Controller)**, dostosowany do specyfiki Livewire.

### 1. Model (baza danych i logika danych)
Modele znajdują się w katalogu `app/Models`. Reprezentują tabele w bazie danych i relacje między nimi.

*   **User (`app/Models/User.php`)**:
    *   Reprezentuje użytkownika systemu.
    *   **Metoda `isAdmin()`**: Kluczowa dla bezpieczeństwa. Sprawdza, czy użytkownik ma prawo do panelu administratora (bazując na polu `is_admin` w bazie).
    *   Relacje: `ideas()` (zgłoszone pomysły), `votes()` (oddane głosy), `comments()` (napisane komentarze).
*   **Idea (`app/Models/Idea.php`)**:
    *   Centralny model aplikacji.
    *   Wykorzystuje `Sluggable` do tworzenia przyjaznych linków.
    *   Metoda `getStatusClasses()`: Zwraca klasy CSS (kolory) dla badge'a statusu, oddzielając logikę prezentacji od widoku.
    *   Metody `vote(User $user)` i `removeVote(User $user)`: Enkapsulują logikę dodawania i usuwania głosów.
    *   Metoda `isVotedByUser(?User $user)`: Sprawdza, czy dany użytkownik już głosował.
*   **Status (`app/Models/Status.php`)**:
    *   Słownik statusów (np. "Nowy", "W realizacji").
    *   Metoda `getCount()`: Pobiera liczbę pomysłów w każdym statusie (używane w sidebarze).
*   **Comment (`app/Models/Comment.php`)**: Reprezentuje komentarz pod pomysłem.
*   **Vote (`app/Models/Vote.php`)**: Tabela łącząca (pivot) użytkowników z pomysłami (relacja Many-to-Many).
*   **Category (`app/Models/Category.php`)**: Słownik kategorii pomysłów.

### 2. View (Widoki - warstwa prezentacji)
Widoki znajdują się w `resources/views`.
*   **Layouts (`layouts/app.blade.php`)**: Główny szablon z nagłówkiem i stopką.
*   **Livewire Views (`resources/views/livewire`)**: Każdy komponent logiczny ma swój odpowiednik w widoku (np. `ideas-index.blade.php`). To tutaj zdefiniowany jest HTML i klasy Tailwind.
*   **Blade Components**: Reużywalne elementy UI (np. przyciski, inputy) w `resources/views/components`.

### 3. Controller (Logika aplikacji)
W tej aplikacji rolę kontrolerów pełnią tradycyjne kontrolery Laravel oraz **Komponenty Livewire**.

#### Tradycyjne Kontrolery (`app/Http/Controllers`)
Obsługują routing początkowy (wejście na stronę).
*   `IdeaController`:
    *   `index()`: Wyświetla stronę główną z listą pomysłów.
    *   `show(Idea $idea)`: Wyświetla stronę pojedynczego pomysłu.

#### Komponenty Livewire (`app/Livewire`)
Przejmują interaktywną logikę po załadowaniu strony (AJAX bez pisania JS).

1.  **`IdeasIndex`**: (Lista pomysłów)
    *   Kontroler dla strony głównej.
    *   Przechowuje stan filtrów (`$status`, `$category`, `$filter`, `$search`).
    *   Nasłuchuje zmian w filtrach i dynamicznie odświeża listę pomysłów.
    *   Obsługuje paginację.

2.  **`IdeaIndex`**: (Pojedynczy kafelek na liście)
    *   Komponent potomny dla `IdeasIndex`.
    *   Każdy pomysł na liście to oddzielna instancja tego komponentu.
    *   Obsługuje głosowanie (`vote()`) bezpośrednio z listy, bez przeładowania strony.

3.  **`StatusFilters`**: (Tabsbar)
    *   Wyświetla listę statusów z licznikami.
    *   Po kliknięciu w status wysyła zdarzenie (`dispatch`), które jest odbierane przez `IdeasIndex` w celu przefiltrowania listy.

4.  **`IdeaShow`**: (Widok szczegółowy)
    *   Obsługuje pełny widok pomysłu.
    *   Zawiera logikę głosowania, zmiany statusu (dla admina) oraz usuwania pomysłu.
    *   Liczy głosy i komentarze w czasie rzeczywistym.

5.  **`IdeaComments`**: (Sekcja komentarzy)
    *   Zarządza listą komentarzy pod pomysłem.
    *   Obsługuje formularz dodawania komentarza (`postComment`).
    *   Obsługuje usuwanie komentarzy (`deleteComment`).

6.  **`CreateIdea`**: (Formularz dodawania)
    *   Dedykowany komponent do tworzenia nowych zgłoszeń.
    *   Posiada własną walidację (rules) i logikę zapisu do bazy.

---

## Szczegółowy Opis Funkcjonalności wg Sposobu Dostępu

### 1. Użytkownik Niezalogowany (Gość)
Gość ma dostęp tylko do odczytu (z wyjątkiem prób akcji, które kończą się przekierowaniem).
*   **Przeglądanie listy pomysłów**:
    *   Widzi wszystkie pomysły.
    *   Może korzystać z wyszukiwarki.
    *   Może sortować (np. "Najlepsze") i filtrować (Kategorie, Statusy).
*   **Podgląd szczegółów**: Może wejść w każdy pomysł i przeczytać opis oraz komentarze.
*   **Próba interakcji**: Kliknięcie "Głosuj" lub próba dodania komentarza przekierowuje do strony logowania/rejestracji.

### 2. Użytkownik Zalogowany
Posiada wszystkie uprawnienia Gościa oraz dodatkowo:
*   **Zgłaszanie pomysłów**:
    *   Dostęp do formularza "Dodaj pomysł".
    *   Wymagane podanie tytułu, kategorii i opisu.
*   **Głosowanie**:
    *   Może oddać głos na dowolny pomysł (jeden głos na pomysł).
    *   Przycisk zmienia kolor na niebieski po zagłosowaniu.
    *   Może wycofać swój głos.
*   **Komentowanie**:
    *   Może dodawać komentarze pod pomysłami.
    *   Walidacja minimalnej długości komentarza.
*   **Filtrowanie "Moje pomysły"**: Dodatkowy filtr pozwalający zobaczyć tylko zgłoszone przez siebie projekty.

### 3. Administrator
Użytkownik ze statusem Admin (weryfikacja przez `User::isAdmin()` - flaga w bazie danych). Posiada pełną kontrolę nad systemem.
*   **Zmiana Statusu Pomysłu**:
    *   W widoku szczegółowym widzi dodatkowe menu (radio buttons) ze statusami.
    *   Wybranie statusu (np. "Zrealizowane") natychmiast aktualizuje pomysł w bazie.
    *   System automatycznie dodaje komentarz specjalny: *"Status został zaktualizowany"*, aby powiadomić użytkowników.
*   **Usuwanie Pomysłów**:
    *   Dostępna opcja "Usuń pomysł" (również w widoku szczegółowym).
    *   Usunięcie jest kaskadowe (usuwa powiązane głosy i komentarze).
*   **Usuwanie Komentarzy**:
    *   Obok każdego komentarza widzi opcję "Usuń" (np. do moderacji spamu).
*   **Dostęp do Panelu**: W obecnej wersji funkcje administracyjne są wbudowane w widoki publiczne, dostępne warunkowo.

---

## Dodatkowo
*   **Wyrażenia regularne**: Wykorzystywane w mechanizmach walidacji Laravel oraz routingu.
*   **Paginacja**: Lista pomysłów jest stronicowana (po 10 na stronę), co optymalizuje działanie przy dużej ilości danych.
*   **Wyszukiwarka**: Zaimplementowana i w pełni funkcjonalna, typu livesearch.
*   **Wzorzec MVC**: Kod podzielony logicznie na modele (baza), widoki (Blade) i kontrolery (w tym komponenty Livewire).

