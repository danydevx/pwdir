# Deploy Skill - ProcessWire Listings

## Objetivo

Desplegar el MVP de Listings de forma segura usando Git, GitHub Actions y SSH.

El deploy debe ser:

* Simple
* Repetible
* Compatible con ProcessWire
* Seguro para archivos subidos desde el admin
* Compatible con migraciones
* Fácil de mantener

---

# Stack

Usar:

* Git
* GitHub
* GitHub Actions
* SSH
* rsync
* Composer solo si el proyecto lo requiere
* npm solo si hay build frontend

No usar:

* Docker en el MVP
* Kubernetes
* Deploys manuales por FTP
* CI/CD demasiado complejo
* Comandos que borren archivos persistentes de ProcessWire

---

# Ramas

Usar:

```text
main        producción
develop     staging/desarrollo
feature/*   cambios puntuales
```

Flujo:

```text
feature/* → develop → main
```

No trabajar directo sobre:

```text
main
```

---

# Ambientes

Recomendado:

```text
staging.midominio.com
midominio.com
```

Cada ambiente debe tener su propio:

```text
/site/config.php
base de datos
/site/assets/files/
```

No compartir base de datos entre staging y producción.

No compartir `site/config.php`.

---

# Archivos que Sí se Despliegan

```text
/wire/
/index.php
/.htaccess

/site/templates/
/site/modules/ propios
/site/migrations/
/site/migrate.php
/site/classes/
/site/config.example.php

/package.json
/package-lock.json
/vite.config.js

/AGENTS.md
/skills/
```

Si no hay frontend con build todavía, `package.json`, `package-lock.json` y `vite.config.js` pueden no existir.

---

# Archivos que NO se Despliegan o NO se Sobrescriben

```text
/site/config.php
/site/migrations.log
/site/assets/files/
/site/assets/cache/
/site/assets/logs/
/site/assets/sessions/
/site/assets/backups/

/node_modules/
/vendor/

.env
```

Especialmente importante:

```text
/site/assets/files/
```

Nunca borrar ni sobrescribir esta carpeta en producción.

Ahí viven las imágenes y archivos subidos desde ProcessWire.

---

# .gitignore Recomendado

```gitignore
# ProcessWire local config
/site/config.php
/site/migrations.log

# ProcessWire runtime assets
/site/assets/cache/
/site/assets/logs/
/site/assets/sessions/
/site/assets/backups/

# Uploaded files
/site/assets/files/

# Dependencies
/node_modules/
/vendor/

# Environment
.env

# OS / logs
.DS_Store
Thumbs.db
npm-debug.log*
yarn-debug.log*
yarn-error.log*
```

---

# Deploy con rsync

Preferir `rsync` sobre `scp` porque permite excluir carpetas.

Ejemplo:

```bash
rsync -az --delete \
  --exclude="/site/config.php" \
  --exclude="/site/migrations.log" \
  --exclude="/site/assets/files/" \
  --exclude="/site/assets/cache/" \
  --exclude="/site/assets/logs/" \
  --exclude="/site/assets/sessions/" \
  --exclude="/site/assets/backups/" \
  --exclude="/node_modules/" \
  --exclude="/vendor/" \
  --exclude="/.env" \
  ./ user@server:/ruta/del/proyecto/
```

Nota:

`--delete` es útil, pero peligroso si no se excluyen bien carpetas persistentes.

Nunca usar `--delete` sin excluir:

```text
/site/assets/files/
```

---

# GitHub Secrets

Crear secrets:

```text
SSH_HOST
SSH_PORT
SSH_USER
SSH_PRIVATE_KEY
STAGING_REMOTE_PATH
PRODUCTION_REMOTE_PATH
```

Opcional:

```text
STAGING_URL
PRODUCTION_URL
```

No guardar secrets en el repositorio.

---

# GitHub Actions Básico sin Build Frontend

Usar esta versión cuando todavía no exista Vue, Vite o build frontend.

Archivo:

```text
.github/workflows/deploy.yml
```

Contenido:

```yaml
name: Deploy

on:
  push:
    branches:
      - develop
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Setup SSH
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_PRIVATE_KEY }}" > ~/.ssh/deploy_key
          chmod 600 ~/.ssh/deploy_key
          ssh-keyscan -p "${{ secrets.SSH_PORT }}" "${{ secrets.SSH_HOST }}" >> ~/.ssh/known_hosts

      - name: Select remote path
        id: vars
        run: |
          if [ "${GITHUB_REF_NAME}" = "main" ]; then
            echo "remote_path=${{ secrets.PRODUCTION_REMOTE_PATH }}" >> "$GITHUB_OUTPUT"
          else
            echo "remote_path=${{ secrets.STAGING_REMOTE_PATH }}" >> "$GITHUB_OUTPUT"
          fi

      - name: Deploy files
        run: |
          rsync -az --delete \
            -e "ssh -i ~/.ssh/deploy_key -p ${{ secrets.SSH_PORT }}" \
            --exclude="/site/config.php" \
            --exclude="/site/migrations.log" \
            --exclude="/site/assets/files/" \
            --exclude="/site/assets/cache/" \
            --exclude="/site/assets/logs/" \
            --exclude="/site/assets/sessions/" \
            --exclude="/site/assets/backups/" \
            --exclude="/node_modules/" \
            --exclude="/vendor/" \
            --exclude="/.env" \
            ./ "${{ secrets.SSH_USER }}@${{ secrets.SSH_HOST }}:${{ steps.vars.outputs.remote_path }}"

      - name: Run migrations
        run: |
          ssh -i ~/.ssh/deploy_key -p "${{ secrets.SSH_PORT }}" \
            "${{ secrets.SSH_USER }}@${{ secrets.SSH_HOST }}" \
            "cd '${{ steps.vars.outputs.remote_path }}' && php site/migrate.php"
```

---

# GitHub Actions con Build Frontend

Usar esta versión cuando ya exista Vite o build frontend.

```yaml
name: Deploy

on:
  push:
    branches:
      - develop
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout
        uses: actions/checkout@v4

      - name: Setup Node
        uses: actions/setup-node@v4
        with:
          node-version: 22

      - name: Install dependencies
        run: npm ci

      - name: Build frontend
        run: npm run build

      - name: Setup SSH
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_PRIVATE_KEY }}" > ~/.ssh/deploy_key
          chmod 600 ~/.ssh/deploy_key
          ssh-keyscan -p "${{ secrets.SSH_PORT }}" "${{ secrets.SSH_HOST }}" >> ~/.ssh/known_hosts

      - name: Select remote path
        id: vars
        run: |
          if [ "${GITHUB_REF_NAME}" = "main" ]; then
            echo "remote_path=${{ secrets.PRODUCTION_REMOTE_PATH }}" >> "$GITHUB_OUTPUT"
          else
            echo "remote_path=${{ secrets.STAGING_REMOTE_PATH }}" >> "$GITHUB_OUTPUT"
          fi

      - name: Deploy files
        run: |
          rsync -az --delete \
            -e "ssh -i ~/.ssh/deploy_key -p ${{ secrets.SSH_PORT }}" \
            --exclude="/site/config.php" \
            --exclude="/site/migrations.log" \
            --exclude="/site/assets/files/" \
            --exclude="/site/assets/cache/" \
            --exclude="/site/assets/logs/" \
            --exclude="/site/assets/sessions/" \
            --exclude="/site/assets/backups/" \
            --exclude="/node_modules/" \
            --exclude="/vendor/" \
            --exclude="/.env" \
            ./ "${{ secrets.SSH_USER }}@${{ secrets.SSH_HOST }}:${{ steps.vars.outputs.remote_path }}"

      - name: Run migrations
        run: |
          ssh -i ~/.ssh/deploy_key -p "${{ secrets.SSH_PORT }}" \
            "${{ secrets.SSH_USER }}@${{ secrets.SSH_HOST }}" \
            "cd '${{ steps.vars.outputs.remote_path }}' && php site/migrate.php"
```

---

# Migraciones en Deploy

Después de subir archivos, correr:

```bash
php site/migrate.php
```

Las migraciones deben ser idempotentes.

No correr migraciones destructivas sin respaldo.

No usar:

```bash
php site/tools/run-migrations.php
```

porque el runner oficial del proyecto es:

```bash
php site/migrate.php
```

---

# Permisos

ProcessWire necesita escritura en:

```text
/site/assets/
/site/assets/files/
/site/assets/cache/
/site/assets/logs/
/site/assets/sessions/
```

Permisos comunes:

```bash
find site/assets -type d -exec chmod 755 {} \;
find site/assets -type f -exec chmod 644 {} \;
```

En servidores donde Apache/PHP usa otro usuario, puede ser necesario ajustar owner/grupo.

Evitar permisos demasiado abiertos:

```text
0777
0666
```

Solo usar permisos amplios temporalmente en desarrollo local si no hay alternativa.

---

# Configuración por Ambiente

Cada servidor mantiene su propio:

```text
/site/config.php
```

No subir credenciales al repositorio.

Crear ejemplo seguro:

```text
/site/config.example.php
```

sin datos reales.

`config.example.php` puede documentar:

```php
$config->dbHost = 'localhost';
$config->dbName = 'database_name';
$config->dbUser = 'database_user';
$config->dbPass = 'database_password';
$config->httpHosts = ['example.com'];
```

---

# Build Frontend

En MVP clásico puede no existir build.

Cuando se use Vite:

```bash
npm ci
npm run build
```

El build debe generar archivos dentro de:

```text
/site/templates/assets/dist/
```

o la carpeta definida por el proyecto.

No subir:

```text
/node_modules/
```

---

# Rollback Simple

Mantener Git limpio.

Rollback recomendado:

```bash
git revert <commit>
git push
```

También se puede redeployar un commit anterior desde GitHub Actions si el flujo lo permite.

Recordatorio:

Git no revierte base de datos ni archivos subidos desde ProcessWire.

---

# Backups

Antes de deploys importantes en producción:

* Respaldar base de datos.
* Respaldar `/site/assets/files/`.

No asumir que Git protege contenido subido desde el admin.

---

# Cache

Después del deploy se puede limpiar cache si es necesario.

Ejemplo:

```bash
rm -rf site/assets/cache/*
```

Nunca borrar:

```text
site/assets/files/
```

---

# Seguridad

No imprimir secrets en logs.

No guardar llaves privadas dentro del repositorio.

No subir:

```text
.env
site/config.php
site/migrations.log
```

No usar permisos 777 en producción salvo emergencia temporal.

---

# Agente IA

Cuando el agente modifique deploy debe revisar:

* Que no se borre `/site/assets/files/`.
* Que no se suba `site/config.php`.
* Que no se suba `site/migrations.log`.
* Que las migraciones corran después del deploy.
* Que el comando de migración sea `php site/migrate.php`.
* Que los comandos funcionen por SSH.
* Que el workflow distinga `develop` y `main`.
* Que no agregue Docker ni sistemas complejos.
* Que no dependa de FTP manual.

---

# Checklist Antes de Activar Deploy

Confirmar:

```text
Repositorio creado.
Rama develop existe.
Rama main existe.
SSH funciona.
GitHub Secrets configurados.
Remote path correcto para staging.
Remote path correcto para producción.
site/config.php existe manualmente en cada servidor.
Base de datos creada en cada servidor.
site/assets/files/ existe en cada servidor.
site/assets/ tiene permisos correctos.
php site/migrate.php funciona en servidor.
```

---

# Criterio de Éxito

El deploy está bien si:

* Se ejecuta con push a `develop` o `main`.
* `develop` despliega a staging.
* `main` despliega a producción.
* Respeta archivos persistentes de ProcessWire.
* No borra `/site/assets/files/`.
* No sobrescribe `/site/config.php`.
* Ejecuta migraciones.
* No expone secretos.
* No depende de FTP.
* Es simple de mantener.
