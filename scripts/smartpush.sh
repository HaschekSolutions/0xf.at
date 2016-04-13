#!/bin/bash

cd /var/www/0xf
rsync -e 'ssh -p 1337 -ax' -aPv --exclude .git --exclude stats --exclude data/tmp --exclude data/users --exclude stats --exclude comments . root@212.17.118.125:/var/www/0xf

