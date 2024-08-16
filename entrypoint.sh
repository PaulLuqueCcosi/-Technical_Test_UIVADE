#!/bin/bash

# Ejecuta docker-compose para levantar los contenedores
docker-compose up -d

# Espera a que los servicios de Docker estén listos (puedes ajustar el tiempo si es necesario)
sleep 10

php artisan migrate --force

# Inicia el servidor de desarrollo de Laravel
php artisan serve --host=0.0.0.0 --port=8000
