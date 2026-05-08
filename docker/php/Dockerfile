FROM php:8.4-fpm-bookworm

# Установка системных зависимостей и расширений PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_mysql \
        zip \
        bcmath \
        intl \
        mbstring \
        exif \
        pcntl \
        bcmath \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка OPcache (для производительности)
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Настройка времени
RUN echo "date.timezone=UTC" > /usr/local/etc/php/conf.d/timezone.ini

# Создание пользователя
RUN groupadd -r appuser -g 1000 && \
    useradd -r -g appuser -u 1000 -m -s /bin/bash appuser

WORKDIR /var/www/html
RUN chown -R appuser:appuser /var/www/html
USER appuser

CMD ["php-fpm", "-F"]