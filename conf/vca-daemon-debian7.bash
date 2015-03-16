#!/bin/bash

if [ ! -d "/usr/share/vca/daemon" ];then
	echo "VCA is not present !";
	exit 0
fi

# OpenVZ repo
cat << EOF > /etc/apt/sources.list.d/openvz-rhel6.list
deb http://download.openvz.org/debian wheezy main
# deb http://download.openvz.org/debian wheezy-test main
EOF
wget http://ftp.openvz.org/debian/archive.key
apt-key add archive.key
rm -f archive.key

aptitude update

# All needed packages
aptitude install python3 python3-devel python3-crypto python3-dev vzkernel vzctl -y

# Create vcakey.conf
touch /usr/share/vca/daemon/vca.cfg
