Server
--------

The server is a standard LAMP server - Linux Apache2 MariaDB PHP

**URLS**

www.hardinfo2.org, hardinfo2.org, api.hardinfo2.org can be access be IPv4 or IPv6.

**Rewrites on Server**
 
Rewrite api to /api/gethtmltables -> /api/gethtmltables.php
 - RewriteCond $1.php -f
 - RewriteRule ^(.*?)/?$ $1.php [L]

Non found, non images,css,js,ids are pointed to /index.php
 - RewriteCond %{REQUEST_FILENAME} !-f
 - RewriteCond %{REQUEST_FILENAME} !-d
 - RewriteCond %{REQUEST_URI} !\.(?:jpe?g|ids|gif|bmp|png|css|js)$ [NC]
 - RewriteRule (.*) /index.php/$1 [L]
