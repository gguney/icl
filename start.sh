#!/bin/bash

docker start icl_db
docker start icl_php-fpm
docker start icl_nginx
open http://localhost:86
