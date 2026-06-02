#!/bin/bash
set -e

# 本番サーバー上の配置パス（環境に合わせて変更）
APP_ROOT="/var/www/vhosts/tecono"
BRANCH="main"

DOCKER_COMPOSE="docker compose"
COMPOSE_FILE="$APP_ROOT/docker-compose.prod.yml"

cd "$APP_ROOT"

echo ">>> Git fetch / pull ($BRANCH)"
git fetch origin
git checkout "$BRANCH"
git pull origin "$BRANCH"

echo ">>> Pull WordPress Docker image"
$DOCKER_COMPOSE -f "$COMPOSE_FILE" pull wordpress

echo ">>> Start WordPress container (recreate)"
$DOCKER_COMPOSE -f "$COMPOSE_FILE" up -d --force-recreate wordpress

echo ">>> Deploy finished successfully."
