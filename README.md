# Dacha
Dacha is a web app with purpose to help mikrotik user managing their hotspot without using mikrotik userman

## Requirement
* Web Server with PHP7 or above (because i developed it under php7, it will be good if you want to test it to run under php5)

## Installation
The installation process of this app is simple, we don't need to set up a database for this app, because this app is *database-less* (i hope it still database-less in the future). And i assume you have knowledge about webserver installation

* clone this repository into your public_html or htdocs (if you are using xampp)
* Edit this file

      dacha/application/config/config.php
* Find ```$config['state']='development';```, and change **development** into **production**
* And next, it is not necessary but i suggest you to look at it for a while about this file too

      dacha/application/config/routeros_conf.php
* We will find some array, but focus to ```$config['routeros_conf']```. The configuration inside this array will be appeared in the login page, change these values as you wish.
* lastly, try to visit your ```localhost``` and you will see the login page
