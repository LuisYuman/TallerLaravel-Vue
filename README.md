API en Laravel - Usuarios y Tareas

Proyecto de API REST desarrollada en Laravel, enfocada en la gestión de usuarios y tareas. Incluye autenticación mediante Laravel Sanctum, validaciones completas y la posibilidad de exportar reportes a Excel.

🚀 Funcionalidades principales

🔑 Autenticación basada en tokens personales con Sanctum

👥 CRUD completo para Usuarios y Tareas

🛡️ Roles de usuario: admin y usuario

📦 Respuestas JSON claras y consistentes

🔗 Relaciones entre modelos:

Usuario → hasMany → Tarea

Tarea → belongsTo → Usuario

📊 Generación de reportes en Excel con tareas pendientes

📋 Requisitos previos

PHP 8.1 o superior

MySQL o MariaDB

Composer 2+

Node.js (solo requerido si se compilan assets, no necesario para la API)

⚙️ Instalación

Clonar el repositorio e instalar dependencias

git clone <URL_DEL_REPO>
cd laravel-api
composer install


Configurar variables de entorno

cp .env.example .env
php artisan key:generate


Editar el archivo .env con los datos de la aplicación y la base de datos:

DB_DATABASE=laravel_api
DB_USERNAME=usuario
DB_PASSWORD=contraseña


Ejecutar migraciones (y opcionalmente seeders)

php artisan migrate
# php artisan db:seed


Iniciar servidor de desarrollo

php artisan serve
# Disponible en http://127.0.0.1:8000

🔑 Endpoints principales
Autenticación

POST /api/register → Crear usuario y obtener token

POST /api/login → Iniciar sesión y generar token

POST /api/logout → Cerrar sesión (requiere auth:sanctum)

Ejemplo de login:

curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"juan@ejemplo.com","password":"123456"}'


Respuesta esperada:

{
  "message": "Login exitoso",
  "usuario": { "id": 1, "nombre": "Juan Pérez", "email": "juan@ejemplo.com", "rol": "usuario" },
  "token": "1|abcdef..."
}


Para acceder a rutas protegidas agrega el header:
Authorization: Bearer TU_TOKEN

Usuarios

GET /api/usuarios/listUsers → Listar usuarios

POST /api/usuarios/addUser → Crear usuario

GET /api/usuarios/getUser/{id} → Ver usuario específico

PUT /api/usuarios/updateUser/{id} → Editar usuario

DELETE /api/usuarios/deleteUser/{id} → Eliminar usuario

Tareas

GET /api/tareas/ → Listar todas las tareas (con datos del usuario asociado)

POST /api/tareas/ → Crear nueva tarea

GET /api/tareas/{id} → Detalle de tarea

PUT /api/tareas/{id} → Actualizar tarea

DELETE /api/tareas/{id} → Eliminar tarea

GET /api/tareas/usuarios → Listado de usuarios para asignación

GET /api/tareas/report-pendientes → Descargar Excel con tareas pendientes

Ejemplo de creación de tarea:

curl -X POST http://localhost:8000/api/tareas/ \
  -H "Content-Type: application/json" \
  -d '{
    "titulo": "Preparar informe",
    "descripcion": "Informe mensual",
    "estado": "pendiente",
    "fecha_vencimiento": "2025-12-31",
    "user_id": 1
  }'

📂 Modelos y relaciones

Usuario (App\Models\Usuario)

Campos: nombre, email, password, rol

password oculto en respuestas ($hidden)

Relación: tareas() → hasMany

Tarea (App\Models\Tarea)

Campos: titulo, descripcion, estado, fecha_vencimiento, user_id

fecha_vencimiento casteada como date

Relación: user() → belongsTo Usuario

🔒 Seguridad

Tokens personales generados con Sanctum (createToken)

Validación de datos en controladores (errores 422 en caso de fallo)

Protección de información sensible en JSON

📊 Reportes en Excel

Ruta: GET /api/tareas/report-pendientes

Devuelve archivo .xlsx con tareas en estado pendiente

🗂️ Estructura relevante del proyecto
app/
├─ Http/Controllers/Api/
│  ├─ AuthController.php
│  ├─ UsuarioController.php
│  └─ TareaController.php
├─ Models/
│  ├─ Usuario.php
│  └─ Tarea.php
routes/
└─ api.php


Consulta también ARQUITECTURA.md y el diagrama diagrama-arquitectura.drawio para una visión general.

🛠️ Comandos útiles
php artisan serve      # Levantar el servidor
php artisan migrate    # Ejecutar migraciones
php artisan tinker     # Consola interactiva
php artisan route:list # Ver rutas disponibles
