#!/bin/bash
wget -O /var/www/html/server/www/pci.ids https://pci-ids.ucw.cz/v2.2/pci.ids
wget -O /var/www/html/server/www/usb.ids http://www.linux-usb.org/usb.ids
wget -O /var/www/html/server/www/arm.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/arm.ids
wget -O /var/www/html/server/www/cpuflags.json https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/cpuflags.json
wget -O /var/www/html/server/www/edid.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/edid.ids
wget -O /var/www/html/server/www/ieee_oui.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/ieee_oui.ids
wget -O /var/www/html/server/www/kernel-module-icons.json https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/kernel-module-icons.json
wget -O /var/www/html/server/www/sdcard.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/sdcard.ids
wget -O /var/www/html/server/www/vendor.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/vendor.ids

#create credits from github
curl -s https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/shell/callbacks.c|grep \",|grep -v ardinfo|grep -v shell|grep -v NULL|grep -v FALSE|grep -v key_file |sed 's/",/<br>/g' |sed 's/"//g' |sed '/\:/ s/^/\<b>/' |sed '/\:/ s/$/\<\/b>/' >/var/www/html/server/www/credits.ids

#backup to github - serverDB
cd /var/www/html/serverDB
mysqldump -h127.0.0.1 -uhardinfo -phardinfo hardinfo |gzip -c > hardinfo2.sql.tgz
git commit -a -m "Database Backup"
git push
