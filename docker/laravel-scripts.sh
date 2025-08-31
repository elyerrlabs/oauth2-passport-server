#!/bin/sh

# -----------------------------------------------------------------------------
# Copyright (c) 2025 Elvis Yerel Roman Concha
#
# This file is part of an open source project licensed under the
# "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
#
# You may use, study, modify, and redistribute this file for personal,
# educational, or non-commercial research purposes only.
#
# Commercial use is strictly prohibited without prior written consent
# from the author.
#
# Combining this software with any project licensed for commercial use
# (such as AGPL) is not permitted without explicit authorization.
#
# This software supports OAuth 2.0 and OpenID Connect.
#
# Author Contact: yerel9212@yahoo.es
#
# SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
# -----------------------------------------------------------------------------
 
set -e   

cd /var/www 

echo "⚙️ Running system configuration..."

cp -vf /root/.env /var/www/.env 

find . -type d -exec chmod 750 {} \;
find . -type f -exec chmod 640 {} \;

chmod 400 .env

php artisan settings:system-start

chmod 600 secrets/oauth/*.key

chown -R www-data:www-data .

php artisan storage:link

echo "🚀 Starting PHP-FPM..."
php-fpm83 -D

echo "🌐 Starting Nginx..."
nginx -g "daemon off;" &   

echo "🛠️ Starting Supervisor..."
supervisord -c /etc/supervisord.conf &  

echo "✅ All services started"

tail -f /dev/null