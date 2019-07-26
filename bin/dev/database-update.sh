#!/bin/sh

echo "_________ START DATABASE MIGRATION SCRIPT ___________"

docker-compose exec -T api php core/yii migrate