#!/bin/bash

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
set_env_var "SSH_DIR" "$HOME/.ssh"
set_env_var "GIT_CONFIG" "$HOME/.gitconfig"
## Add directory to git safe list to avoid permission issues 
git config --global --get-all safe.directory | grep -qx "/home/dev/code" \
  || git config --global --add safe.directory /home/dev/code


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

## Install composer dependencies
docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 composer install

docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 php artisan settings:system-start

docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 php artisan storage:link

docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 chmod 600 secrets/oauth/*.key

## install and build docker dependencies
docker exec -it --user $(id -u):$(id -g) ops-dev-app-1 npm install && npm run dev

## Clean up unused Docker images
docker image prune -f

echo "[INFO] Deployment completed."
docker logs -f --tail=1000 --timestamps ops-dev-app-1
