# Sử dụng PHP 8.2 với Apache
FROM php:8.2-apache

# Cài đặt các extension PHP cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn Laravel vào container
COPY . .

# Cấp quyền cho storage và bootstrap/cache trước khi chạy Composer
RUN chmod -R 777 storage bootstrap/cache

# Cài đặt Composer (sau khi có mã nguồn)
RUN composer install --no-dev --optimize-autoloader

# Expose cổng 80
EXPOSE 80

# Chạy Apache
CMD ["apache2-foreground"]
