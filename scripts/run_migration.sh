#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#	Usage:
#		./run_migration.sh SITE_SLUG
#		./run_migration.sh SITE_SLUG :rollback
#		./run_migration.sh SITE_SLUG :refresh
#
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#
#!/bin/bash

if [ "$#" -lt 1 ]; then
	echo "~~ Not enough arguments supplied. ~~"
    echo "Usage: ./run_migration.sh SITE_SLUG"
    echo "Example: ./run_migration.sh granitecms_dev"
    exit 0
fi

SITE=$1
ACTION=$2
shift 2

php ../artisan migrate$2 --recursive --path="database/migrations/sites/${SITE}/" -vv --site=$SITE