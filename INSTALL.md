# Virtual Control Admin Installation


Table of contents:

1. Introduction
2. Files
3. Install daemon
4. Install panel
5. Uninstallation


## 1. Introduction


You can install VCA directly or by git. The two part of VCA can be installed
in different servers. For the moment, update is not supported.


## 2. Files


Copy VCA into /usr/share/

```bash
cd /usr/share
git clone https://github.com/V-Paranoiaque/vca.git vca
```


## 3. Install daemon

Go to /usr/share/vca/conf

On Centos 6 :
```bash
bash vca-daemon-centos6.bash
```

On Debian 7 (wheezy) :
```bash
bash vca-daemon-debian7.bash
```

Define your secret key in /usr/share/vca/daemon/vcakey.conf

Run VCA daemon in a screen
```bash
cd /usr/share/vca/daemon
screen python3 vcadaemon.py 
```


## 4. Install panel


You need apache or nginx, PHP, MySQL and gettext.
Use apache or nginx conf file from /usr/share/vca/conf to configure your access.
Create your database and import /usr/share/vca/db/create
Create database user and configure his rights.
Modify /usr/share/vca/www/config.php and define database access.


## 5. Uninstallation


To uninstall vca, remove /usr/share/vca and delete the database.
```bash
rm -rf /usr/share/vca
```
