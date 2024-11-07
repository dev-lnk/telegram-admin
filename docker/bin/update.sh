#!/bin/bash

cd /var/www/app

php artisan migrate --force

php artisan optimize:clear

php artisan optimize
