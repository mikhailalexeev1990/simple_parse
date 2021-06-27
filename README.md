## Install

1) Create docker container
- `docker-compose up -d`

2) install
- `docker exec -it parser_local_php-fpm bash`
- `composer install && npm i && npm run dev`
- `php artisan db:create && php artisan migrate`
- start parser - `php artisan parse-rbk:news`
- [open web page](localhost:3009)

3) stop docker
- exit from docker container - use `exit`
- `docker-compose down` 
or (if you need to delete an image) `docker-compose down --rmi all`
