FROM php:8.1-fpm

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp apcu bcmath exif gd grpc imap intl ldap mcrypt opcache pgsql pdo_pgsql redis sockets uuid xdebug yaml zip

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl


RUN curl -sS https://getcomposer.org/installer | \
  php -- --version=2.2.3 --install-dir=/usr/local/bin --filename=composer

