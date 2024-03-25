<p align="center">
  <a href="" rel="noopener">
 <img src="https://raw.githubusercontent.com/HaschekSolutions/0xf.at/master/css/imgs/logo.png" alt="0xf.at logo"></a>
</p>

<h1 align="center">0xf.at</h1>


<h4 align="center"><a href="https://0xf.at">https://0xf.at</a></h4>

<div align="center">
 
  
![](https://img.shields.io/badge/php-8.3%2B-brightgreen.svg)
[![](https://img.shields.io/docker/pulls/hascheksolutions/0xf.at?color=brightgreen)](https://hub.docker.com/r/hascheksolutions/0xf.at)
[![](https://img.shields.io/docker/cloud/build/hascheksolutions/0xf.at?color=brightgreen)](https://hub.docker.com/r/hascheksolutions/0xf.at/builds)
[![Apache License](https://img.shields.io/badge/license-Apache-brightgreen.svg?style=flat)](https://github.com/HaschekSolutions/0xf.at/blob/master/LICENSE)
[![HitCount](http://hits.dwyl.io/HaschekSolutions/0xf.at.svg)](http://hits.dwyl.io/HaschekSolutions/0xf.at)
[![](https://img.shields.io/github/stars/HaschekSolutions/0xf.at.svg?label=Stars&style=social)](https://github.com/HaschekSolutions/0xf.at)

#### Hackits for `javascript`, `PHP`, `HTML`, `password cracking`, `wifi cracking` and more

</div>

# About
0xf.at (or oxfat it you prefer) is a password-riddle (so called hackit) site. You could say it's [Project Euler](https://projecteuler.net/) for it security or IT in general.
This is a tribute site to the old Starfleet Academy Hackits site which has been offline for many years now.


# License
It's licensed under GPL3 which means you can copy, sell, change 0xfat but all changes have to be public (also open source) and your code must be released under GPL3 as well.

### What do I need to host my own 0xf.at instance?

#### The easy way using docker Docker

Run `docker run --rm --name 0xf -p 8080:80 hascheksolutions/0xf.at` and point your browser to http://localhost:8080

#### The harder way
- A webserver running Apache or nginx
- PHP 7.2 or higher
- NodeJS for the TCP server levels
- A (sub) domain
- No database needed

1. Download this repo: https://github.com/HaschekSolutions/0xf.at/archive/master.zip
2. Unpack it to your web folder
3. rename ```inc/example.config.inc.php``` to ```inc/config.inc.php``` and set the SALT value to some random string (used for user data encryption)
4. Add a cronjob to start the TCP servers on reboot: ```@reboot cd /var/www/0xf/tcp_servers; ./start.sh```


# How to create your own level

1. Go to ```data/levels``` and find the highest level.
2. Make a copy of ```data/levels/template.php``` and rename it to ```data/levels/hackit<new level number>.php``` (eg. hackit36.php)
3. Edit your level and test it on your site

You can make a pull request if you want your level on the official 0xf.at site.

## Easy development

0xf supports development using the PHP-integrated webserver. So you can just download the code and from the main directory run `php -S localhost:8080` and point your browser to http://localhost:8080

# Trivia
- The site was originally created to be used by computer science teachers only. The creator Christian Haschek [stated in his blog](http://blog.haschek.at/post/f7e62) that hackits are the first thing he teaches new classes to spark flames for computer science and security.
- The name 0xfat was actually a coincidence as the creator wanted to buy a short URL because he wanted to make short domains for his projects. Later this seemed unnecessary for him and he implemented his idea of a hackit site for his students under this domain.
- Users can't change or recover their passwords because the site uses the user password to encrypt a text file which contains all info about a user. If a password is lost, there is no way (other than brute force) to recover the stats.