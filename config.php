<?php
#Package: Kippo-Graph
#Version: 0.9
#Author: ikoniaris
#Website: bruteforce.gr/kippo-graph

#DIR_ROOT -- defines where your Kippo-Graph installation currently resides in.
#This should be an absolute directory/path, e.g. /var/www/kippo-graph for Linux
#or something like C:\BitNami\wampstack-X.XX\apache2\htdocs\kippo-graph for Windows
define('DIR_ROOT', '/var/www/kippo-graph');

#Chart language selection -- Default: en (English). Change the two-letter
#lang.XX.php language code to your preferred choice.
#Available options:
#en: English | fr: French | de: German | it: Italian | es: Spanish
#nl: Dutch | el: Greek | et: Estonian | pl: Polish | sv: Swedish
#ar: Arabic (currently not working)
require_once('include/languages/lang.en.php');

#You will have to change the following four definitions
#from the default values to the correct ones, according
#to your MySQL server instance. When you installed Kippo
#and configured MySQL logging, you should have created
#a new MySQL server user just for this job.
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'database');
define('DB_PORT', '3306');

#The following value determines whether Kippo-Graph would
#automatically check if a newer version is available for download.
#You can inspect the function at include/misc/versionCheck.php.
#It works by comparing the latest version number that resides
#in a text file uploaded on Kippo-Graph's website against the
#'VERSION' definition inside versionCheck.php. If the local
#number is lower than the online/remote one, you get the message.
#While in theory you can trust the remote website, I realise that
#you might think this procedure poses a risk to the privacy of
#your honeypot's IP address, because it is being transmitted and
#logged while retrieving the remote text file. For this reason,
#the following value ensures that having the update checking
#feature enabled is your choice and not forced.
#Change NO to YES if you want to enable it.
define('UPDATE_CHECK', 'YES');

?>