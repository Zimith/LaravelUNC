# SiMEP

Sistema de Monitoreo y Evaluación de Proyectos de la FCA-UNC, implementado con Laravel MVC sobre la base de datos legacy existente.

## Requisitos

- PHP 8.3 o superior con `pdo_mysql`, `fileinfo` y `mbstring`.
- Composer.
- MySQL con la base `simep` ya creada y las ocho tablas legacy.

## Puesta en marcha local

1. Instalar dependencias:

   ```powershell
   composer install
   ```

2. Copiar `.env.example` a `.env` y generar la clave:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

   El ejemplo configura MySQL en `127.0.0.1:3307`, base `simep`, usuario `root` y contraseña vacía. Ajustar esos valores sólo si el entorno local difiere.

3. Configurar Laragon para servir el directorio `public/`, o iniciar el servidor integrado:

   ```powershell
   php artisan serve
   ```

Los assets institucionales están disponibles en `public/css`, `public/js`, `public/images`, `public/fonts` y `public/icon`.

## Base de datos existente

La aplicación mapea directamente `usuarios`, `proyectos`, `usuario_x_proyecto`, `objetivos_especificos`, `indicadores`, `actividades`, `grados_avance` e `informe_avance`. No contiene migraciones de creación ni se debe ejecutar `migrate:fresh`, `migrate` u otra operación que altere la estructura de la base legacy.

El acceso verifica primero las contraseñas en texto plano heredadas y también acepta hashes Laravel si existieran. Los registros de avance conservan `inicio`, `fin` y `justificacion` como cadenas vacías cuando el estado no utiliza esos campos, de acuerdo con las restricciones `NOT NULL` de la tabla.

Los archivos de verificación se guardan en `public/fuenteValidacion/{idProyecto}/`. Se permiten `jpg`, `jpeg`, `png`, `pdf`, `doc`, `docx`, `xls`, `xlsx`, `zip` y `rar`.

## Pruebas

```powershell
php artisan test
```
