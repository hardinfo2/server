#!/bin/bash
cd /var/www/html/server
git pull

#update cpu db from benchmarks
/usr/bin/php /var/www/html/server/setup/update_cpudb_from_benchmarks.php
