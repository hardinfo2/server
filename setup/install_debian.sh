#!/bin/bash

echo " --  Install script is untested and is WIP  --"
exit
cd /var/www/html/server/setup/

echo "Installing packages..."
apt -y install apache2 mariadb-server php certbot

echo "Settings up Mariadb..."
cp -f 50-server.cnf /etc/mysql/mariadb.conf/
systemclt enable mariadb
systemclt restart mariadb
#add user hardinfo,hardinfo

echo "Fetching latest database backup"
cd /var/www/html
git clone https://github.com/hardinfo2/serverDB
cd serverDB
cat hardinfo2.sql |mysql -u root
cd /var/www/html/server/setup/

echo "Settings up Apache2..."
cp -f hardinfo2.conf /etc/apache2/sites-avaiable/
systemctl enable apache2
systemctl stop apache2
a2ensite hardinfo2

echo "Installing certificate from LetsEncrypt"
certbot --cert-only --standalone -D hardinfo2.org -D www.hardinfo2.org -D api.hardinfo2.org
systemctl restart apache2

echo "Setting up cron tasks..."
cat crontab >>/etc/crontab
systemctl restart cron
