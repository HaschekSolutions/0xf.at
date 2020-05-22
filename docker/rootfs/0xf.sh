#!/bin/bash
set -euo pipefail

_update() {
    echo "[i] Updating installation"
    curl --silent --remote-name https://codeload.github.com/HaschekSolutions/0xf.at/zip/master
    unzip -q master
    cp -r 0xf.at-master/* .
    rm -r master 0xf.at-master
    chmod -R 777 data
    chmod -R 777 comments
}

_filePermissions() {
    chown -R nginx:nginx /usr/share/nginx/html
}

_buildConfig() {
    echo "<?php"
    echo "error_reporting(E_ALL & ~E_NOTICE);"
    echo "ini_set('display_errors','Off');"
    echo "define('SALT', '${SALT:-}');"
    echo "define('RECAPTCHA_KEY', '${RECAPTCHA_KEY:-}');"
    echo "define('RECAPTCHA_SECRET', '${RECAPTCHA_SECRET:-}');"
}

_main() {
    echo 'Setting up 0xf.at'


    if [[ ${AUTO_UPDATE:=true} = true ]]; then
        _update
    fi

    _buildConfig > inc/config.inc.php

    echo '[i] Done! Starting nginx'

    exec /init
}

if [[ $0 = $BASH_SOURCE ]]; then
    _main "$@"
fi