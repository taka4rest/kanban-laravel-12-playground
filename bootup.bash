#!/bin/sh
docker compose down --remove-orphans
docker compose up -d --build
docker compose exec php composer dump-autoload
docker compose exec php npm install
docker compose exec -T php npm run dev &