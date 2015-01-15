#!/bin/bash
if [ ! -d "/usr/share/vca/www" ];then
	echo "VCA is not present !";
	exit 0
fi

aptitude update
aptitude install nginx php5-fpm mysql-server php5-mysql php5-mcrypt gettext openssl -y

chmod 777 /usr/share/vca/www/templates_c

echo "Don't forget to configure mysql, php and nginx"
echo "Import the database"
echo "Configure config.php"
