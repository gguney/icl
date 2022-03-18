#!/bin/bash

docker volume create icl-data

docker-compose up -d

cp ./.env.example ./.env

docker exec -i icl_php-fpm composer install
docker exec -i icl_php-fpm npm install
docker exec -i icl_php-fpm npm run prod
docker exec -i icl_php-fpm php artisan key:generate
docker exec -i icl_db mariadb -uroot -pmy-secret-psw <<< "CREATE DATABASE IF NOT EXISTS icl_dev;"
docker exec -i icl_php-fpm php artisan migrate:fresh --seed

open http://localhost:86

