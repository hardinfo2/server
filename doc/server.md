Server
--------

The server is a standard LAMP server - Linux Apache2 MariaDB PHP

**URLS**

- www.hardinfo2.org
- hardinfo2.org
- api.hardinfo2.org

Can be access by IPv4 or IPv6.

**SSL**
- letsencrypt is used for certificated - free of charge.
- www.hardinfo2.org and hardinfo2.org has HSTS and redirects to HTTP->HTTPS
- api.hardinfo2.org does **not** use HSTS and does **not** redirects to HTTPS
- HTTP/HTTPS is possible to all domain names.

**Rewrites on Server**
 
Rewrite api to /api/gethtmltables/ -> /api/gethtmltables.php
 - RewriteCond $1.php -f
 - RewriteRule ^(.*?)/?$ $1.php [L]

Non found, non images,css,js,ids are pointed to /index.php
 - RewriteCond %{REQUEST_FILENAME} !-f
 - RewriteCond %{REQUEST_FILENAME} !-d
 - RewriteCond %{REQUEST_URI} !\.(?:jpe?g|ids|gif|bmp|png|css|js)$ [NC]
 - RewriteRule (.*) /index.php/$1 [L]
