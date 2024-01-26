FROM php:8.2-fpm

WORKDIR /var/www/html

# Устанавливаем зависимости Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
&& docker-php-ext-install intl zip gd pdo pdo_mysql

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем файлы Symfony
COPY . .

# Устанавливаем зависимости проекта
RUN composer install --no-scripts

# Экспортируем порт
EXPOSE 9000

CMD ["php-fpm"]
