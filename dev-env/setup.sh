#!/bin/bash

###############################################################

              # SETUP YOUR LOCAL DEV ENVIRONMENT #

###############################################################

# Run Artisan commands to set up keys and configs
docker-compose exec php php artisan config:clear

# Run all migrations
docker-compose exec php php artisan migrate:fresh --seed