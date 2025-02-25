# Node.jsのステージ
FROM node:20 as node-builder
WORKDIR /app
COPY src/package*.json ./
RUN npm install

# PHPのステージ
FROM php:8.2-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    git \
    unzip \
    libzip-dev \
    net-tools \
    iproute2 \
    lsof

# Node.jsとnpmをインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# PHP拡張機能をインストール
RUN docker-php-ext-install pdo_mysql zip

# Composerをインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# node-builderステージからnode_modulesをコピー
COPY --from=node-builder /app/node_modules ./node_modules
COPY --from=node-builder /app/package*.json ./

# キャッシュをクリア
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Xdebugをインストール
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Xdebugの設定
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY ./docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf