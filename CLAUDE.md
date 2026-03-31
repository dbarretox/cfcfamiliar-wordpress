# CFC Familiar - WordPress Theme

## Comunicación
- Hablar en español

## Proyecto
- **Cliente**: Centro Familiar Cristiano (iglesia en Panamá)
- **Qué es**: Tema WordPress custom que el cliente instala en su WordPress existente
- **Repositorio**: github.com/dbarretox/cfcfamiliar-wordpress

## Stack
- WordPress theme clásico (PHP)
- Tailwind CSS v3 compilado (NO CDN)
- AOS (animaciones scroll)
- Sin plugins requeridos. Compatible con Gravity Forms y ACF

## Flujo de trabajo

### Desarrollo → Staging
1. Hago cambios en local (`/home/dbarreto/dev/clients/cfcfamiliar/wordpress/`)
2. Compilo Tailwind: `npm run build`
3. Subo por FTP al staging:
```bash
cd ~/dev/clients/cfcfamiliar/wordpress
lftp -u 'claude@serverdbarreto.com','mH3cjvVLpj(utl{]' -e "set ssl:verify-certificate no; set ftp:passive-mode yes" 104.244.154.76 << 'EOF'
put ARCHIVO -o wp-content/themes/cfcfamiliar/ARCHIVO
quit
EOF
```
4. El usuario verifica en serverdbarreto.com/pages/cfcfamiliar/

### Staging → Producción (cliente)
1. Commit + `./release.sh patch` (compila Tailwind, crea ZIP, push, GitHub Release)
2. El WordPress del cliente detecta la actualización automáticamente
3. El cliente actualiza desde su admin

### Compilar Tailwind
```bash
npm run build    # compilar una vez
npm run dev      # watch mode (desarrollo)
```

## Estructura del tema

### Páginas principales (templates fijos)
- `front-page.php` — Homepage (la que realmente renderiza el inicio)
- `page-inicio.php` — Template "Inicio" (requerido para que front-page.php funcione)
- `page-quienes-somos.php` — Misión, visión, equipo
- `page-ministerios.php` → include `archive-cfc_ministerio.php`
- `page-eventos.php` → include `archive-cfc_evento.php`
- `page-reflexiones.php` → include `archive-reflexiones.php`
- `page-visitanos.php` — Horarios, ubicación
- `page-dar.php` — Donaciones/ofrendas

### Custom Post Types
- `cfc_evento` — Eventos de la iglesia
- `cfc_ministerio` — Ministerios
- `cfc_equipo` — Miembros del equipo pastoral
- `cfc_grupo` — Grupos (adolescentes, jóvenes, parejas, etc.)
- **Reflexiones** — Usa Posts nativos de WordPress (renombrado en admin). Categorías con meta: icono, color, gradiente

### Archivos clave
- `functions.php` — Todo: CPTs, metaboxes, admin UI, helpers, menu walkers
- `tailwind.config.js` — Config de Tailwind v3 con colores custom
- `src/input.css` — Input para compilación de Tailwind
- `assets/css/tailwind.css` — CSS compilado (se genera, no editar manual)
- `assets/css/main.css` — Estilos custom (header, animaciones, Gravity Forms protect)
- `assets/js/main.js` — Header scroll, mobile menu, smooth scroll
- `release.sh` — Script de release (versioning, ZIP, GitHub Release)
- `deploy.sh` — En el directorio padre, sube por FTP al staging

### Auto-updater
- `inc/github-updater.php` — Detecta nuevas releases en GitHub y notifica al WordPress del cliente

## Configuración de la iglesia
- Las opciones globales (nombre, dirección, teléfono, WhatsApp, redes, horarios) se manejan en `CFC Familiar → Configuraciones` en el admin
- Los defaults están en `cfc_defaults()` en functions.php

## Admin UI
- Gutenberg desactivado para páginas con template CFC, CPTs, y Posts (Reflexiones)
- Páginas con template CFC: editor oculto, solo metaboxes del tema
- Screen Options, permalink, Page Attributes ocultos en páginas con template
- Los metaboxes se muestran/ocultan con JS según el template seleccionado
- CSS de metaboxes: estilo minimal sin gradientes

## Notas importantes
- `front-page.php` es el archivo que renderiza el homepage (NO page-inicio.php)
- page-inicio.php solo necesita existir como página con ese template para que front-page.php funcione
- Tailwind v3 (NO v4) — v4 rompe las clases existentes
- El deploy.sh usa la IP directa `104.244.154.76` porque `ftp.serverdbarreto.com` no resuelve DNS
- Sección "Encuentra Tu Lugar" (grupos carousel) está desactivada temporalmente con `if (false)`
- Reflexiones usa Posts nativos. En admin aparece como "Reflexiones" bajo menú CFC Familiar
- El cliente ya tiene Gravity Forms — el tema lo protege con CSS `all: revert` en `.gform_wrapper`

## Pendientes
- Rediseñar sección de Grupos (carousel estilo Apple desactivado)
- Implementar dashboard custom (cards con links a CPTs)
- White-label del admin (ocultar menús innecesarios de WordPress)
- Páginas con template → sin metaboxes editables, solo links a los CPTs correspondientes
