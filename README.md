# 📒 Directorio Telefónico

Aplicación web para gestionar y buscar contactos fácilmente.

## Funcionalidades
- Registro y login de usuarios.
- Alta, edición y eliminación de contactos.
- Búsqueda y filtrado por categorías.
- Visualización de contactos con avatares.

## Instalación rápida
1. Clona o copia el proyecto en tu servidor local (XAMPP, WAMP, etc.).
2. Importa la base de datos desde la carpeta `/App/config/BD.sql`.
3. Configura el SMTP y servidor BD en `/App/Config/.env.example` (_Luego quitar el .example_).

## 📦 Dependencias / Librerías usadas
1. Composer install con:
2. Libreria [Medoo] — Micro ORM para PHP.
3. Libreria [Phpmailer] — Envio de correo.
4. Libreria [phpdotenv] — variables de entorno.

## Análisis con SonarCloud (Opcional)
Para activarlo:
1. Crea un proyecto en [SonarCloud](https://sonarcloud.io).
2. Configura estos secrets en GitHub:
   - `SONAR_TOKEN` (token de SonarCloud).
   - `SONAR_PROJECT_KEY` (ej: `TuUsuario_MiProyecto`).
   - `SONAR_ORG` (nombre de tu organización).
3. Si no lo necesitas, borra `.github/workflows/sonarcloud.yml` y ` sonar-project.properties`.

> [!IMPORTANT]
> _Asegúrate de instalar o incluir las librerías necesarias antes de ejecutar la aplicación._

---
Desarrollado por Williboper Dev.
