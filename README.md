  ___        __       _ 
 / _ \__  __/ _| __ _| |_  
| | | \ \/ / |_ / _` | __| 
| |_| |>  <|  _| (_| | |_   
 \___//_/\_\_|(_)__,_|\__|  

# 0xf.at - hackits for everyone

0xf.at (or oxfat it you prefer) is a password-riddle (so called hackit) site. You could say it's [Project Euler](https://projecteuler.net/) for geeks.
This is a tribute site to the old Starfleet Academy Hackits site which has been offline for many years now.

# License
It's licensed under GPL3 which means you can copy, sell, change 0xfat but all changes have to be public (also open source) and your code must be released under GPL3 as well.

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

# Trivia
- The site was originally created to be used by computer science teachers only. The creator Christian Haschek [stated in his blog](http://blog.haschek.at/post/f7e62) that hackits are the first thing he teaches new classes to spark flames for computer science and security.
- The name 0xfat was actually a coincidence as the creator wanted to buy a short URL because he wanted to make short domains for his projects. Later this seemed unnecessary for him and he implemented his idea of a hackit site for his students under this domain.
- Users can't change or recover their passwords because the site uses the user password to encrypt a text file which contains all info about a user. If a password is lost, there is no way (other than brute force) to recover the stats.