

# Proyecto de Laravel y PostgreSQL con Docker

## Descripción

Este proyecto utiliza Laravel para el desarrollo de aplicaciones web y PostgreSQL como sistema de gestión de bases de datos, todo gestionado mediante Docker para simplificar el entorno de desarrollo y despliegue.

## Requisitos

- Docker
- Docker Compose
- PHP (para ejecutar los scripts de Artisan localmente, si es necesario)
- Composer (para gestionar dependencias de PHP)

## Estructura del Proyecto

- **docker-compose.yml**: Configuración para los servicios de Docker.
- **entrypoint.bat**: Script para Windows para iniciar los contenedores y el servidor Laravel.
- **entrypoint.sh**: Script para Unix (Linux/Mac) para iniciar los contenedores y el servidor Laravel.

## Configuración

1. **Configura las Variables de Entorno**

   Asegúrate de tener un archivo `.env` en el directorio raíz del proyecto con las variables de entorno necesarias para Laravel y Docker. Un ejemplo de archivo `.env` puede ser:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=db
   DB_PORT=5432
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. **Configura Docker Compose**

   Asegúrate de que el archivo `docker-compose.yml` esté configurado correctamente para tus necesidades. El archivo proporcionado es:

   ```yaml
   version: '3.8'

   services:
     db:
       image: postgres:15
       container_name: postgres_db
       environment:
         POSTGRES_DB: ${DB_DATABASE}
         POSTGRES_USER: ${DB_USERNAME}
         POSTGRES_PASSWORD: ${DB_PASSWORD}
       ports:
         - "5432:5432"
       volumes:
         - db_data:/var/lib/postgresql/data

   volumes:
     db_data:
   ```

3. **Scripts de Entrada**

   - **`entrypoint.bat`** (para Windows):

     ```batch
     @echo off
     REM Levanta los contenedores en segundo plano
     docker-compose up -d

     REM Espera a que los servicios de Docker estén listos (ajusta el tiempo si es necesario)
     timeout /t 10 /nobreak

     php artisan serve --port=8000
     ```

   - **`entrypoint.sh`** (para Unix):

     ```bash
     #!/bin/bash

     # Ejecuta docker-compose para levantar los contenedores
     docker-compose up -d

     # Espera a que los servicios de Docker estén listos (puedes ajustar el tiempo si es necesario)
     sleep 10

     php artisan migrate --force

     # Inicia el servidor de desarrollo de Laravel
     php artisan serve --host=0.0.0.0 --port=8000
     ```

## Instrucciones para Ejecutar el Proyecto

1. **Ejecuta el Entrypoint**

   - En Windows, abre una terminal y ejecuta:

     ```batch
     entrypoint.bat
     ```

   - En Unix (Linux/Mac), abre una terminal y ejecuta:

     ```bash
     chmod +x entrypoint.sh
     ./entrypoint.sh
     ```

2. **Accede a la Aplicación**

   Una vez que los contenedores estén en funcionamiento y el servidor Laravel esté activo, puedes acceder a la aplicación en tu navegador en `http://localhost:8000`.

3. **Migraciones y Seeders**

   El script de entrada (`entrypoint.sh`) ejecutará las migraciones automáticamente. Si necesitas ejecutar migraciones adicionales o seeders manualmente, puedes hacerlo con los siguientes comandos:

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

## Desarrollo

- **Actualizar Dependencias de PHP**: Usa Composer para gestionar las dependencias de PHP. Ejecuta `composer install` dentro del contenedor de Laravel si necesitas instalar nuevas dependencias.

- **Desarrollar Nuevas Funcionalidades**: Realiza cambios en el código fuente de Laravel en el directorio del proyecto. Los cambios se reflejarán inmediatamente gracias a la configuración de volúmenes en Docker.

## Problemas Comunes

- **Contenedores No Se Inician**: Verifica los logs de Docker para identificar problemas. Usa `docker-compose logs` para ver los registros de los contenedores.

- **Conexión a la Base de Datos**: Asegúrate de que las credenciales en el archivo `.env` coincidan con las configuraciones en el `docker-compose.yml`.

