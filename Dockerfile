FROM php:8.1-apache

# نسخ الملفات إلى مجلد الموقع
COPY . /var/www/html/

# تفعيل mod_rewrite
RUN a2enmod rewrite

# تعيين صلاحيات الملفات
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
