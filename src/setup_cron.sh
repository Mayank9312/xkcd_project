#!/bin/bash

# Path to PHP executable (you can adjust it to match your system)
PHP_PATH="/usr/bin/php"

# Full path to cron.php
CRON_PATH="$(pwd)/cron.php"

# CRON expression: Run once every 24 hours at 9:00 AM
CRON_JOB="0 9 * * * $PHP_PATH $CRON_PATH"

# Add to crontab if not already present
(crontab -l 2>/dev/null | grep -v -F "$CRON_PATH" ; echo "$CRON_JOB") | crontab -

echo "✅ CRON job scheduled to run cron.php daily at 9:00 AM."
