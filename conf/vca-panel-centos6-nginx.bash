#!/bin/bash
if [ ! -d "/usr/share/vca/www" ];then
	echo "VCA is not present !";
	exit 0
fi

yum install epel-release -y

yum install nginx php-fpm mysql-server php-mysql php-mcrypt gettext openssl -y

# Iptables
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -j ACCEPT
/sbin/service iptables save
iptables -L -v

# Services
chkconfig --levels 235 mysqld on
chkconfig --levels 235 nginx on
chkconfig --levels 235 php-fpm on

service mysqld start
service nginx start
service php-fpm start

chmod 777 /usr/share/vca/www/templates_c

echo "Don't forget to configure mysql, php and nginx"
echo "Import the database"
echo "Configure config.php"
