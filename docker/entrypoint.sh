#!/bin/sh
# ToWork container entrypoint.
#   CONTAINER_ROLE=app        PHP-FPM (runs migrations / seeding first)
#   CONTAINER_ROLE=queue      queue worker (mails, reminders)
#   CONTAINER_ROLE=scheduler  task scheduler (reminders, pruning)
set -e

ROLE="${CONTAINER_ROLE:-app}"
KEY_FILE=storage/app/private/.app_key

# Zero-config APP_KEY: generate once and keep it on the shared storage volume.
if [ -z "$APP_KEY" ]; then
    if [ ! -s "$KEY_FILE" ]; then
        if [ "$ROLE" = "app" ]; then
            php -r 'echo "base64:".base64_encode(random_bytes(32));' > "$KEY_FILE"
            echo "[entrypoint] Generated a new APP_KEY in $KEY_FILE"
        else
            echo "[entrypoint] Waiting for the app container to create the APP_KEY..."
            i=0; while [ ! -s "$KEY_FILE" ] && [ $i -lt 60 ]; do sleep 1; i=$((i+1)); done
        fi
    fi
    APP_KEY="$(cat "$KEY_FILE")"
    export APP_KEY
fi

wait_for_db() {
    echo "[entrypoint] Waiting for the database..."
    i=0
    until php artisan db:show --json >/dev/null 2>&1; do
        i=$((i+1))
        if [ $i -ge 60 ]; then echo "[entrypoint] Database not reachable, giving up." >&2; exit 1; fi
        sleep 2
    done
}

if [ "$APP_ENV" = "production" ]; then
    php artisan optimize >/dev/null
fi

case "$ROLE" in
    app)
        wait_for_db
        if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
            php artisan migrate --force --no-interaction
        fi
        if [ "${SEED_DEMO:-false}" = "true" ]; then
            php artisan db:seed --class='Database\Seeders\DemoSeeder' --force --no-interaction
        fi
        exec "$@"
        ;;
    queue)
        wait_for_db
        exec php artisan queue:work --tries=3 --backoff=10 --max-time=3600 --sleep=2
        ;;
    scheduler)
        wait_for_db
        exec php artisan schedule:work
        ;;
    *)
        exec "$@"
        ;;
esac
