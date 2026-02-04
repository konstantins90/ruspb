# Deploy auf Live-Server

## 1. Cache leeren & CSS/Assets neu erzeugen

**Lokal oder auf dem Server** (je nachdem, wo du buildest):

```bash
# Cache leeren (wichtig nach Code-Änderungen)
php bin/console cache:clear --env=prod

# SCSS → CSS bauen (app.scss → kompiliertes CSS)
php bin/console sass:build

# Asset Mapper: alle gemappten Assets (JS, CSS) für Produktion kompilieren
php bin/console asset-map:compile

# Optional: Bundle-Assets ins public-Verzeichnis
php bin/console assets:install --env=prod
```

**Kurzfassung – alles in einem:**
```bash
php bin/console cache:clear --env=prod && \
php bin/console sass:build && \
php bin/console asset-map:compile
```

---

## 2. Typischer Live-Deploy-Ablauf

### Auf dem Live-Server (per SSH)

```bash
# 1. Ins Projektverzeichnis
cd /pfad/zu/ruspb

# 2. Code aktualisieren (z. B. Git Pull)
git pull origin main

# 3. PHP-Abhängigkeiten (ohne Dev-Pakete)
composer install --no-dev --optimize-autoloader

# 4. Cache leeren
php bin/console cache:clear --env=prod

# 5. Importmap-Vendor-Assets (z. B. @orchidjs/sifter für Autocomplete)
php bin/console importmap:install

# 6. SCSS bauen & Assets kompilieren
php bin/console sass:build
php bin/console asset-map:compile

# 7. Migrationen (falls DB-Schema geändert)
php bin/console doctrine:migrations:migrate --no-interaction

# 8. Optional: Slugs für alte Posts
# php bin/console app:posts:backfill-slugs
```

### Wichtig auf dem Server

- **Umgebung:** `APP_ENV=prod` und `APP_DEBUG=0` (z. B. in `.env.local`).
- **Uploads:** Verzeichnis `public/uploads/posts/` muss existieren und schreibbar sein.
- **Rechte:** `var/cache` und `var/log` müssen für den Webserver schreibbar sein.

---

## APP_ENV=prod funktioniert nicht (Dev funktioniert)

Wenn die Seite mit `APP_ENV=dev` läuft, mit `APP_ENV=prod` aber nicht (weißer Bildschirm, 500, oder kaputte Seite):

### 1. Prod-Cache leeren und Rechte setzen

```bash
php bin/console cache:clear --env=prod
# Rechte: Webserver-User muss var/cache und var/log schreiben können
chmod -R 775 var/cache var/log
# Falls nötig (User des Webservers, z. B. www-data):
# chown -R www-data:www-data var/cache var/log
```

### 2. Fehler sichtbar machen (nur kurz zum Debuggen)

In `.env.local` auf dem Server **temporär**:

```bash
APP_DEBUG=1
```

Seite aufrufen – oft erscheint die genaue Fehlermeldung. Danach wieder `APP_DEBUG=0` setzen.

### 3. Log prüfen

```bash
tail -100 var/log/prod.log
# oder
tail -100 var/log/dev.log
```

Oft steht dort die Exception (z. B. fehlende Extension, falsche DB-URL, fehlende Datei).

### 4. Häufige Prod-spezifische Ursachen

| Problem | Lösung |
|--------|--------|
| Weißer Bildschirm, kein Eintrag im Log | Cache leeren: `php bin/console cache:clear --env=prod`; Rechte auf `var/` prüfen. |
| 500 nach Umstellung auf prod | `var/cache/prod` löschen, dann `cache:clear --env=prod`. |
| Assets (CSS/JS) fehlen oder 404 | `importmap:install`, danach `asset-map:compile` (siehe Abschnitt 1). |
| Datenbankfehler in prod | In `.env.local` prüfen: `DATABASE_URL` für den Live-Server (Host, User, Passwort). |
| Composer-Autoload in prod | `composer install --no-dev --optimize-autoloader` ausführen. |

### 5. Umgebung auf dem Server setzen

`APP_ENV` und `APP_DEBUG` sollten auf dem Live-Server in `.env.local` (oder echten Umgebungsvariablen) stehen, nicht nur in `.env`:

```bash
# .env.local auf dem Server (nicht committen)
APP_ENV=prod
APP_DEBUG=0
```

---

## 3. Nur Cache + CSS neu (ohne Git/Composer)

Wenn nur Konfiguration oder Twig/CSS geändert wurde:

```bash
php bin/console cache:clear --env=prod
php bin/console sass:build
php bin/console asset-map:compile
```
