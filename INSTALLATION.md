# Installationsanleitung - XAMPP

Schritt-für-Schritt-Anleitung zur lokalen Installation auf Windows mit XAMPP.

## Voraussetzungen

- Windows 10/11
- Administrator-Rechte
- ~500 MB freier Speicherplatz

## Installation

### 1. XAMPP herunterladen und installieren

1. Besuche https://www.apachefriends.org/
2. Lade die neueste Version herunter (Version 8.0+ empfohlen)
3. Führe das Installationsprogramm aus
4. Installiere nach: `C:\xampp\` (Standardpfad)
5. Wähle Apache, MySQL und PHP aus
6. Beende die Installation

### 2. Projekt vorbereiten

1. Lade das Projekt herunter oder klone es:
   ```bash
   git clone https://github.com/Megatronvs/rheinbeck-feuerwehr-admin.git
   ```

2. Kopiere den `rheinbeck` Ordner nach:
   ```
   C:\xampp\htdocs\rheinbeck\
   ```

3. Stelle sicher, dass die Dateistruktur wie folgt aussieht:
   ```
   C:\xampp\htdocs\
   └── rheinbeck\
       ├── index.php
       ├── config/
       ├── database/
       ├── sql/
       └── ...
   ```

### 3. XAMPP Control Panel starten

1. Öffne das XAMPP Control Panel:
   ```
   C:\xampp\xampp-control.exe
   ```

2. Starte die Services:
   - **Apache**: Klick auf "Start" neben Apache
   - **MySQL**: Klick auf "Start" neben MySQL

3. Beide sollten grün werden und sagen "(Running)"

### 4. Datenbank erstellen

1. Öffne phpMyAdmin im Browser:
   ```
   http://localhost/phpmyadmin/
   ```

2. Links auf **"Neue Datenbank"** klicken

3. Name eingeben: `rheinbeck`

4. Sortierung: `utf8mb4_unicode_ci`

5. Klick auf **"Erstellen"**

### 5. SQL-Datei importieren

1. Klick auf die neue Datenbank `rheinbeck` (links in der Übersicht)

2. Klick auf den Reiter **"Importieren"**

3. Klick auf **"Datei auswählen"**

4. Wähle die Datei:
   ```
   C:\xampp\htdocs\rheinbeck\sql\database.sql
   ```

5. Klick auf **"OK"**

6. Die Tabellen werden automatisch erstellt

### 6. Konfiguration anpassen

1. Öffne die Datei:
   ```
   C:\xampp\htdocs\rheinbeck\config\config.php
   ```

2. Kontrolliere die Einstellungen:

   ```php
   // Datenbank
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // XAMPP: leer!
   define('DB_NAME', 'rheinbeck');
   
   // Base URL
   define('BASE_URL', 'http://localhost/rheinbeck/');
   
   // Discord (optional)
   define('DISCORD_CLIENT_ID', 'your_client_id');
   define('DISCORD_CLIENT_SECRET', 'your_client_secret');
   define('DISCORD_REDIRECT_URI', BASE_URL . 'auth/discord_callback.php');
   ```

3. Speichern und schließen

### 7. Website öffnen

Öffne einen Browser und navigiere zu:
```
http://localhost/rheinbeck/
```

Du solltest jetzt die Startseite sehen.

## Erstes Setup

### Benutzer anlegen

1. Klick auf **"Anmelden"** oben rechts oder gehe zu:
   ```
   http://localhost/rheinbeck/login.php
   ```

2. Es gibt noch keinen Admin-Benutzer. Führe diese SQL aus:

   **In phpMyAdmin:**
   - Gehe zu Datenbank `rheinbeck`
   - Klick auf **"SQL"**
   - Kopiere diese Query:

   ```sql
   INSERT INTO users (username, email, password, full_name, personnel_number, rank_id, department_id, status, created_at)
   VALUES ('admin', 'admin@rheinbeck.de', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', '00001', 1, 1, 'active', NOW());
   ```

   - Klick **"Ausführen"**

3. Standardlogin:
   ```
   Benutzername: admin
   Passwort: password
   ```

4. **WICHTIG**: Änder dein Passwort sofort nach dem Login!

### Admin-Panel öffnen

Nach dem Login:
```
http://localhost/rheinbeck/admin/
```

Dort kannst du:
- Benutzer verwalten
- Rollen und Berechtigungen konfigurieren
- Abteilungen erstellen
- Lehrgänge anlegen
- Fahrzeuge registrieren
- Und vieles mehr...

## Fehlerbehandlung

### "Datenbank-Verbindung fehlgeschlagen"

1. Kontrolliere, dass MySQL läuft (grün im XAMPP Control Panel)
2. Prüfe `config/config.php`:
   - `DB_HOST` sollte `localhost` sein
   - `DB_USER` sollte `root` sein
   - `DB_PASS` sollte leer sein (bei XAMPP Standard)
3. Öffne phpMyAdmin und überprüfe die Datenbank `rheinbeck`

### "404 - Seite nicht gefunden"

1. Kontrolliere, dass Apache läuft (grün im XAMPP Control Panel)
2. Kontrolliere den Projektpfad: `C:\xampp\htdocs\rheinbeck\`
3. Versuche: `http://localhost/rheinbeck/index.php`

### "Permission Denied" beim Upload

1. Rechtsklick auf `C:\xampp\htdocs\rheinbeck\assets\uploads\`
2. **Eigenschaften** → **Sicherheit** → **Bearbeiten**
3. Wähle dich selbst und gib **Vollzugriff** → **OK**

### Fehler im Browser-Konsole (F12)

1. Öffne die Browser-Entwicklertools: **F12**
2. Gehe zum Reiter **"Konsole"**
3. Kontrolliere die Fehlermeldungen
4. Überprüfe die `php_errors.log`:
   ```
   C:\xampp\apache\logs\error.log
   ```

## Datenbank zurücksetzen

Falls etwas schiefgeht:

1. Öffne phpMyAdmin
2. Gehe zur Datenbank `rheinbeck`
3. Klick auf **"Operations"**
4. Klick auf **"Datenbank löschen"**
5. Bestätige mit **"Ja"**
6. Erstelle die Datenbank neu
7. Importiere die SQL-Datei erneut (siehe Schritt 5)

## Externe Erreichbarkeit

Um die Website später auch von anderen Computern aus zu erreichen:

1. Kontrolliere deine interne IP:
   ```
   Windows: ipconfig (suche nach "IPv4-Adresse", z.B. 192.168.x.x)
   ```

2. Andere Nutzer im Netzwerk können dann zugreifen:
   ```
   http://192.168.x.x/rheinbeck/
   ```

3. Für externe Erreichbarkeit: Port-Forwarding einrichten oder ngrok verwenden

## Tipps

- **Regelmäßige Backups**: Exportiere regelmäßig die Datenbank in phpMyAdmin
- **Logs überprüfen**: Fehlerlogs in `C:\xampp\apache\logs\`
- **Performance**: Mit vielen Daten kann es langsamer werden
- **Passwörter**: Ändere die Admin-Passwörter regelmäßig

## Support

Bei Fragen: Konsultiere die `README.md` oder überprüfe die Datenbank-Struktur in phpMyAdmin.

Viel Erfolg! 🚀
