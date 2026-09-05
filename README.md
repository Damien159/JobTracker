# JobTracker

Eine Laravel/Livewire-Webanwendung zur Verwaltung und statistischen Auswertung eigener Bewerbungen – mit Dashboard, Streak-System und automatischen Erinnerungen.

## Über das Projekt

JobTracker ist ein persönliches Lern- und Portfolio-Projekt, das den gesamten Bewerbungsprozess an einem Ort abbildet: von der ersten Bewerbung über Ansprechpartner und Status-Verlauf bis hin zu Bewerbungsunterlagen (Lebenslauf, Anschreiben).

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
- [x] Eigenes Design für Login/Register (Split-Layout, Markenfarben)
- [ ] Bewerbungen anlegen
- [ ] Übersichtsliste der Bewerbungen
- [ ] Dashboard/Statistiken
- [ ] Streak-System
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