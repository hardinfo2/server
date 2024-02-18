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

#TODO
#backup to github - serverDB
