FROM php:8.4-fpm

RUN apt-get update

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libwebp-dev \
    libxpm-dev \
    unzip

# Install supervisor
RUN apt-get install -y supervisor

# Install Imagick
# RUN apt-get install -y libmagickwand-dev --no-install-recommends \
# && pecl install imagick \
# && docker-php-ext-enable imagick

RUN apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install sockets pdo pdo_mysql mbstring exif pcntl bcmath gd opcache

# Install necessary PHP extensions
RUN docker-php-ext-configure gd --with-webp

# Install Postgre PDO
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

#RUN pecl install mongodb && docker-php-ext-enable mongodb

# # Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY /supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php/php.ini-development $PHP_INI_DIR/conf.d/
COPY php/php.ini-production $PHP_INI_DIR/conf.d/

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    mkdir -p /var/run/supervisor && \
    chown -R $user:$user /var/run/supervisor

# RUN mkdir -p /var/www/onlyoffice/Data && \
#    chown -R $user:$user /var/www/onlyoffice/Data

# Entrypoint faylini nusxalash va bajarishga ruxsat berish
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["/bin/bash"]
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

USER $user
