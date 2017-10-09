#!/usr/bin/env bash

#example 1
wp db export --skip-plugins --skip-themes

#example 2
wp db export --skip-plugins --skip-themes

#example 3
wp db export "$(date '+%y-%m-%d').sql"

#example 4
wp db export - | gzip > db_backup-$(date +%Y-%m-%d-%H%M%S).sql.gz