# PHP 依存パッケージのインストール
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

# 実行環境（PHP2Shell 修正版の 8.2.35 以上）
FROM php:8.2-apache

RUN php -r 'exit(version_compare(PHP_VERSION, "8.2.35", "<") ? 1 : 0);' \
    || (echo "PHP $(php -r 'echo PHP_VERSION;') is vulnerable to PHP2Shell; need >= 8.2.35" && exit 1)

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql opcache \
    && rm -rf /var/lib/apt/lists/* \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && a2enmod rewrite

# public/ を公開し、Koyeb から渡される $PORT で待ち受ける
ENV PORT=8000 \
    APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!<Directory /var/www/>!<Directory ${APACHE_DOCUMENT_ROOT}>!; /<Directory \$\{APACHE_DOCUMENT_ROOT\}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && sed -ri 's/^Listen 80$/Listen ${PORT}/' /etc/apache2/ports.conf \
    && sed -ri 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
# public/build はコミット済みのビルド成果物をそのまま使う（従来の Koyeb ビルドと同じ）
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor

# package:discover は環境変数（Pusher 等）が必要なため、ビルド時ではなく実行時に Laravel が自動生成する
RUN chown -R www-data:www-data bootstrap/cache storage

EXPOSE 8000
