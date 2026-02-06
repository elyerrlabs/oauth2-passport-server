#!/bin/sh

# -----------------------------------------------------------------------------
# OAuth2 Passport Server — a centralized, modular authorization server
# implementing OAuth 2.0 and OpenID Connect specifications.

# Copyright (c) 2026 Elvis Yerel Roman Concha

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU Affero General Public License for more details.
 
# You should have received a copy of the GNU Affero General Public License
# along with this program. If not, see <https://www.gnu.org/licenses/>.

# Author: Elvis Yerel Roman Concha
# Contact: yerel9212@yahoo.es

# SPDX-License-Identifier: AGPL-3.0-or-later
# -----------------------------------------------------------------------------
 
set -e   

cd /var/www 

echo "⚙️ Running system configuration..."

cp -vf /root/.env /var/www/.env 

find . -type d -exec chmod 750 {} \;
find . -path "./third-party" -prune -o -type f -exec chmod 640 {} +

chmod 664 resources/views/layouts/editable/*.blade.php
chmod -R 664 public/*
chmod 400 .env

php artisan settings:system-start

chmod 600 secrets/oauth/*.key

chown -R www-data:www-data .

chown -R www-data:www-data /var/lib/nginx

php artisan storage:link

echo "🚀 Starting PHP-FPM..."
php-fpm84 -D

echo "🌐 Starting Nginx..."
nginx -g "daemon off;" &   

echo "🛠️ Starting Supervisor..."
supervisord -c /etc/supervisord.conf &  

echo "✅ All services started"

tail -f /dev/null