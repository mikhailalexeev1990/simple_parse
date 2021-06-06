## Install

1) Create docker container
- docker-compose up -d

2) install dependencies
- docker exec -it parser_local_php-fpm bash
- composer install && npm i && npm run dev
- php artisan db:create && php artisan migrate

3) if you would like to delete container
- docker-compose down && 
