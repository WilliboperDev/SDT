# 📒 Directorio Telefónico

Aplicación web para gestión de contactos con autenticación de usuarios.

## 🚀 Características principales
- ✅ Registro y autenticación de usuarios.
- ➕ CRUD completo de contactos.
- 🔍 Búsqueda avanzada con filtros por categorías.
- 🖼️ Visualización con avatares personalizados

## ⚙️ Instalación rápida
1. Clona o copia el repositorio en tu servidor local (XAMPP, WAMP, etc.).
2. Importa la base de datos desde la carpeta `/App/config/BD.sql`.
3. Configura el SMTP y servidor BD en `/App/Config/.env.example` (_Luego quitar el .example_).

## 📦 Dependencias / Librerías usadas
1. Instalar con Composer install:
2. [Medoo] — Micro ORM para PHP.
3. [PHPMailer] — Envio de correo.
4. [vlucas/phpdotenv] — Gestión de variables de entorno.

## 🔍 Análisis con SonarCloud (Opcional)
Para activarlo:
1. Crea un proyecto en [SonarCloud](https://sonarcloud.io).
2. Configura estos secrets en GitHub:
   - `SONAR_TOKEN` (token de SonarCloud).
   - `SONAR_PROJECT_KEY` (ej: `TuUsuario_MiProyecto`).
   - `SONAR_ORG` (nombre de tu organización).
3. Si no lo necesitas, borra `.github/workflows/sonarcloud.yml` y `sonar-project.properties`.



> [!IMPORTANT]
> _Asegúrate de instalar o incluir las librerías necesarias antes de ejecutar la aplicación._

---
📄 Licencia

MIT License © 2025 Williboper Dev
