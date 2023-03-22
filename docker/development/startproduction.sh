#!/bin/bash

# $STARTUP_FILE = "/appstart.txt"
# if [[ ! -f $STARTUP_FILE ]]; then
#     touch $STARTUP_FILE;

#     # recache config 
#     echo -e "\033[1;35m Caching config... \033[0m"
#     php artisan config:clear && php artisan config:cache
#     echo -e "\033[1;35m Done config cache! \033[0m"
    
#     # Copy to RSO
#     echo -e "\033[1;35m COPY assets to RSO... \033[0m" 
#     $RSO_FOLDER = /var/www/html/rso/
#     if [[ -d "$RSO_FOLDER" ]]; then
#         cp -a /var/www/html/public/assets/. $RSO_FOLDER/zircon_ps
#     fi
#     echo -e "\033[1;35m Done RSO! \033[0m"
# fi

# RUN webserver
echo -e "\033[1;35m App start \033[0m" 
/usr/bin/supervisord -n -c /etc/supervisord.conf