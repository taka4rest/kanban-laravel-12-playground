FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    telnet

RUN docker-php-ext-install pdo_mysql

# Apacheの設定を上書きするために、設定ファイルをコピー
COPY default.conf /etc/apache2/sites-available/000-default.conf

# ドキュメントルートを/var/www/htmlに設定
WORKDIR /var/www/html

# a2enmod rewrite を実行して rewrite モジュールを有効化
RUN a2enmod rewrite

# a2enmod proxy_fcgi を実行して proxy_fcgi モジュールを有効化 
RUN a2enmod proxy_fcgi 