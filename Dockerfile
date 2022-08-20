FROM php:7.4-fpm

############################
# Install PHP requirements #
############################

# Install wget, git and libraries needed by php extensions
RUN apt-get update && \
    apt-get install -y \
    vim \
    mc \
    curl \
    zip \
    supervisor

# Remove lists
RUN rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions sockets
RUN install-php-extensions mysqli
# Set working directory
WORKDIR /var/www
# Install composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer
# Copy composer.json
COPY ./var/www/composer.json /var/www/

