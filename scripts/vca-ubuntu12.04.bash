#!/bin/bash

if [ ! -d "/usr/share/vca/www" ] ; then 
	echo "VCA is not present !"
	exit 0
fi

DAEMON=1
PANEL=1
ARCH=$(uname -m)

if [ ! -f /usr/bin/apt-get ] ; then
	echo "Error, you use an unsupported OS"
	exit 0;
fi

if [ "${DAEMON}" == "1" ] && [ "${ARCH}" = "x86_64" ] ; then
	while [ "${INSTALL_DAEMON}" != "y" ] && [ "${INSTALL_DAEMON}" != "n" ]; do
		echo "Would you like to install VCA daemon? y/n"
		read INSTALL_DAEMON
	done

	if [ "${INSTALL_DAEMON}" == "n" ] ; then
		DAEMON=0
	fi
else
	DAEMON=0
	echo "You can't install VCA daemon on this system"
fi

if [ "${PANEL}" == "1" ] ; then
	while [ "${INSTALL_PANEL}" != "y" ] && [ "${INSTALL_PANEL}" != "n" ]; do
		echo "Would you like to install VCA panel? y/n"
		read INSTALL_PANEL
	done

	if [ "${INSTALL_PANEL}" == "n" ] ; then
		PANEL=0
	fi
fi

if [ "${PANEL}" == "0" ] && [ "${DAEMON}" == "0" ] ; then
	echo "Nothing to do :("
	exit 0;
fi

if [ "${DAEMON}" == "1" ] ; then
	#For Openvz
	sed -i 's/kernel.sysrq = 0/kernel.sysrq = 1/g' /etc/sysctl.conf
	sed -i 's/net.ipv4.ip_forward = 0/net.ipv4.ip_forward = 1/g' /etc/sysctl.conf

	# OpenVZ repo
	echo "deb http://download.openvz.org/debian wheezy main" > /etc/apt/sources.list.d/openvz-rhel6.list
	echo "#deb http://download.openvz.org/debian wheezy-test main" >> /etc/apt/sources.list.d/openvz-rhel6.list
	wget http://ftp.openvz.org/debian/archive.key
	apt-key add archive.key
	rm -f archive.key
	apt-get update
	
	# All needed packages
	apt-get install clamav fuse ploop python3 python3-crypto python3-dev python3-setuptools vzctl screen -y
	easy_install3 pip
	pip3 install dropbox
	
	if [ ! -f /usr/share/vca/daemon/vca.cfg ] ; then 
		DAEMON_KEY=`date +%s | sha512sum | base64 | head -c 32 ; echo`
		echo "[DEFAULT]" >> /usr/share/vca/daemon/vca.cfg
		echo "key = ${DAEMON_KEY}" >> /usr/share/vca/daemon/vca.cfg
		echo "port = 10000" >> /usr/share/vca/daemon/vca.cfg

		#Only for local
		if [ "${PANEL}" == "1" ] ; then
			echo "host = 127.0.0.1" >> /usr/share/vca/daemon/vca.cfg
		else
			echo "host = 0.0.0.0" >> /usr/share/vca/daemon/vca.cfg
		fi
	fi	
	
	mkdir -p /root/.ssh
	chmod 700 /root/.ssh
	
	if [ ! -f /root/.ssh/id_rsa ] ; then
		ssh-keygen -t rsa -f /root/.ssh/id_rsa -P ""
	fi
	
	#Startup script
	cp /usr/share/vca/conf/vcadaemon.upstart /etc/init.d/vcadaemon
	chmod 755 /etc/init.d/vcadaemon
	update-rc.d vcadaemon defaults
fi

