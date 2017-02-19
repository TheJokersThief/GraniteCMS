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

if [[ ! -z "$ACTION" ]] && [ ":refresh" == $ACTION ]; then
	# If action not empty and the action is refresh

	# Run a reset of the site's migrations and then 
	# run the migrations again. Simulates a site-specific
	# migration refresh
	php ../artisan migrate:reset --path="sites/${SITE}/theme/migrations/" -vv --site=$SITE
	php ../artisan migrate --path="sites/${SITE}/theme/migrations/" -vv --site=$SITE
else
	php ../artisan migrate$2 --path="sites/${SITE}/theme/migrations/" -vv --site=$SITE
fi