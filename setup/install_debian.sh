#!/bin/bash

echo " --  Install script is for debian 12 - untested and is WIP  --"
exit
cd /var/www/html/server/setup/

echo "Installing packages..."
apt -y install apache2 mariadb-server php certbot

echo "Settings up Mariadb..."
cp -f 50-server.cnf /etc/mysql/mariadb.conf/
systemclt enable mariadb
systemclt restart mariadb

echo "Fetching latest database backup"
cd /var/www/html
echo "Please enter your github passkey:"
read passkey
git clone https://$passkey@github.com/hardinfo2/serverDB
cd serverDB
zcat hardinfo2.sql.tar.gz |mysql

echo "add user hardinfo,hardinfo"
echo "CREATE USER 'hardinfo'@'127.0.0.1' IDENTIFIED BY 'hardinfo';" | mysql
echo "GRANT DELETE, EXECUTE, INSERT, LOCK TABLES, UPDATE ON hardinfo.* TO 'hardinfo'@'127.0.0.1';" | mysql
echo "FLUSH PRIVILEGES;" | mysql
cd /var/www/html/server/setup/

echo "Settings up Apache2..."
cp -f hardinfo2.conf /etc/apache2/sites-avaiable/
systemctl enable apache2
systemctl stop apache2
a2ensite hardinfo2

echo "Installing certificate from LetsEncrypt"
certbot certonly --standalone -D hardinfo2.org -D www.hardinfo2.org -D api.hardinfo2.org
systemctl restart apache2

echo "Setting up cron tasks..."
cat crontab >>/etc/crontab
systemctl restart cron
