FROM composer:2.6.6 as composer

FROM php:8.3-apache

# composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt update && apt install -y libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-configure zip --with-zip
RUN docker-php-ext-enable zip

RUN docker-php-ext-install mysqli pdo_mysql


ENV OCI_HOME=/usr/lib/instantclient_21_13 \
  OCI_LIB_DIR=$OCI_HOME \
  OCI_INCLUDE_DIR=$OCI_HOME/sdk/include \
  OCI_VERSION=21 \
  ORACLE_HOME=$OCI_HOME \
  NLS_LANG=AMERICAN_AMERICA.UTF8 \
  LD_LIBRARY_PATH=/usr/lib/instantclient_21_13:${LD_LIBRARY_PATH} \
  TNS_ADMIN="/usr/lib/instantclient_21_13/network/admin" \
  NLS_LANG=KOREAN_KOREA.KO16MSWIN949

RUN apt install -y unzip
RUN curl -O https://download.oracle.com/otn_software/linux/instantclient/2113000/instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip
RUN unzip instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip -d /usr/lib
RUN rm -f instantclient-basiclite-linux.x64-21.13.0.0.0dbru.zip
RUN curl -O https://download.oracle.com/otn_software/linux/instantclient/2113000/instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip
RUN unzip instantclient-sdk-linux.x64-21.13.0.0.0dbru.zip -d /usr/lib
RUN echo $OCI_HOME | tee -a /etc/ld.so.conf.d/oracle_instant_client.conf
RUN echo "          (ADDRESS =(PROTOCOL=TCP)(HOST=192.168.0.40)(PORT=1521)" | tee $OCI_HOME/network/admin/tnsnames.ora

RUN apt install -y libaio1
RUN ldconfig
RUN docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,$OCI_HOME,21.13
RUN echo 'instantclient,/usr/lib/instantclient_21_13' | pecl install oci8
RUN docker-php-ext-install pdo_oci
RUN docker-php-ext-enable oci8

# 아파치 Document root 수정
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 아파치 모듈 활성화(rewirte, headers)
RUN a2enmod rewrite
RUN a2enmod headers

# 아파치 데몬 재시작(현재 연결 유지)
RUN apache2ctl -k graceful

RUN chown -R www-data:www-data /var/www/html