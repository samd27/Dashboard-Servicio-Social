# Dashboard-Servicio-Social

Proyecto para la gestión de servicio social, construido con Laravel 11, Breeze y Tailwind CSS.

---

## Tech Stack

- **Framework:** Laravel 11
- **PHP:** 8.3
- **Frontend:** Blade con Alpine.js (Instalado vía Breeze)
- **CSS:** Tailwind CSS
- **Base de Datos:** MySQL
- **Entorno de Desarrollo:** Laravel Herd
- **Node.js:** v24.x (LTS)

---

## Configuración Local

1. **Clonar el Repositorio**

   ```bash
   git clone [URL_DE_TU_REPOSITORIO]
   cd Dashboard-Servicio-Social
   ```

2. **Instalar Dependencias**

   ```bash
   composer install
   npm install
   ```

3. **Configurar Entorno**

   ```bash
   # Copiar el archivo de entorno
   cp .env.example .env

   # Generar la llave de la aplicación
   php artisan key:generate
   ```

4. **Base de Datos**

   - Crea una nueva base de datos MySQL llamada **`dashboard_servicio_social`**.
   - Actualiza tu archivo `.env` con las credenciales correctas.

   ```env
   DB_CONNECTION=mysql
   DB_DATABASE=dashboard_servicio_social
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Ajuste de Collation (Fix para Herd/MySQL)**

   - Edita el archivo `config/database.php`.
   - En la conexión `mysql`, cambia la línea `collation` por:

   ```php
   'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
   ```

6. **Migrar la Base de Datos**

   ```bash
   php artisan migrate
   ```

7. **Ejecutar el Servidor**

   ```bash
   npm run dev
   ```

   - Accede al proyecto en la URL provista por Herd (ej. `http://dashboard-servicio-social.test`).