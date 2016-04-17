# 0xf.at - hackits for everyone

0xf.at (or oxfat it you prefer) is a password-riddle (so called hackit) site. You could say it's [Project Euler](https://projecteuler.net/) for geeks.
This is a tribute site to the old Starfleet Academy Hackits site which has been offline for many years now.

### What do I need to host my own 0xf.at instance?
- A webserver running Apache or nginx
- PHP 5.5 or higher
- A (sub) domain
- No database needed

### Installation
- Download this repo: https://github.com/HaschekSolutions/0xf.at/archive/master.zip
- Unpack it to your web folder
- rename ```inc/example.config.inc.php``` to ```inc/config.inc.php``` and set the SALT value to some random string (used for user data encryption)
- Add a cronjob to start the TCP servers on reboot: ```@reboot cd /var/www/0xf/tcp_servers; ./start.sh```


# How to create your own level

1. Go to ```data/levels``` and find the highest level.
2. Make a copy of ```data/levels/template.php``` and rename it to ```data/levels/hackit<new level number>.php``` (eg. hackit36.php)
3. Edit your level and test it on your site

You can make a pull request if you want your level on the official 0xf.at site.