#!/bin/bash

if [ ! -d "/usr/share/vca/www" ] ; then 
    echo "VCA is not present !"
    exit 0
fi

#Install Daemon
DAEMON=1
#Install panel
PANEL=1
#Server arch
ARCH=$(uname -m)

if [ ! -f /usr/bin/yum ] ; then
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

while [ "${INSTALL_PANEL}" != "y" ] && [ "${INSTALL_PANEL}" != "n" ]; do
    echo "Would you like to install VCA panel? y/n"
    read INSTALL_PANEL
done

if [ "${INSTALL_PANEL}" == "n" ] ; then
    PANEL=0
fi

if [ "${PANEL}" == "0" ] && [ "${DAEMON}" == "0" ] ; then
    echo "Nothing to do :("
    exit 0;
fi

if [ -f /etc/selinux/config ] ; then
    #Disable SELinux
    sed -i s/SELINUX=enforcing/SELINUX=disabled/g /etc/selinux/config
    sed -i s/SELINUX=permissive/SELINUX=disabled/g /etc/selinux/config
fi

#Install Epel repo
yum install epel-release -y

if [ "${DAEMON}" == "1" ] ; then
    #For Openvz
    sed -i 's/kernel.sysrq = 0/kernel.sysrq = 1/g' /etc/sysctl.conf
    sed -i 's/net.ipv4.ip_forward = 0/net.ipv4.ip_forward = 1/g' /etc/sysctl.conf
    
    # OpenVZ repo
    cp /usr/share/vca/conf/openvz.repo /etc/yum.repos.d/
    
    yum install openvz-release -y --nogpgcheck
    rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-Virtuozzo-7
    
    # SCL repo
    #yum install centos-release-scl -y
    
    # All needed packages
    yum install clamav fuse gcc ploop prl-disp-service python34 python34-devel python34-setuptools vzctl vzkernel -y
    
    # Python install
    cd /root/
    
    easy_install-3.4 pip
    pip3 install dropbox pycrypto
    
    if [ ! -f /usr/share/vca/daemon/vca.cfg ] ; then 
        DAEMON_KEY=`date +%s | sha512sum | base64 | head -c 32 ; echo`
        echo "[DEFAULT]" >> /usr/share/vca/daemon/vca.cfg
        echo "key = ${DAEMON_KEY}" >> /usr/share/vca/daemon/vca.cfg
        echo "port = 10000" >> /usr/share/vca/daemon/vca.cfg
        echo "host = 0.0.0.0" >> /usr/share/vca/daemon/vca.cfg
    fi
    
    mkdir -p /root/.ssh
    chmod 700 /root/.ssh
    
    if [ ! -f /root/.ssh/id_rsa ] ; then
        ssh-keygen -t rsa -f /root/.ssh/id_rsa -P ""
    fi
    
    #Startup script
    cp /usr/share/vca/conf/vcadaemon.service /usr/lib/systemd/system/vcadaemon.service
    cp /usr/share/vca/conf/vcadaemon.chkconfig /etc/init.d/vcadaemon
    chmod 755 /etc/init.d/vcadaemon
    systemctl enable vcadaemon
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
    
    #apache
    if [ "${SERVER}" == "1" ] ; then 
        yum install httpd php mod_ssl -y
        systemctl enable httpd
        
        #Configure
        cp /usr/share/vca/conf/vca-apache-example.conf /etc/httpd/conf.d/vca-panel.conf
        sed -i s/vca.example.com/${DOMAIN}/g /etc/httpd/conf.d/vca-panel.conf
    #nginx
    elif [ "${SERVER}" == "2" ] ; then 
        yum install nginx php-fpm -y
        systemctl enable nginx
        systemctl enable php-fpm
        
        if [ ! -f /etc/nginx/conf.d/php5-fpm.conf ] ; then 
            echo "upstream php5-fpm-sock {" > /etc/nginx/conf.d/php5-fpm.conf
            echo "  server unix:/var/run/php5-fpm.sock;" >> /etc/nginx/conf.d/php5-fpm.conf
            echo "}" >> /etc/nginx/conf.d/php5-fpm.conf
        fi
        
        #Configure
        cp /usr/share/vca/conf/vca-nginx-example.conf /etc/nginx/conf.d/vca-panel.conf
        sed -i s/vca.example.com/${DOMAIN}/g /etc/nginx/conf.d/vca-panel.conf
        sed -i s/127.0.0.1:9000/\\\/var\\\/run\\\/php5-fpm.sock/g /etc/php-fpm.d/www.conf
    fi
    
    yum install php-curl php-mysql php-mcrypt php-pecl-apcu gettext openssl crontabs -y
    
    # Iptables
    if [ -f /usr/bin/firewall-cmd ] ; then
        firewall-cmd --zone=public --add-port=80/tcp --permanent
        firewall-cmd --zone=public --add-port=443/tcp --permanent
        firewall-cmd --reload
    fi
    
    # MariaDB
    if [ ! -f /usr/bin/mariadb ] ; then
        yum install mariadb-server -y
        systemctl enable mariadb
        service mariadb start
        sleep 2
        mysql_secure_installation
    fi
    
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
    
    stty -echo
    read -p "Enter your Mariadb root password: " MYSQL_PASSWORD; echo
    stty echo
    
    #Import DB
    mysql -u root -p${MYSQL_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS vca"
    
    for f in /usr/share/vca/db/create/*.sql; do
        mysql -u root -p${MYSQL_PASSWORD} vca < "$f"
    done
    
    PASSWORD=`date +%s | sha256sum | base64 | head -c 32 ; echo`
    
    #DB configuration
    echo "DB configuration"
    sed -i s/\(\'DB_NAME\'\,\ \'\'\)/\(\'DB_NAME\'\,\ \'vca\'\)/g /usr/share/vca/www/config.php
    sed -i s/\(\'DB_USER\'\,\ \'\'\)/\(\'DB_USER\'\,\ \'vca\'\)/g /usr/share/vca/www/config.php
    sed -i s/\(\'DB_PASS\'\,\ \'\'\)/\(\'DB_PASS\'\,\ \'${PASSWORD}\'\)/g /usr/share/vca/www/config.php
    
    #Create users
    mysql -u root -p${MYSQL_PASSWORD} -e "CREATE USER 'vca'@'localhost' IDENTIFIED BY '${PASSWORD}';"
    mysql -u root -p${MYSQL_PASSWORD} -e "GRANT ALL PRIVILEGES ON vca. * TO 'vca'@'localhost';"
    
    if [ "${SERVER}" == "1" ] ; then 
        service httpd start
    elif [ "${SERVER}" == "2" ] ; then 
        service nginx start
        service php-fpm start
    fi
    
    #Import Daemon config
    if [ "${DAEMON}" == "1" ] && [ "${DAEMON_KEY}" != "" ] ; then
        mysql -u root -p${MYSQL_PASSWORD} -b vca -e "INSERT INTO server (server_name, server_address, server_key) VALUES ('Server', '127.0.0.1', '${DAEMON_KEY}')"
    fi
fi

echo "Installation is complete, may be you will need to reboot your server"
