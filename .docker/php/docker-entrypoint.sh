#!/bin/sh
set -e

 # shellcheck disable=SC2120
 webapp() {
  echo "Starting PHP-FPM"
  php-fpm
}

case "$1" in
  "webapp")
      webapp
  ;;
  *)
    exec "$@"
  ;;
esac
