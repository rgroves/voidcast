FROM php:8.3-apache
RUN touch /var/voidcast-journal.json
RUN chown www-data:www-data /var/voidcast-journal.json
