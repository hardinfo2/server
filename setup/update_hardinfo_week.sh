#!/bin/bash
#create downloads from github
curl -s -L https://hardinfo2.org/github?downloadlist |grep "hardinfo2_\|hardinfo2-"|grep -v debug \
     |sed ':a;N;$!ba;s/">\n/">/g' \
     |grep -o '<a href.*span>' \
     |sed 's/" rel="nofollow" data-turbo="false" data-view-component="true" class="Truncate">/">/g' \
     |sed 's/href="/href="https:\/\/github.com/g' \
     |sed 's/<span data-view-component="true" class="Truncate-text text-bold">//g' \
     |sed 's/<\/span>/<a><br>/g' \
     |sed 's/    //g' \
     >/var/www/html/server/www/downloads1.ids
#exit
rm -f /var/www/html/server/www/downloads.ids
echo "<h1>Debian/APT Based</h1>sudo apt install ./hardinfo2_FULLNAME<br><br>" >/var/www/html/server/www/downloads.ids
cat /var/www/html/server/www/downloads1.ids |grep '2_2'>> /var/www/html/server/www/downloads.ids
echo "<h1>Fedora/DNF/RPM Based</h1>sudo dnf install ./hardinfo2-FULLNAME<br><br>" >>/var/www/html/server/www/downloads.ids
cat /var/www/html/server/www/downloads1.ids |grep '2-2'|grep -v SUSE|grep -v PCL|grep -v Atomic>> /var/www/html/server/www/downloads.ids
echo "<h1>Fedora/OSTREE/RPM Based</h1>sudo rpm-ostree install ./hardinfo2-FULLNAME (And reboot)<br><br>" >>/var/www/html/server/www/downloads.ids
cat /var/www/html/server/www/downloads1.ids |grep '2-2'|grep Atomic>> /var/www/html/server/www/downloads.ids
echo "<h1>OpenSuse/ZYPPER/RPM Based</h1>sudo zypper --no-gpg-checks install ./hardinfo2-FULLNAME<br><br>" >>/var/www/html/server/www/downloads.ids
cat /var/www/html/server/www/downloads1.ids |grep '2-2'|grep SUSE>> /var/www/html/server/www/downloads.ids
echo "<h1>PCLinuxOS/APT/RPM Based</h1>sudo apt install ./hardinfo2-FULLNAME<br><br>" >>/var/www/html/server/www/downloads.ids
cat /var/www/html/server/www/downloads1.ids |grep '2-2'|grep PCL>> /var/www/html/server/www/downloads.ids
echo "<h1>Arch/PACMAN Based</h1>sudo pacman -U ./hardinfo2-FULLNAME<br><br>" >>/var/www/html/server/www/downloads.ids
cat /var/www/html/server/www/downloads1.ids |grep -v '2_2' |grep -v '2-2' >> /var/www/html/server/www/downloads.ids
exit
rm -f /var/www/html/server/www/downloads1.ids

#wget -O /var/www/html/server/www/pci.ids https://pci-ids.ucw.cz/v2.2/pci.ids
#wget -O /var/www/html/server/www/usb.ids http://www.linux-usb.org/usb.ids
wget -O /var/www/html/server/www/pci.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/pci.ids
wget -O /var/www/html/server/www/usb.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/usb.ids
wget -O /var/www/html/server/www/arm.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/arm.ids
wget -O /var/www/html/server/www/cpuflags.json https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/cpuflags.json
wget -O /var/www/html/server/www/edid.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/edid.ids
wget -O /var/www/html/server/www/ieee_oui.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/ieee_oui.ids
wget -O /var/www/html/server/www/kernel-module-icons.json https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/kernel-module-icons.json
wget -O /var/www/html/server/www/sdcard.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/sdcard.ids
wget -O /var/www/html/server/www/vendor.ids https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/data/vendor.ids

#create credits from github
curl -s https://raw.githubusercontent.com/hardinfo2/hardinfo2/master/shell/callbacks.c|grep \",|grep -v ardinfo|grep -v pixmaps|grep -v shell|grep -v NULL|grep -v FALSE|grep -v VERSION|grep -v latest| grep -v key_file |sed 's/",/<br>/g' |sed 's/"//g' |sed '/\:/ s/^/\<b>/' |sed '/\:/ s/$/\<\/b>/' >/var/www/html/server/www/credits.ids

#backup to github - serverDB
cd /var/www/html/serverDB
mysqldump -h127.0.0.1 -uhardinfo -phardinfo hardinfo |gzip -c > hardinfo2.sql.tgz
git commit -a -m "Database Backup"
git push


