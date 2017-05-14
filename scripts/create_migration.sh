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
    echo "Example: ./create_migration.sh granitecms_dev create_new_table --create=new_table"
    exit 0
fi

SITE=$1
FILE=$2
CREATE_TABLE=""
shift 2

createRegex='--create=([a-zA-Z_]+)'
if [[ $@ =~ $createRegex ]]
then
    CREATE_TABLE="--create=${SITE}_${BASH_REMATCH[1]}"
fi

for arg do
  shift
  [[ "$arg" =~ "--create=" ]] && continue
  set -- "$@" "$arg"
done

php ../artisan make:migration $FILE $CREATE_TABLE --path="database/migrations/sites/${SITE}" $@ -vv --site=$SITE
