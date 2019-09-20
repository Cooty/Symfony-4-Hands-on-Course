#!/usr/bin/env bash
# Remove symlink
sudo rm -R /var/www/micropost_old && \
sudo cp -R /var/www/micropost_current /var/www/micropost_old/ && \
sudo rm /var/www/micropost && \
sudo rm -R /var/www/micropost_current && \
# Create symlink to older version && \
sudo ln -s /var/www/micropost_old /var/www/micropost