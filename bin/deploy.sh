#!/bin/sh

echo "_________ START DEPLOYMENT SCRIPT ___________"

echo "Database configuration:"
docker-compose exec -T api php core/yii config-db -h=$1 -d=$2 -u=$3 -p=$4

echo "Run migrations:"
docker-compose exec -T api php core/yii migrate/up --interactive=0