#!/bin/bash

if [ ! -d "/usr/share/vca/www" ] ; then 
	echo "VCA is not present !"
	exit 0
fi

if grep -q CentOS /etc/issue; then 
	OS="Centos"

	if grep -q 6. /etc/issue; then
		DAEMON=1
	else
		DAEMON=0
	fi
	PANEL=1
elif grep -q Fedora /etc/issue; then 
	OS="Centos"
	DAEMON=0
	PANEL=1
elif grep -q Debian /etc/issue; then 
	OS="Debian"

	#Wheezy
	if grep -q 7 /etc/issue; then
		DAEMON=1
	else
		DAEMON=0
	fi
	PANEL=1
elif grep -q Raspbian /etc/issue; then 
	OS="Debian"
	DAEMON=0
	PANEL=1
elif grep -q Ubuntu /etc/issue; then 
	OS="Debian"
	DAEMON=0
	PANEL=1
elif grep -q CentOS /etc/os-release; then 
	OS="Centos"
	DAEMON=0
	PANEL=1
else
	echo "Error, you use an unsupported OS"
	exit 0;
fi

ARCH=$(uname -m)

if [ "${DAEMON}" == "1" ] && [ "${ARCH}" = "x86_64" ] ; then
	while [ "${INSTALL_DAEMON}" != "y" ] && [ "${INSTALL_DAEMON}" != "n" ]; do
		echo "Would you like to install VCA daemon ? y/n"
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
		echo "Would you like to install VCA panel ? y/n"
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

if [ "${OS}" == "Centos" ] ; then 
	#Disable SELinux
	sed -i s/SELINUX=enforcing/SELINUX=disabled/g /etc/selinux/config
	sed -i s/SELINUX=permissive/SELINUX=disabled/g /etc/selinux/config

	#Install wget
	yum install wget -y
fi

if [ "${DAEMON}" == "1" ] ; then
	#For Openvz
	sed -i 's/kernel.sysrq = 0/kernel.sysrq = 1/g' /etc/sysctl.conf
	sed -i 's/net.ipv4.ip_forward = 0/net.ipv4.ip_forward = 1/g' /etc/sysctl.conf

	if [ "${OS}" == "Centos" ] ; then 
		# OpenVZ repo
		wget -O /etc/yum.repos.d/openvz.repo http://download.openvz.org/openvz.repo
		rpm --import http://download.openvz.org/RPM-GPG-Key-OpenVZ
		
		# SCL repo
		yum install centos-release-SCL -y
		
		# All needed packages
		yum install python33-python python33-python-devel python33-python-setuptools gcc gmp-devel screen vzkernel vzctl -y
		
		# Python install
		cd /root/

		#PATH
		if ! grep -q python33 /root/.bashrc ; then 
			echo "" >> /root/.bashrc
			echo "export PATH=$PATH:/opt/rh/python33/root/usr/bin" >> /root/.bashrc
			echo "export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/opt/rh/python33/root/usr/lib64" >> /root/.bashrc
		fi
		
		source /root/.bashrc
		
		easy_install-3.3 pip
		pip3 install pycrypto
		
	elif [ "${OS}" == "Debian" ] ; then 
		# OpenVZ repo
		echo "deb http://download.openvz.org/debian wheezy main" > /etc/apt/sources.list.d/openvz-rhel6.list
		echo "#deb http://download.openvz.org/debian wheezy-test main" >> /etc/apt/sources.list.d/openvz-rhel6.list
		wget http://ftp.openvz.org/debian/archive.key
		apt-key add archive.key
		rm -f archive.key
		apt-get update
		
		# All needed packages
		apt-get install python3 python3-crypto python3-dev vzkernel vzctl screen -y
	fi

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
fi

