#!/bin/bash

chmod -R 777 storage

cp ./docker/.env ./.env

chmod -R 777 storage

composer update

php artisan purge
