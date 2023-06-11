FROM php:8.1-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql sockets bcmath

# Install Node.js and NPM
RUN apk add --update nodejs npm

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install




RUN npm install

RUN npm run dev 


RUN php artisan config:clear

# Copy and run the entrypoint script to modify the environment variables
COPY docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]

# Run the migrate command after modifying the environment variables
#CMD ["php", "artisan", "migrate"]

# in case of database is not up and running , but we will be using sql query to run.
CMD php artisan migrate ; php artisan serve --host=0.0.0.0 --port=80

# CMD php artisan serve --host=0.0.0.0 --port=80

# Expose the default port
EXPOSE 80
