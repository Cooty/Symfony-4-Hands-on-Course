#!/usr/bin/env bash
sudo rm -R /var/www/micropost && \
sudo ln -s /var/www/micropost_current /var/www/micropost && \
cd /var/www/micropost && \
sudo APP_ENV=$APP_ENV DATABASE_URL=$DATABASE_URL php bin/console:migrations:migrate --no-interaction && \
sudo chown -R www-data:www-data /var/www/micropost_current && \
sudo chown -h www-data:www-data /var/www/micropost && \
sudo service apache2 restart