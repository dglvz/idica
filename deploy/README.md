Despliegue OHIF + Orthanc usando Docker Compose

Resumen
-------
Esta carpeta contiene un ejemplo mínimo para desplegar:
- `orthanc` (osimis/orthanc:23.12.1)
- `ohif` (ohif/viewer:latest)
- `nginx` (reverse-proxy que expone `/ohif` y `/orthanc`)

Instrucciones rápidas
---------------------
1. Copia la carpeta `deploy` al servidor (ej: `/opt/ohif-deploy`).
2. Edita `nginx/conf.d/ohif.conf` y reemplaza `REPLACE_WITH_BASE64` por la credencial base64 de Orthanc si quieres que el proxy agregue auth automáticamente:
   ```bash
   echo -n 'orthanc:orthanc' | base64
   ```
   Y pega el resultado como: `proxy_set_header Authorization "Basic <BASE64>";`

3. Revisa `ohif/config/default.json` para adaptar rutas si tu Orthanc expone DICOMweb en otra ruta.

4. Inicia el stack:
   ```bash
   cd /opt/ohif-deploy
   docker compose up -d
   ```

5. Verifica:
   - `docker compose ps` para ver contenedores ejecutándose.
   - `docker compose logs -f nginx` / `ohif` / `orthanc` para ver errores.

6. Prueba en el navegador:
   - `http://TU_SERVIDOR/ohif?StudyInstanceUIDs=<UID>`
   - Quita `/ohif` si montas en otra ruta en Nginx (ajusta `config/orthanc.php` en tu app si es necesario).

Notas
-----
- Si Docker ya está instalado y funcionando correctamente en el servidor, no necesitas reinstalarlo; solo copia los archivos y ejecuta `docker compose up -d`.
- El `orthanc.json` incluido es mínimo; ajusta según tus necesidades (autenticación, plugins, certificados, etc.).
- Por seguridad en producción usa HTTPS en Nginx (certs) y no dejes credenciales codificadas sin protección.

Si quieres, puedo:
- Generar una versión con TLS (Let's Encrypt) y ejemplo de `nginx` con HTTPS.
- Ajustar la configuración de OHIF según la versión exacta del `ohif/viewer` que usas.
