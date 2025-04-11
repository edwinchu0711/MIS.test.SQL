FROM php:8.2-apache

# 複製你的網站檔案到 Apache 根目錄
COPY . /var/www/html/

# 啟用 Apache rewrite module（可選）
RUN a2enmod rewrite

# 安裝 PostgreSQL 驅動所需的依賴
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# 啟用 Apache 的 rewrite 模組
RUN a2enmod rewrite

# 複製您的應用程式檔案
COPY . /var/www/html/

# 設定正確的權限
RUN chown -R www-data:www-data /var/www/html/
