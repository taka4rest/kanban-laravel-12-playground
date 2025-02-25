#!/bin/sh
docker compose down --remove-orphans
echo "" > src/storage/logs/laravel.log
docker compose up -d --build
docker compose exec php composer dump-autoload
docker compose exec php php artisan config:cache
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear
docker compose exec php php artisan view:clear
docker compose exec php php artisan route:clear
docker compose exec php php artisan migrate:fresh --seed
docker compose exec php php artisan optimize:clear
docker compose exec php php artisan optimize
docker compose exec php npm install
docker compose exec -T php npm run dev &