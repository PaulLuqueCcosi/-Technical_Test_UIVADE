@echo off
REM Levanta los contenedores en segundo plano
docker-compose up -d

REM Espera a que los servicios de Docker estén listos (ajusta el tiempo si es necesario)
timeout /t 10 /nobreak


php artisan serve --port=8000
