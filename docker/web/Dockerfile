FROM composer:2.6.6 as composer

FROM php:8.3-apache

# 환경 설정
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
  TZ=Asia/Seoul \
  LC_ALL=ko_KR.UTF-8 \
  LANG=ko_KR.UTF-8 \
  LC_TYPE=ko_KR.UTF-8 \
  PHP_MEMORY_LIMIT=512M \
  XDEBUG_IDEKEY=PHPSTORM \
  XDEBUG_REMOTE_PORT=9000

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# 새로운 패키지 버전 확인
RUN apt-get clean \
  && apt-get update --fix-missing \
  && apt-get upgrade -y --allow-unauthenticated \
  && apt-get -y install locales \
  && localedef -f UTF-8 -i ko_KR ko_KR.UTF-8

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
  && apt-get install -y libzip-dev \
  && docker-php-ext-install zip \
  && docker-php-ext-install mysqli pdo_mysql \
  && docker-php-ext-configure sysvsem --enable-sysvsem \
  && docker-php-ext-install sysvsem

ENV OCI_HOME=/usr/lib/instantclient_21_13 \
  OCI_LIB_DIR=$OCI_HOME \
  OCI_INCLUDE_DIR=$OCI_HOME/sdk/include \
  OCI_VERSION=21 \
  ORACLE_HOME=$OCI_HOME \
  NLS_LANG=AMERICAN_AMERICA.UTF8 \
  LD_LIBRARY_PATH=/usr/lib/instantclient_21_13:${LD_LIBRARY_PATH} \
  TNS_ADMIN="/usr/lib/instantclient_21_13/network/admin" \
  NLS_LANG=KOREAN_KOREA.KO16MSWIN949

COPY oracle/instantclient .
RUN apt install -y unzip \
  && unzip instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip -d /usr/lib \
  && rm -f instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip \
  && unzip instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip -d /usr/lib \
  && rm -f instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip \
  && echo $OCI_HOME | tee -a /etc/ld.so.conf.d/oracle_instant_client.conf \
  && ldconfig \
  && apt install -y libaio1 \
  && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,$OCI_HOME,21.13 \
  && echo 'instantclient,/usr/lib/instantclient_21_13' | pecl install oci8 \
  && docker-php-ext-install pdo_oci \
  && docker-php-ext-enable oci8

RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 아파치 Document root 수정
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
  && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 아파치 모듈 활성화(rewirte, headers)
RUN a2enmod rewrite \
  && a2enmod headers

# 아파치 데몬 재시작(현재 연결 유지)
RUN apache2ctl -k graceful

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer --version && php -v
