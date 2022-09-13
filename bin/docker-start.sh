#! /bin/bash

composer install --no-dev -o
php bin/hyperf.php
php bin/hyperf.php start
