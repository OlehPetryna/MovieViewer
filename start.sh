#!/usr/bin/env bash

php -f initdb.php $1 $2 $3 $4
php -S localhost:8000 index.php