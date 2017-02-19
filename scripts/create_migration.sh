#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#	Usage:
#		./create_migration.sh SITE_SLUG FILE_NAME
#		./create_migration.sh SITE_SLUG FILE_NAME --create=TABLE_NAME
#
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#
#!/bin/bash

if [ "$#" -lt 2 ]; then
	echo "~~ Not enough arguments supplied. ~~"
    echo "Usage: ./create_migration.sh SITE_SLUG FILE_NAME"
    echo "Example: ./create_migration.sh granitecms_dev create_new_table"
    exit 0
fi

SITE=$1
FILE=$2
shift 2

php ../artisan make:migration $FILE --path="sites/${SITE}/theme/migrations/" $@ -vv --site=$SITE
