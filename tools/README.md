Capturar capturas del sitio local

Requisitos:
- Node.js instalado (>=14)
- En la carpeta raíz del proyecto ejecutar `npm init -y` y `npm i puppeteer`

Cómo usar (Windows PowerShell):

1. Abrir PowerShell en la carpeta `c:\Users\Admin\Documents\MitoGafas\public_html`.
2. Inicializar dependencias (si no lo hiciste):

   npm init -y; npm i puppeteer

3. Ejecutar el script:

   node .\tools\capture-screenshots.js

Las capturas se guardarán en `screenshots/` dentro de la carpeta `public_html`.

Notas:
- El script abre el archivo `index.html` localmente con la ruta file://. Si tu proyecto corre en un servidor local (ej. php -S), cambia la variable `url` en el script.
- Puppeteer descargará una versión de Chromium cuando se instale; puede tardar dependiendo de la conexión.
