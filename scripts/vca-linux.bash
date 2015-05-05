#!/bin/bash
#
# Script tested on :
# Centos 6, 7
# Debian 7
# Ubuntu 12.04, 14.04

if [ ! -d "/usr/share/vca/www" ] ; then 
	echo "VCA is not present !"
	exit 0
fi

SCRIPT=""
#Centos/Redhat
if [ -f /etc/redhat-release ] ; then 
	VERSION=`rpm -q --qf "%{VERSION}" $(rpm -q --whatprovides redhat-release)`
	if [ "${VERSION}" == "6" ] ; then 
		SCRIPT="vca-centos6.bash"
	elif [ "${VERSION}" == "7" ] ; then 
		SCRIPT="vca-centos7.bash"
	fi
#Debian/Ubuntu
elif [ -f /etc/debian_version ] ; then 
	DISTRIB=`lsb_release -is`
	VERSION=`lsb_release -rs`
	if [ "${DISTRIB}" == "Ubuntu" ] ; then
		if [ "${VERSION}" == "12.04" ] ; then
			SCRIPT="vca-ubuntu12.04.bash"
		elif [ "${VERSION}" == "14.04" ] ; then
			SCRIPT="vca-ubuntu14.04.bash"
		else
			SCRIPT="vca-ubuntulast.bash"
		fi
	else
		case $VERSION in
			7.*) SCRIPT="vca-debian7.bash" ;;
			8.*) SCRIPT="vca-debian8.bash" ;;
			9.*) SCRIPT="vca-debian8.bash" ;;
			*) SCRIPT="vca-debian7.bash" ;;
		esac
	fi
fi

#Detection by package type
if [ "${SCRIPT}" == "" ] ; then
	if [ -f /usr/bin/apt-get ] ; then
		SCRIPT="vca-debian7.bash"
	elif [ -f /usr/bin/yum ] ; then
		SCRIPT="vca-centos6.bash"
	fi
fi

if [ "${SCRIPT}" == "" ] ; then
	echo "Error, you use an unsupported OS"
	exit 0;
else
	bash /usr/share/vca/scripts/${SCRIPT}
fi