if [ "${PANEL}" == "1" ] ; then 
	# 1 apache
	# 2 nginx
	SERVER=0
	while [ ${SERVER} != 1 ] && [ ${SERVER} != 2 ]; do
		echo "Which server do you use?"
		echo "1 apache"
		echo "2 nginx"
		read SERVER
	done
	
	DOMAIN=""
	while [ "${DOMAIN}" == "" ]; do
		echo "Enter your domain"
		read DOMAIN
	done
	
	#Update packages
	apt-get update
	
	#apache
	if [ "${SERVER}" == "1" ] ; then 
		apt-get install apache2 libapache2-mod-php5 php5 -y
		
		#Configure
		rm -f /etc/apache2/sites-enabled/vca-panel.conf
		cp /usr/share/vca/conf/vca-apache-example.conf /etc/apache2/sites-available/vca-panel.conf
		sed -i s/vca.example.com/${DOMAIN}/g /etc/apache2/sites-available/vca-panel.conf
		ln -s /etc/apache2/sites-available/vca-panel.conf /etc/apache2/sites-enabled/vca-panel.conf
		
		a2enmod rewrite
		a2enmod ssl
	#nginx
	elif [ "${SERVER}" == "2" ] ; then 
		apt-get install nginx php5-fpm php5-cli -y
		if [ ! -f /etc/nginx/conf.d/php5-fpm.conf ] ; then 
			echo "upstream php5-fpm-sock {" > /etc/nginx/conf.d/php5-fpm.conf
			echo "	server unix:/var/run/php5-fpm.sock;" >> /etc/nginx/conf.d/php5-fpm.conf
			echo "}" >> /etc/nginx/conf.d/php5-fpm.conf
		fi
		
		sed -i s/127.0.0.1:9000/\\\/var\\\/run\\\/php5-fpm.sock/g /etc/php5/fpm/pool.d/www.conf
		sed -i s/\;listen\.owner/listen\.owner/g /etc/php5/fpm/pool.d/www.conf
		sed -i s/\;listen\.group/listen\.group/g /etc/php5/fpm/pool.d/www.conf
		sed -i s/\;listen\.mode/listen\.mode/g /etc/php5/fpm/pool.d/www.conf
		
		#Configure
		rm -f /etc/nginx/sites-enabled/vca-panel.conf
		cp /usr/share/vca/conf/vca-nginx-example.conf /etc/nginx/sites-available/vca-panel.conf
		sed -i s/vca.example.com/${DOMAIN}/g /etc/nginx/sites-available/vca-panel.conf
		ln -s /etc/nginx/sites-available/vca-panel.conf /etc/nginx/sites-enabled/vca-panel.conf
	fi
	
	apt-get install bsdutils -y
	apt-get install mysql-server php5-apc php5-curl php5-mysql gettext openssl php5-mcrypt -y
	php5enmod curl
	php5enmod mcrypt
	
	#Template cache
	chmod 777 /usr/share/vca/www/templates_c
	
	#Cron
	cp /usr/share/vca/conf/vca.cron /etc/cron.d/
	mkdir -p /var/log/vca
	
	#Gettext
	for r in /usr/share/vca/www/lang/*; do
		if [ -f $r/LC_MESSAGES/messages.po ] ; then
			msgfmt $r/LC_MESSAGES/messages.po -o $r/LC_MESSAGES/messages.mo
		fi
	done
	
	#SSL
	mkdir -p /etc/ssl/certs
	if [ ! -f "/etc/ssl/certs/${DOMAIN}.crt" ] ; then 
		openssl req \
		-new \
		-newkey rsa:4096 \
		-days 365 \
		-nodes \
		-x509 \
		-subj "/C=US/ST=World/L=World/O=Dis/CN=${DOMAIN}" \
		-keyout /etc/ssl/certs/${DOMAIN}.key \
		-out /etc/ssl/certs/${DOMAIN}.crt
	fi
	
	echo "Import DB"
	
	#Import DB
	mysql --defaults-file=/etc/mysql/debian.cnf -e "CREATE DATABASE IF NOT EXISTS vca"
	
	for f in /usr/share/vca/db/create/*.sql; do
		mysql --defaults-file=/etc/mysql/debian.cnf vca < "$f"
	done
	
	PASSWORD=`date +%s | sha256sum | base64 | head -c 32 ; echo`
	
	#DB configuration
	echo "DB configuration"
	sed -i s/\(\'DB_NAME\'\,\ \'\'\)/\(\'DB_NAME\'\,\ \'vca\'\)/g /usr/share/vca/www/config.php
	sed -i s/\(\'DB_USER\'\,\ \'\'\)/\(\'DB_USER\'\,\ \'vca\'\)/g /usr/share/vca/www/config.php
	sed -i s/\(\'DB_PASS\'\,\ \'\'\)/\(\'DB_PASS\'\,\ \'${PASSWORD}\'\)/g /usr/share/vca/www/config.php
	
	#Create users
	mysql --defaults-file=/etc/mysql/debian.cnf -e "CREATE USER 'vca'@'localhost' IDENTIFIED BY '${PASSWORD}';"
	mysql --defaults-file=/etc/mysql/debian.cnf -e "GRANT ALL PRIVILEGES ON vca. * TO 'vca'@'localhost';"
	
	service mysql restart

	if [ "${SERVER}" == "1" ] ; then 
		service apache2 restart
	elif [ "${SERVER}" == "2" ] ; then 
		service nginx restart
		service php5-fpm restart
	fi

	#Import Daemon config
	if [ "${DAEMON}" == "1" ] && [ "${DAEMON_KEY}" != "" ] ; then
		mysql -u vca -p${PASSWORD} -b vca -e "INSERT INTO server (server_name, server_address, server_key) VALUES ('Server', '127.0.0.1', '${DAEMON_KEY}')"
	fi
fi

echo "Installation is complete, may be you will need to reboot your server"
