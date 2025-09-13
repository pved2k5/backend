FROM php:8.2-fpm

# Install system dependencies including netcat
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev unzip curl default-mysql-client \
    nginx netcat-openbsd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create Nginx configuration for Drupal
RUN rm /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-available/drupal
RUN ln -s /etc/nginx/sites-available/drupal /etc/nginx/sites-enabled/

# Create necessary directories and set proper permissions
RUN mkdir -p /var/www/html/config/sync && \
    mkdir -p /var/www/html/web/sites/default/files && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/web/sites/default/files

# Set PHP configuration for Drupal
RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/drupal.ini && \
    echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/drupal.ini && \
    echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/drupal.ini && \
    echo "max_execution_time = 120" >> /usr/local/etc/php/conf.d/drupal.ini

WORKDIR /var/www/html

# Copy entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80 for Nginx
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["start-services"]
