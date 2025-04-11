FROM php:8.2-apache

# 複製你的網站檔案到 Apache 根目錄
COPY . /var/www/html/

# 啟用 Apache rewrite module（可選）
RUN a2enmod rewrite