if [ "${PANEL}" == "1" ] ; then 
	# 1 apache
	# 2 nginx
	SERVER=0
	while [ ${SERVER} != 1 ] && [ ${SERVER} != 2 ]; do
		echo "Which server do you use ?"
		echo "1 apache"
		echo "2 nginx"
		read SERVER
	done
	
	DOMAIN=""
	while [ "${DOMAIN}" == "" ]; do
		echo "Enter your domain"
		read DOMAIN
	done
	
	if [ "${OS}" == "Centos" ] ; then 
		yum install epel-release -y
		
		#apache
		if [ "${SERVER}" == "1" ] ; then 
			yum install httpd php mod_ssl -y
			chkconfig --levels 235 httpd on
			
			#Configure
			cp /usr/share/vca/conf/vca-apache-example.conf /etc/httpd/conf.d/vca-panel.conf
			sed -i s/vca.example.com/${DOMAIN}/g /etc/httpd/conf.d/vca-panel.conf
		#nginx
		elif [ "${SERVER}" == "2" ] ; then 
			yum install nginx php-fpm -y
			chkconfig --levels 235 nginx on
			chkconfig --levels 235 php-fpm on
			
			if [ ! -f /etc/nginx/conf.d/php5-fpm.conf ] ; then 
				echo "upstream php5-fpm-sock {" > /etc/nginx/conf.d/php5-fpm.conf
				echo "	server unix:/var/run/php5-fpm.sock;" >> /etc/nginx/conf.d/php5-fpm.conf
				echo "}" >> /etc/nginx/conf.d/php5-fpm.conf
			fi
			
			#Configure
			cp /usr/share/vca/conf/vca-nginx-example.conf /etc/nginx/conf.d/vca-panel.conf
			sed -i s/vca.example.com/${DOMAIN}/g /etc/nginx/conf.d/vca-panel.conf
			sed -i s/127.0.0.1:9000/\\\/var\\\/run\\\/php5-fpm.sock/g /etc/php-fpm.d/www.conf
		fi
		
		yum install php-mysql php-mcrypt gettext openssl -y
		
		# Iptables
		iptables -A INPUT -p tcp --dport 80 -j ACCEPT
		iptables -A INPUT -p tcp --dport 443 -j ACCEPT
		/sbin/service iptables save
		
		# MySQL
		if [ ! -f /usr/bin/mysql ] ; then
			yum install mysql-server -y
			mysql_secure_installation
		fi
		chkconfig --levels 235 mysqld on
	elif [ "${OS}" == "Debian" ] ; then 
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
			
			#Configure
			rm -f /etc/nginx/sites-enabled/vca-panel.conf
			cp /usr/share/vca/conf/vca-nginx-example.conf /etc/nginx/sites-available/vca-panel.conf
			sed -i s/vca.example.com/${DOMAIN}/g /etc/nginx/sites-available/vca-panel.conf
			ln -s /etc/nginx/sites-available/vca-panel.conf /etc/nginx/sites-enabled/vca-panel.conf
		fi
		
		apt-get install mysql-server php5-mysql php5-mcrypt gettext openssl -y
		
		#MySQL
		apt-get install mysql-server -y
	fi
	
	#Template cache
	chmod 777 /usr/share/vca/www/templates_c
	
	#Cron
	cp /usr/share/vca/conf/vca.cron /etc/cron.d/
	
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
	
	if [ "${OS}" == "Centos" ] ; then 
	
		stty -echo
		read -p "Enter your MySQL root password: " MYSQL_PASSWORD; echo
		stty echo
		
		#Import DB
		mysql -u root -p${MYSQL_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS vca"
		
		for f in /usr/share/vca/db/create/*.sql; do
			mysql -u root -p${MYSQL_PASSWORD} vca < "$f"
		done
	elif [ "${OS}" == "Debian" ] ; then 
		#Import DB
		mysql --defaults-file=/etc/mysql/debian.cnf -e "CREATE DATABASE IF NOT EXISTS vca"
		
		for f in /usr/share/vca/db/create/*.sql; do
			mysql --defaults-file=/etc/mysql/debian.cnf vca < "$f"
		done
	fi
	
	PASSWORD=`date +%s | sha256sum | base64 | head -c 32 ; echo`
	
	#DB configuration
	echo "DB configuration"
	sed -i s/\(\'DB_NAME\'\,\ \'\'\)/\(\'DB_NAME\'\,\ \'vca\'\)/g /usr/share/vca/www/config.php
	sed -i s/\(\'DB_USER\'\,\ \'\'\)/\(\'DB_USER\'\,\ \'vca\'\)/g /usr/share/vca/www/config.php
	sed -i s/\(\'DB_PASS\'\,\ \'\'\)/\(\'DB_PASS\'\,\ \'${PASSWORD}\'\)/g /usr/share/vca/www/config.php
	
	if [ "${OS}" == "Centos" ] ; then
		
		#Create users
		mysql -u root -p${MYSQL_PASSWORD} -e "CREATE USER 'vca'@'localhost' IDENTIFIED BY '${PASSWORD}';"
		mysql -u root -p${MYSQL_PASSWORD} -e "GRANT ALL PRIVILEGES ON vca. * TO 'vca'@'localhost';"
		
		if [ "${SERVER}" == "1" ] ; then 
			service httpd restart
		elif [ "${SERVER}" == "2" ] ; then 
			service nginx restart
		fi
	elif [ "${OS}" == "Debian" ] ; then
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
	fi

	#Import Daemon config
	if [ "${DAEMON}" == "1" ] && [ "${DAEMON_KEY}" != "" ] ; then
		mysql -u vca -p${PASSWORD} -b vca -e "INSERT INTO server (server_name, server_address, server_key) VALUES ('Server', '127.0.0.1', '${DAEMON_KEY}')"
	fi
fi

echo "Installation is complete, may be you will need to reboot your server"
