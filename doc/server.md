Server
--------

The server is a standard LAMP server - Linux Apache2 MariaDB PHP

**URLS**

www.hardinfo2.org, hardinfo2.org, api.hardinfo2.org can be access be IPv4 or IPv6.


**Rewrite**

Most non found requests are pointed to index.php via below settings:
 - RewriteEngine on
 - RewriteCond %{REQUEST_FILENAME} !-f
 - RewriteCond %{REQUEST_FILENAME} !-d
 - RewriteCond %{REQUEST_URI} !\.(?:jpe?g|ids|gif|bmp|png|css|js)$ [NC]
 - RewriteRule (.*) /index.php/$1 [L]

It ignores unfound images, css, js and ids (Our Blobs).
