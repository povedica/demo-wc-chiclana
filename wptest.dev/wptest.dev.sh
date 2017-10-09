#!/usr/bin/env bash
wp core download --version=latest  --locale=es_ES  --path=/sites/wptest.dev
wp core config --dbname=wptest  --dbuser=demo  --dbpass=demo  --dbhost=127.0.0.1  --dbprefix=wptest_  --dbcharset=utf8  --dbcollate=utf8_general_ci  --locale=es_ES  --path=/sites/wptest.dev
wp db  create   --path=/sites/wptest.dev
wp core install --url=wptest.dev  --title="WP Test"  --admin_user=demo  --admin_password=demo  --admin_email=yoyyosemite@gmail.com  --path=/sites/wptest.dev
wp plugin install debug-bar   --activate   --path=/sites/wptest.dev
wp theme activate twentyseventeen  --path=/sites/wptest.dev
wp post create  --post_type="page" --post_title="Inicio" --post_status="publish"  --path=/sites/wptest.dev
wp post create  --post_type="page" --post_title="Página 1 - Demo 1" --post_status="publish"  --path=/sites/wptest.dev
wp post generate  --count="10"  --path=/sites/wptest.dev