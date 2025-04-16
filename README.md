# Sistema de Gestión Académica - Escuela David Urosa

Este proyecto es un sistema completo de gestión académica desarrollado para la Escuela David Urosa. Permite administrar academias, cursos, estudiantes, matrículas, pagos y comunicados a través de una interfaz web y una API RESTful.

## Características

- Gestión de academias y cursos
- Registro y seguimiento de estudiantes
- Proceso de matrícula e inscripción
- Sistema de pagos y control financiero
- Envío de comunicados a padres
- API RESTful completa para integración con otros sistemas

## Autenticación

Todos los endpoints (excepto los de autenticación) requieren un token de acceso válido. Para obtener un token, debes autenticarte utilizando el endpoint de login.

### Obtener Token de Acceso

```
POST /api/auth/login
```

**Parámetros de solicitud:**
```json
{
  "email": "usuario@ejemplo.com",
  "password": "contraseña",
  "device_name": "Mi Dispositivo" // Opcional
}
```

**Respuesta:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Usuario",
    "email": "usuario@ejemplo.com",
    "created_at": "2023-01-01T00:00:00.000000Z",
    "updated_at": "2023-01-01T00:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456789"
}
```

### Registrar Usuario (solo para desarrollo)

```
POST /api/auth/register
```

**Parámetros de solicitud:**
```json
{
  "name": "Nuevo Usuario",
  "email": "nuevo@ejemplo.com",
  "password": "contraseña",
  "password_confirmation": "contraseña"
}
```

### Cerrar Sesión (Revocar Token)

```
POST /api/auth/logout
```

**Headers requeridos:**
```
Authorization: Bearer {token}
```

## Academias

### Listar Academias

```
GET /api/academias
```

### Obtener Academia

```
GET /api/academias/{id}
```

### Crear Academia

```
POST /api/academias
```

**Parámetros de solicitud:**
```json
{
  "nombre": "Academia de Música",
  "descripcion": "Descripción de la academia",
  "direccion": "Dirección de la academia",
  "telefono": "123456789",
  "email": "academia@ejemplo.com"
}
```

### Actualizar Academia

```
PUT /api/academias/{id}
```

**Parámetros de solicitud:**
```json
{
  "nombre": "Nuevo nombre",
  "descripcion": "Nueva descripción",
  "direccion": "Nueva dirección",
  "telefono": "987654321",
  "email": "nuevo@ejemplo.com"
}
```

### Eliminar Academia

```
DELETE /api/academias/{id}
```

## Cursos

### Listar Cursos

```
GET /api/cursos
```

**Parámetros de consulta opcionales:**
```
academia_id=1
```

### Obtener Curso

```
GET /api/cursos/{id}
```

### Crear Curso

```
POST /api/cursos
```

**Parámetros de solicitud:**
```json
{
  "nombre": "Curso de Piano",
  "descripcion": "Descripción del curso",
  "academia_id": 1,
  "precio": 100.00,
  "cupo_maximo": 20,
  "fecha_inicio": "2023-01-01",
  "fecha_fin": "2023-12-31"
}
```

### Actualizar Curso

```
PUT /api/cursos/{id}
```

**Parámetros de solicitud:**
```json
{
  "nombre": "Nuevo nombre del curso",
  "descripcion": "Nueva descripción",
  "academia_id": 1,
  "precio": 150.00,
  "cupo_maximo": 25,
  "fecha_inicio": "2023-02-01",
  "fecha_fin": "2023-12-31"
}
```

### Eliminar Curso

```
DELETE /api/cursos/{id}
```

## Estudiantes

### Listar Estudiantes

```
GET /api/estudiantes
```

**Parámetros de consulta opcionales:**
```
padre_id=1
```

### Obtener Estudiante

```
GET /api/estudiantes/{id}
```

### Crear Estudiante

```
POST /api/estudiantes
```

**Parámetros de solicitud (con padre existente):**
```json
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "fecha_nacimiento": "2010-05-15",
  "genero": "masculino",
  "padre_id": 1
}
```

**Parámetros de solicitud (creando nuevo padre):**
```json
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "fecha_nacimiento": "2010-05-15",
  "genero": "masculino",
  "padre_nombre": "Pedro",
  "padre_apellido": "Pérez",
  "padre_email": "pedro@ejemplo.com",
  "padre_telefono": "123456789"
}
```

### Actualizar Estudiante

```
PUT /api/estudiantes/{id}
```

**Parámetros de solicitud:**
```json
{
  "nombre": "Nuevo nombre",
  "apellido": "Nuevo apellido",
  "fecha_nacimiento": "2010-05-15",
  "genero": "masculino",
  "padre_id": 1
}
```

### Eliminar Estudiante

```
DELETE /api/estudiantes/{id}
```

## Matrículas

### Listar Matrículas

```
GET /api/matriculas
```

**Parámetros de consulta opcionales:**
```
curso_id=1
estado=activa
```

### Obtener Matrícula

```
GET /api/matriculas/{id}
```

### Crear Matrícula

```
POST /api/matriculas
```

**Parámetros de solicitud:**
```json
{
  "curso_id": 1,
  "estudiante_id": 1,
  "fecha_inscripcion": "2023-01-15",
  "estado": "activa",
  "monto_inicial": 50.00,
  "metodo_pago": "efectivo"
}
```

### Actualizar Matrícula

```
PUT /api/matriculas/{id}
```

**Parámetros de solicitud:**
```json
{
  "curso_id": 1,
  "estudiante_id": 1,
  "fecha_inscripcion": "2023-01-15",
  "estado": "completada"
}
```

### Eliminar Matrícula

```
DELETE /api/matriculas/{id}
```

## Pagos

### Listar Pagos

```
GET /api/pagos
```

**Parámetros de consulta opcionales:**
```
matricula_id=1
fecha_desde=2023-01-01
fecha_hasta=2023-12-31
metodo=efectivo
```

### Obtener Pago

```
GET /api/pagos/{id}
```

### Crear Pago

```
POST /api/pagos
```

**Parámetros de solicitud:**
```json
{
  "matricula_id": 1,
  "monto": 50.00,
  "metodo": "efectivo",
  "fecha": "2023-01-15",
  "referencia": "Referencia opcional"
}
```

### Actualizar Pago

```
PUT /api/pagos/{id}
```

**Parámetros de solicitud:**
```json
{
  "monto": 75.00,
  "metodo": "transferencia",
  "fecha": "2023-01-15",
  "referencia": "Nueva referencia"
}
```

### Eliminar Pago

```
DELETE /api/pagos/{id}
```

### Obtener Resumen de Pagos por Matrícula

```
GET /api/matriculas/{matricula_id}/pagos/resumen
```

## Comunicados

### Listar Comunicados

```
GET /api/comunicados
```

**Parámetros de consulta opcionales:**
```
padre_id=1
curso_id=1
fecha_desde=2023-01-01
fecha_hasta=2023-12-31
```

### Obtener Comunicado

```
GET /api/comunicados/{id}
```

### Crear Comunicado

```
POST /api/comunicados
```

**Parámetros de solicitud (para todos los padres):**
```json
{
  "titulo": "Comunicado importante",
  "mensaje": "Contenido del comunicado",
  "filtro_tipo": "todos"
}
```

**Parámetros de solicitud (para padres de un curso específico):**
```json
{
  "titulo": "Comunicado para curso",
  "mensaje": "Contenido del comunicado",
  "filtro_tipo": "curso",
  "curso_id": 1
}
```

**Parámetros de solicitud (para padres de estudiantes en un rango de edad):**
```json
{
  "titulo": "Comunicado por edad",
  "mensaje": "Contenido del comunicado",
  "filtro_tipo": "edad",
  "edad_min": 8,
  "edad_max": 12
}
```

### Eliminar Comunicado

```
DELETE /api/comunicados/{id}
```

### Obtener Comunicados por Padre

```
GET /api/padres/{padre_id}/comunicados
```

## Instalación

### Requisitos previos

- PHP 8.1 o superior
- Composer
- MySQL o PostgreSQL
- Node.js y npm

### Pasos para la instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/tu-usuario/matricula-escuela-david-urosa.git
cd matricula-escuela-david-urosa
```

