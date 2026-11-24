# docker-sandboxes-claude

Beispielprojekt: nginx + PHP-FPM + MariaDB per Docker Compose, Code liegt in `src/`.

## Umgebung

- Du läufst in einer Docker Sandbox mit eigenem Docker-Daemon.
- PHP ist NICHT direkt installiert. PHP läuft nur im Compose-Service `php`.

## Befehle

- Stack starten: `docker compose up -d`
- Status: `docker compose ps`
- PHP-Befehl ausführen: `docker compose exec php php <script oder -r '...'>`
- Logs: `docker compose logs nginx php`
- Website testen: `curl -s http://localhost:8080/`
- Datenbank-Konsole: `docker compose exec db mariadb -uapp -papp app`

Führe PHP-Befehle immer über `docker compose exec php` aus.

## Datenbank

- MariaDB läuft im Compose-Service `db` (Host `db`, Port 3306).
- Datenbank `app`, Benutzer `app`, Passwort `app` (Root-Passwort `root`). Nur Testdaten.
- Das PHP-Image enthält noch keine MySQL-Extension (`pdo_mysql`).
