FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache configs to use /public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 🔐 Explicitly allow access to /public
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\
