This is instructions to deploy a new server
-------------------------------------------

* install a distro (for now debian) with IPv4 and IPv6 and open firewall for port 80, 443

* maintainers change the DNS setup to point to the new server

* install git and checkout https://github.com/hardinfo2/server to /var/www/html/server

* run as root the ..server/setup/install_debian.sh (Initial: debian, others to come)
  - This will install and configure:
  - apache2
  - mariadb
  - php
  - cronjobs
  - letsencrypt SSL
  - Get the latest database backup from github

* Done - check server is working