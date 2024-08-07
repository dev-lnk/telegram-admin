#!/bin/bash

set -e

MYSQL_PASSWORD=$1

PROJECT_DIR="/var/www/tg.lnk-dev.ru"
REPOSITORY="git@github.com:dev-lnk/telegram-admin.git"
USER="develop"

# make dir if not exists (first deploy)
mkdir -p $PROJECT_DIR

cd $PROJECT_DIR

git config --global --add safe.directory $PROJECT_DIR

# the project has not been cloned yet (first deploy)
if [ ! -d $PROJECT_DIR"/.git" ]; then
  GIT_SSH_COMMAND="ssh -i /home/$USER/.ssh/id_ed25519 -o IdentitiesOnly=yes" git clone $REPOSITORY .
else
  GIT_SSH_COMMAND="ssh -i /home/$USER/.ssh/id_ed25519 -o IdentitiesOnly=yes" git pull
fi

composer install --no-interaction --optimize-autoloader --no-dev

# initialize .env if does not exist (first deploy)
if [ ! -f $PROJECT_DIR"/.env" ]; then
    cp .env.example .env
    sed -i "/DB_PASSWORD/c\DB_PASSWORD=$MYSQL_PASSWORD" $PROJECT_DIR"/.env"
    sed -i '/QUEUE_CONNECTION/c\QUEUE_CONNECTION=database' $PROJECT_DIR"/.env"
    php artisan key:generate
fi

sudo chown -R www-data:www-data $PROJECT_DIR

php artisan storage:link
php artisan optimize:clear

php artisan migrate --force
php artisan optimize