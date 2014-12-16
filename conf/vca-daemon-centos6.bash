#!/bin/bash

if [ ! -d "/usr/share/vca/daemon" ];then
	echo "VCA is not present !";
	exit 0
fi

yum install wget -y

# OpenVZ repo
wget -O /etc/yum.repos.d/openvz.repo http://download.openvz.org/openvz.repo
rpm --import http://download.openvz.org/RPM-GPG-Key-OpenVZ

# SCL repo
yum install centos-release-SCL -y

# All needed packages
yum install python33-python python33-python-devel python33-python-setuptools gcc gmp-devel vzkernel vzctl -y

# Python install
scl enable python33 bash
easy_install-3.3 pip
pip3 install pycrypto

# Create vcakey.conf
touch /usr/share/vca/daemon/vcakey.conf
