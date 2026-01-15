#!/bin/bash

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
COMPOSE_FILE="docker-compose-dev.yml"
ENV_FILE=".env"
 
# -----------------------------------------------------------------------------
# Detect host UID and GID and persist them into .env
# -----------------------------------------------------------------------------
set_env_var() {
    local key="$1"
    local value="$2"

    if grep -q "^${key}=" "$ENV_FILE"; then 
        sed -i "s|^${key}=.*|${key}=${value}|" "$ENV_FILE"
    else
        tail -c1 "$ENV_FILE" | read -r _ || echo >> "$ENV_FILE"
        echo "${key}=${value}" >> "$ENV_FILE"
    fi
}

HOST_UID=$(id -u)
HOST_GID=$(id -g)

set_env_var "UID" "$HOST_UID"
set_env_var "GID" "$HOST_GID"
set_env_var "APP_ENV" "dev"

# Required environment variables
required_keys=(
    DB_CONNECTION
    DB_HOST
    DB_PORT
    DB_DATABASE
    DB_USERNAME
    DB_PASSWORD 
)
 
for key in "${required_keys[@]}"; do
    if ! grep -q "^$key=" "$ENV_FILE"; then
        echo "[ERROR] Missing required environment variable: $key"
        exit 1
    fi

    value=$(grep "^$key=" "$ENV_FILE" | cut -d '=' -f2-)
    if [[ -z "$value" ]]; then
        echo "[ERROR] Environment variable $key has no value. Please fill it in $ENV_FILE"
        exit 1
    fi
done

echo "[INFO] Stopping containers..."
docker compose -f "$COMPOSE_FILE" down

echo "[INFO] Starting containers..."
docker compose -f "$COMPOSE_FILE" up -d --build

## Add directory to git safe list to avoid permission issues
docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 git config --global --add safe.directory /var/www

## Install composer dependencies
docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 composer install

## install and build docker dependencies
docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 npm install && npm run dev

docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 php artisan settings:system-start

docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 php artisan storage:link

echo "[INFO] Deployment completed."
docker logs -f --tail=1000 --timestamps ops-dev-app-1
