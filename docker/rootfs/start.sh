#!/bin/ash

echo 'Starting 0xf.at'

cd /var/www/0xf

echo ' [+] Starting php'
php-fpm83

echo ' [+] Starting nginx'
mkdir -p /var/logs/
chmod 777 /var/logs/
nginx

echo ' [+] Setting up config.ini'

chown -R nginx:nginx /var/www/0xf


_buildConfig() {
    echo "<?php"
    echo "define('URL','${URL:-http://localhost:8080}');"
    echo "define('IP','${IP}');"
    echo "define('SALT','${SALT}');"
    echo "define('RECAPTCHA_KEY','${RECAPTCHA_KEY}');"
    echo "define('RECAPTCHA_SECRET','${RECAPTCHA_SECRET}');"
}



_buildConfig > inc/config.inc.php

nohup node tcp_servers/tcp_23.js > /dev/null 2>&1 &
nohup node tcp_servers/tcp_27.js > /dev/null 2>&1 &

tail -f /var/logs/0xf*