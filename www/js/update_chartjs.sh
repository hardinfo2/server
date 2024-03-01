#/bin/bash
VER=4.4.1
SERVER=cdn.jsdelivr.net/npm/chart.js@$VER/dist
#SERVER=cdnjs.cloudflare.com/ajax/libs/Chart.js/$VER

echo UPDATING chart.js TO VERSION=$VER

#remove old
rm -f helpers*
rm -f chart*

#get new
wget https://$SERVER/chart.js
wget https://$SERVER/chart.min.js
wget https://$SERVER/chart.umd.js
wget https://$SERVER/chart.umd.min.js
wget https://$SERVER/helpers.js
wget https://$SERVER/helpers.min.js
wget https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels
mv chartjs-plugin-datalabels chartjs-plugin-datalabels.js
