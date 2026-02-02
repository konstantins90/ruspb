# Checkliste vor Live-Gang

## Erledigt im Code
- **Access Control:** Route `app_post_show_slug` war nicht öffentlich – ist ergänzt (SEO-URLs sind jetzt ohne Login erreichbar).

---

## Vor dem Go-Live prüfen

### 1. Umgebung & Geheimnisse
- [ ] **Produktion:** Auf dem Server `APP_ENV=prod` und `APP_DEBUG=0` setzen (z. B. in `.env.local` oder echten Umgebungsvariablen).
- [ ] **APP_SECRET:** Für Prod einen eigenen, langen Secret nutzen (nur in `.env.local` oder Umgebungsvariable auf dem Server, nicht in `.env` committen).
- [ ] **.env:** Keine echten Prod-Passwörter/Keys in der committeten `.env`; Datenbank-URL, Telegram etc. nur in `.env.local` / Umgebungsvariablen auf dem Server.

### 2. Datenbank & Assets
- [ ] **Migrationen** auf dem Prod-System ausführen: `php bin/console doctrine:migrations:migrate --no-interaction`.
- [ ] **Slugs für bestehende Posts:** Einmal `php bin/console app:posts:backfill-slugs` auf Prod laufen lassen (falls noch nicht).
- [ ] **Upload-Verzeichnis:** `public/uploads/posts/` existiert und ist schreibbar (z. B. `mkdir -p public/uploads/posts && chmod 755 public/uploads/posts`).

### 3. Rechtliches & Inhalte (DSGVO / DE)
- [ ] **Impressum** vollständig und korrekt (Verantwortlicher, Kontakt).
- [ ] **Datenschutz/Privacy:** Link zur Datenschutzerklärung; Cookie-Banner ist bereits integriert.
- [ ] **AGB** prüfen und ggf. anpassen.

### 4. Sicherheit & Stabilität
- [ ] **Admin-Zugang:** Starker Passwort für den ersten Admin-User; ggf. nur bestimmte IPs für `/admin` erlauben (Webserver/Proxy).
- [ ] **HTTPS:** In Produktion nur HTTPS; HSTS und Redirect von HTTP → HTTPS einrichten (Webserver/Proxy).

### 5. Performance & Betrieb
- [ ] **Symfony Cache:** Nach Deploy `php bin/console cache:clear --env=prod`.
- [ ] **Assets:** Bei Asset Mapper ggf. Build/Export für Prod prüfen (z. B. `php bin/console asset-map:compile` falls genutzt).
- [ ] **Logs & Fehler:** In Prod keine sensiblen Infos in Fehlerseiten; Logging (z. B. `var/log/`) rotieren und sichern.

### 6. Optional
- [ ] **Backups:** Regelmäßige DB- und ggf. Upload-Backups einrichten.
- [ ] **Fehlerseiten:** Eigene Twig-Templates für 404/500 (`templates/bundles/TwigBundle/Exception/error404.html.twig` etc.) für ein einheitliches Layout.
- [ ] **Monitoring:** Health-Check oder einfaches Monitoring für Verfügbarkeit.

---

Kurz: Geheimnisse und Umgebung für Prod absichern, DB/Uploads/Slugs vorbereiten, Rechtliches prüfen, HTTPS und Admin-Zugang sichern – dann bist du gut vorbereitet für den Live-Gang.
