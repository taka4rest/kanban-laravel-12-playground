docker compose exec php composer dump-autoload
docker compose exec php php artisan config:cache
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear
docker compose exec php php artisan view:clear
docker compose exec php php artisan route:clear
docker compose exec php php artisan optimize:clear
docker compose exec php php artisan optimize
