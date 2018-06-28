<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| This is default configuration file used for connect to a router os
| you can set it all (except port) to null if you won't show it on login page
|
|
*/
$config['routeros_conf']=array(
						'address'	=>'192.168.2.1',	//you can fill it by ip address or a domain name of your mikrotik
						'port'		=>8728,				//default port for api, (default 8729 for api-ssl)
						'username'	=>'admin',
						'password'	=>'ruvaak1knight'
						);

$config['limit-uptime']=array(							//this config used for add and edit user form
						'1d'		=>'1 Day',
						'2d'		=>'2 Days',
						'1w'		=>'1 Week',
						'4w2d'		=>'1 Month',
						'0'			=>'Unlimited'
							);
$config['dacha']=array(
					'version'	=>'0.1-beta'
						);