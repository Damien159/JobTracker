# JobTracker

Laravel/Livewire Webanwendung zur zentralen Verwaltung, Organisation und statistischen Auswertung von Bewerbungen. Mit übersichtlichem Dashboard, Streak-System und automatischen Erinnerungen für einen strukturierten und effizienten Bewerbungsprozess.


## Über das Projekt

JobTracker ist ein persönliches Lern und Portfolio Projekt, das den gesamten Bewerbungsprozess an einem Ort abbildet: von der ersten Bewerbung über Ansprechpartner und Status, Verlauf bis hin zu Bewerbungsunterlagen (Lebenslauf, Anschreiben).

## Tech-Stack

- **Backend:** PHP, Laravel, Livewire
- **Frontend:** Blade, Tailwind CSS, Flux UI
- **Datenbank:** MySQL
- **Auth:** Laravel-Livewire-Starterkit (E-Mail/Passwort)

## Aktueller Stand

**Datenmodell (fertig):**
- `companies` – Firmen
- `contacts` – Ansprechpartner (je Firma)
- `applications` – Bewerbungen (Position, Datum, Gehaltswunsch, Bewerbungsart, Quelle, u. a.)
- `application_status_histories` – Verlauf der Bewerbungsstatus (aktueller Status ergibt sich aus dem neuesten Eintrag)
- `application_documents` – geplant für Lebenslauf/Anschreiben als PDF/Word (Modell/Migration ausstehend)

**Features:**
- [x] Login/Register (Standard des Starterkits)
- [x] Eigenes Design für Login/Register (Split-Layout, Markenfarben, eigenes Logo)
- [x] Bewerbungen anlegen (Modal auf der Übersichtsseite, mit Autocomplete/Find-or-Create für Firma und Kontakt)
- [x] Übersichtsliste der Bewerbungen
- [x] Status einer Bewerbung ändern (Inline-Dropdown in der Liste)
- [x] Detailansicht einer einzelnen Bewerbung (Status-Verlauf, Notizen, Ansprechpartner)
- [x] Bewerbung bearbeiten (Modal mit vorausgefüllten Werten)
- [x] Bewerbung löschen (mit Bestätigungsdialog)
- [x] Dashboard mit Kennzahlen (Gesamt, Interviews, Zusagen, Interview-Rate) und Charts (Status-Verteilung, Bewerbungen pro Monat via Chart.js)
- [ ] Gamification (Streak-System)
- [ ] Automatische E-Mail-Erinnerungen (5 Tage ohne Rückmeldung)
- [ ] Automatische E-Mail-Statuserkennung
- [ ] Dokumenten-Upload (Lebenslauf/Anschreiben)

## Design

Eigenes Farbsystem statt Standard-Palette:
- `#333333` – primäre dunkle Fläche/Text
- `#474747` – sekundäre Fläche/Text
- `#FD105E` – Akzentfarbe (Buttons, Links, Fokus-Zustände)

## Lokale Einrichtung

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

`.env` mit lokalen DB-Zugangsdaten anpassen, dann:

```bash
php artisan migrate
npm run dev
php artisan serve
```