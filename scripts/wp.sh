#!/bin/sh
# WP-CLI no ambiente Docker local, ex.: sh scripts/wp.sh plugin list
exec docker run --rm -i --user 33:33 \
  --volumes-from dija_wordpress --network container:dija_wordpress \
  -e WORDPRESS_DB_HOST=db:3306 -e WORDPRESS_DB_USER=wordpress \
  -e WORDPRESS_DB_PASSWORD=wordpress_password -e WORDPRESS_DB_NAME=wordpress \
  wordpress:cli-2.12.0-php8.5 wp "$@"
