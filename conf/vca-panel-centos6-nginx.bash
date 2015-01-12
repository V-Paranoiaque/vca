#!/bin/bash
if [ ! -d "/usr/share/vca/daemon" ];then
	echo "VCA is not present !";
	exit 0
fi

yum install wget -y

wget http://download.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
rpm -ivh epel-release-6-8.noarch.rpm
rm -f epel-release-6-8.noarch.rpm

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
