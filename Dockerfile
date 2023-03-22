FROM nexus01.leekie.com:8085/nginx-php:8.1.4

#define nginx config with root folder
ENV APP_VERSION=0.0.1
ENV TZ=Asia/Manila PHP_DATE_TIMEZONE=Asia/Manila WEB_DOCUMENT_ROOT=/var/www/html/public FPM_REQUEST_TERMINATE_TIMEOUT=300
ENV ROOT_FOLDER=/var/www/html/public
ENV COMPOSER_PROCESS_TIMEOUT=1200 \ 
    COMPOSER_HOME=/root/composer_cache

#Copying of project files and nginx config
COPY ./ /var/www/html/
COPY ./docker/development/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/development/startproduction.sh /scripts/startproduction.sh 


RUN envsubst '${ROOT_FOLDER}' < /etc/nginx/conf.d/site.conf.template > /etc/nginx/conf.d/site.conf && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get clean &&\
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    composer install --no-dev && \ 
    chmod 777 -R storage/framework && \ 
    chmod +x /scripts/startproduction.sh

EXPOSE 80
ENTRYPOINT ["/scripts/startproduction.sh"]
