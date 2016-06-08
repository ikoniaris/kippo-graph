Kippo-Graph
===========

Kippo-Graph is a full featured script to visualize statistics for a Kippo based SSH honeypot.

It uses the Libchart PHP chart drawing library by Jean-Marc Trémeaux,
QGoogleVisualizationAPI PHP Wrapper for Google's Visualization API by Thomas Schäfer,
RedBeanPHP library by Gabor de Mooij, MaxMind and geoPlugin geolocation technology.

FIXES:</br>
1. Group By statements caused compatibility issues with php7.0 as mysql has made changes to "Group By" syntax </br>
ERROR: Syntax error or access violation: 1055 Expression #3 of SELECT list is not in GROUP BY clause and contains nonaggregated column</br>
References: https://dev.mysql.com/doc/refman/5.7/en/group-by-handling.html</br>
IMPACT: PHP version 5.3.4 or higher were not compatiable.</br>
modified:   class/KippoGraph.class.php "nonaggregated columns mysql patch"</br>
modified:   class/KippoPlayLog.class.php "nonaggregated columns mysql patch"</br>
Now operational with php7.0 and latest mysql</br>

REQUIREMENTS:
-------------
1. PHP version 5.3.4 or higher.
2. The following packages: _libapache2-mod-php7.0_, _php7.0-mysql_, _php7.0-gd_, _php7.0-curl_.

On Ubuntu/Debian:
> apt-get update && apt-get install -y libapache2-mod-php7.0 php7.0-mysql php7.0-gd php7.0-curl
>
> /etc/init.d/apache2 restart

QUICK INSTALLATION:
-------------------
> wget http://bruteforce.gr/wp-content/uploads/kippo-graph-VERSION.tar.gz
>
> mv kippo-graph-VERSION.tar.gz /var/www/html
>
> cd /var/www/html
>
> tar zxvf kippo-graph-VERSION.tar.gz
>
> mv kippo-graph-VERSION kippo-graph
>
> cd kippo-graph
>
> chmod 777 generated-graphs
>
> cp config.php.dist config.php
>
> nano config.php #enter the appropriate values

Browse to http://your-server/kippo-graph to view or generate the honeypot charts and statistics.

Note 1: If you choose to disable `REALTIME_STATS` in your config.php file it is advisable to
        setup a cron job to update the charts in the background. The recommended way to do that
        is to add the following line in your crontab with `crontab -e` (make sure to change the
        kippo-graph path if it's different):
> @hourly cd /var/www/html/kippo-graph && php kippo-graph.php > /dev/null 2>&1

Note 2: If you want to use the Kippo-Scanner component you will have to allow Kippo-Graph's .htaccess file
        to take effect. You can do this by editing your Apache configuration file at /etc/apache2/apache2.conf
        and changing `AllowOverride None` to `AllowOverride All` for the /var/www/ Directory (only).

Note 3: To fully use the geolocation features (Intensity Map) you will need to give CREATE
		TEMPORARY TABLES rights to your MySQL database user (most likely it has already been done).
