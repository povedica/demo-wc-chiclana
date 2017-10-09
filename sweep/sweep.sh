#!/usr/bin/env bash
clear
echo "======================================================"
echo "======================================================"
echo "======================================================"
echo "Así limpiaba así, así ..."
echo "Así limpiaba así, así ..."
echo "Así limpiaba así, así ..."
echo "Así limpiaba WordPress que yo lo ví!"
echo "======================================================"
echo "======================================================"
echo "======================================================"

#check https://github.com/lesterchan/wp-sweep/blob/master/class-command.php
#Available subcommands =
 # 	revisions
 # 	auto_drafts
 # 	deleted_posts
 # 	unapproved_comments
 # 	spam_comments
 # 	deleted_comments
 # 	transient_options
 # 	orphan_postmeta
 # 	orphan_commentmeta
 # 	orphan_usermeta
 # 	orphan_termmeta
 # 	orphan_term_relationships
 # 	unused_terms
 # 	duplicated_postmeta
 # 	duplicated_commentmeta
 # 	duplicated_usermeta
 # 	duplicated_termmeta
 # 	optimize_database
 # 	oembed_postmet
 #
 # ## EXAMPLES
 #
 #  1. wp sweep --all
 #		- Run Sweep for all the items.
 #  2. wp sweep revisions
 #		- Sweep only Revision
 #  3. wp sweep revisions auto_drafts deleted_posts unapproved_comments spam_comments deleted_comments transient_options orphan_postmeta orphan_commentmeta orphan_usermeta orphan_termmeta orphan_term_relationships unused_terms duplicated_postmeta duplicated_commentmeta duplicated_usermeta duplicated_termmeta optimize_database oembed_postmet
 #		- Sweep the selected items

#if plugin does not exist
echo "Comprobando si existe el plugin. Si no existe el plugin, lo instalamos por ti"
wp plugin install wp-sweep --path=$1 --activate

wp sweep revisions --path="$1"
wp sweep duplicated_postmeta --path="$1"