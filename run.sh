#!/bin/bash

set -e

printf "#1 Installing composer dependencies. That may take a while. Hold on...\n"
composer --version
composer install -q
printf "\n#2 Running tests to ensure application behaves properly...\n\n"
./vendor/bin/simple-phpunit tests
printf "\n#3 Starting application...\n"
./console.php app:check-weather
