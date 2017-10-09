#!/usr/bin/env bash
wp search-replace "http://$1" "http://$2" --all-tables --path=$3