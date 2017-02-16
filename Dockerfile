FROM php:7.1-apache

COPY todo/ /var/www/

WORKDIR /var/www

RUN a2enmod rewrite && \
	apt-get update && \
	echo "yes" | apt-get upgrade && \
	echo "yes" | apt-get install git libssl-dev && \
	curl  https://getcomposer.org/installer > /var/www/composer.phar && php /var/www/composer.phar install && \
	pecl install mongodb && \
	pecl install xdebug-beta && \
	docker-php-ext-enable xdebug && \
	echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini

EXPOSE 80

# Build docker image
# docker build php71-mongo .

### RUN MANUALLY

# Run Mongo image
# docker run --name mongodb -d mongo

# Run php71-mongo image linking a mongo instance
# docker run --link mongodb:mongo -p 80:80 -v "$PWD"/todo/:/var/www/ php71-mongo

### DOCKER COMPOSE

# Run using docker-composer
# docker-compose up