2. **Instalar dependencias de PHP**

```bash
composer install
```

3. **Copiar el archivo de entorno**

```bash
cp .env.example .env
```

4. **Configurar el archivo .env**

Edita el archivo `.env` y configura la conexión a la base de datos y otras variables de entorno necesarias:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. **Generar la clave de la aplicación**

```bash
php artisan key:generate
```

6. **Ejecutar las migraciones**

```bash
php artisan migrate
```

7. **Ejecutar los seeders (opcional)**

```bash
php artisan db:seed
```

8. **Instalar dependencias de JavaScript**

```bash
npm install
```

## Ejecución

### Iniciar el servidor de desarrollo

```bash
php artisan serve
```

### Compilar assets (en modo desarrollo)

```bash
npm run dev
```

### Compilar assets (para producción)

```bash
npm run build
```

## Prueba de la API

Puedes probar la API utilizando la herramienta incluida en el proyecto:

```
http://localhost:8000/test-api.html
```

Esta herramienta te permite:
- Registrar usuarios
- Iniciar sesión y obtener tokens
- Probar todos los endpoints de la API

## Códigos de Estado HTTP

- `200 OK`: La solicitud se ha completado correctamente
- `201 Created`: El recurso se ha creado correctamente
- `400 Bad Request`: La solicitud contiene datos inválidos
- `401 Unauthorized`: Se requiere autenticación
- `404 Not Found`: El recurso solicitado no existe
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

## Formato de Respuesta

Todas las respuestas siguen un formato consistente:

**Respuesta exitosa:**
```json
{
  "success": true,
  "data": { ... },
  "message": "Mensaje opcional"
}
```

**Respuesta de error:**
```json
{
  "success": false,
  "message": "Mensaje de error",
  "errors": { ... } // Opcional, para errores de validación
}
```
