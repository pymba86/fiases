#!/usr/bin/env sh

# Exit in case of error
set -e

TAG=${TAG:-latest} \
source ./scripts/build.sh

docker-compose -f docker-stack.yml push
