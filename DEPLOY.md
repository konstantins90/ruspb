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

# 5. SCSS bauen & Assets kompilieren
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

## 3. Nur Cache + CSS neu (ohne Git/Composer)

Wenn nur Konfiguration oder Twig/CSS geändert wurde:

```bash
php bin/console cache:clear --env=prod
php bin/console sass:build
php bin/console asset-map:compile
```
