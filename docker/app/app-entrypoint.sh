#!/usr/bin/env sh
set -e

STARTUP_DELAY="${STARTUP_DELAY:-0}";

if [[ "$STARTUP_DELAY" -gt 0 ]]; then
  echo "[INFO] Wait $STARTUP_DELAY seconds before start ..";
  sleep "$STARTUP_DELAY";
fi;

exec "$@";
