# docker-sandboxes-claude

Beispielprojekt: Claude Code läuft isoliert in einer [Docker Sandbox](https://docs.docker.com/ai/sandboxes/) und steuert von dort einen Docker-Compose-Stack mit nginx, PHP-FPM und MariaDB.

Inoffizielles Beispiel, nicht von Docker oder Anthropic.

## Idee

- Claude Code läuft in einer microVM, nicht auf dem Host. Vom Host sieht es nur diesen Projektordner.
- Die Sandbox hat einen eigenen Docker-Daemon. `docker compose` läuft darin, die Container tauchen im `docker ps` des Hosts nicht auf.
- PHP ist nicht in der Sandbox installiert, sondern nur im Compose-Service `php`. Claude führt PHP-Befehle über `docker compose exec php ...` aus (siehe `CLAUDE.md`).

## Voraussetzungen

- macOS 14 (Sonoma) oder neuer auf Apple Silicon
- `sbx`-CLI und ein Docker-Account
- Claude-Abo oder Anthropic-API-Key

Installation von `sbx` per Homebrew:

    brew trust docker/tap
    brew install docker/tap/sbx
    sbx login

Docker Desktop wird nicht benötigt.

## Start

Mit der Konfigurationsdatei `sbxenv.yaml` (experimentell):

    sbx env plan
    sbx env run

Oder klassisch:

    sbx run --publish 8080:8080 --name docker-sandboxes-claude claude

Beim ersten Start in Claude Code `/login` ausführen. Danach zum Beispiel:

    Starte den Stack mit docker compose und führe src/hello.php im php-Container aus.

Im Browser auf dem Host: http://localhost:8080

## Datenbank vom Host aus

MariaDB ist über zwei Stufen erreichbar: Compose veröffentlicht Port 3306 in die Sandbox, die `sbxenv.yaml` reicht ihn an den Host weiter.

- Host: `127.0.0.1`, Port: `3306`
- Datenbank: `app`, Benutzer: `app`, Passwort: `app` (Root: `root`)

Die Zugangsdaten sind Beispielwerte nur für lokale Tests. Ist Port 3306 auf dem Host belegt, in der `sbxenv.yaml` bei `host:` einen anderen Port eintragen.

## Aufbau

- `sbxenv.yaml`: Sandbox-Konfiguration (Name, Agent, Workspace, Ports)
- `docker-compose.yml`: nginx (Port 8080), PHP-FPM und MariaDB (Port 3306)
- `docker/nginx/default.conf`: nginx-Konfiguration
- `src/`: PHP-Code
- `CLAUDE.md`: Hinweise für Claude Code

## Nützliche Befehle

    sbx ls                                              # Sandboxes anzeigen
    sbx exec docker-sandboxes-claude docker compose ps  # Befehl in der Sandbox
    sbx stop docker-sandboxes-claude                    # anhalten, Zustand bleibt
    sbx rm docker-sandboxes-claude                      # löschen, inklusive Container und Images

## Hinweise

- Claude Code läuft in der Sandbox mit `--dangerously-skip-permissions`, fragt also nicht nach. Der Projektordner ist mit dem Host geteilt: Änderungen dort sind echt, also vorher committen.
- `sbx rm` löscht alles in der Sandbox, auch Datenbank-Volumes.
- Geänderte Ports in der `sbxenv.yaml` greifen erst, nachdem die Sandbox neu erstellt wurde.
