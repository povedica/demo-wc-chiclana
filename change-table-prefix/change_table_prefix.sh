#!/usr/bin/env bash

#check: https://github.com/iandunn/wp-cli-rename-db-prefix

if [[ "$1" = "" ]]; then
    echo "Please add the new preffix"
    exit
fi

if [[ "$2" = "" ]]; then
    echo "Wordpress installation Path required"
    exit
fi

# wp package install
wp package install iandunn/wp-cli-rename-db-prefix

if [ -f "$2/wp-config.php" ]; then
    echo "Wordpress installation ok, doing db backup ..."
    wp db export --path="$2"
    echo "Wordpress installation ok, doing wp-config.ph backup ..."
    cp -f $2/wp-config.php wp-config.php.backup
fi

wp rename-db-prefix $1 --path="$2"