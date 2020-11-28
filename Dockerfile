FROM centos:7

ENV PHP_VERSION=80
ENV COMPOSER_VERSION=1.10.13

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm && \
    rpm -Uvh https://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN yum -y install php${PHP_VERSION} \
    php${PHP_VERSION}-php-cli \
    php${PHP_VERSION}-php-zip \
    php${PHP_VERSION}-php-xml \
    php${PHP_VERSION}-php-mbstring \
    php${PHP_VERSION}-php-pdo

RUN yum -y install epel-release curl git

RUN curl -sS https://getcomposer.org/download/${COMPOSER_VERSION}/composer.phar --output /usr/local/bin/composer && \
    chmod 777 /usr/local/bin/composer && \
    ln -s /usr/bin/php${PHP_VERSION} /usr/bin/php

WORKDIR /var/www

CMD ["./run.sh"]
