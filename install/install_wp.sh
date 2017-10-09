#!/usr/bin/env bash

#install
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php wp-cli.phar --info
sudo mv wp-cli.phar /usr/local/bin/wp
wp --info

#Help inline
wget https://raw.githubusercontent.com/wp-cli/wp-cli/master/utils/wp-completion.bash

#Edit .bash_profile and add this line at the end
#source /path/to/wp-completion.bash

#  export PATH=/usr/local/bin:$PATH
# export PATH