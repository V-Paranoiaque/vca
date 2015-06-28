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
Don't forget to disable SELINUX.

## 2. Files


Copy VCA into /usr/share/

```bash
cd /usr/share
git clone https://github.com/V-Paranoiaque/vca.git vca
```

## 3. Install daemon


Supported systems
* Centos 6
* Debian 7
* Ubuntu 12.04 and 14.04

```bash
cd /usr/share/vca/scripts
bash vca-linux.bash
```
Answer yes to the question "Would you like to install VCA daemon?".


To run VCA daemon:
```bash
service vcadaemon start
```

## 4. Install panel


Supported systems
* Centos 6, 7
* Debian 7, 8
* Fedora
* Raspbian
* Ubuntu 12.04, 14.04 and 15.04

Go to /usr/share/vca/scripts and run the installation script.
```bash
cd /usr/share/vca/scripts
bash vca-linux.bash
```

Answer yes to the question "Would you like to install VCA panel?".

If you want to install VCA panel by your self, you need apache or nginx, PHP, MySQL and gettext.
Use apache or nginx conf file from /usr/share/vca/conf to configure your access.
Create your database and import /usr/share/vca/db/create
Create database's user and configure his rights.
Modify /usr/share/vca/www/config.php and define database access.
Generate .mo language files in lang/ with gettext

## 5. Uninstallation


To uninstall vca, remove /usr/share/vca, startup script and delete the database.
```bash
rm -rf /usr/share/vca
```
