FROM ubuntu:18.04

MAINTAINER Sunil <sunil.k@reportgarden.com>

# ADD ENV
ENV DEBIAN_FRONTEND=noninteractive

# Add php repository & update system
RUN apt-get update
RUN apt-get install -y software-properties-common
RUN apt-add-repository ppa:ondrej/php && apt-get update

# Get all the essential components.
RUN apt-get install --no-install-recommends -y \
				php7.1 \
				php7.1-cli \
				php7.1-mbstring \
				php-xdebug \
				php7.1-mysql \
				php7.1-json \
				zip \
				unzip \
				php7.1-zip \
				composer

# Create a directory to copy files
RUN mkdir /app

# COPY files to the container.
ADD . /app


# Run composer install to install php dependencies.
RUN cd /app && rm composer.lock && composer install

# Not sure Why exposing a port is needed?
EXPOSE 8080

# Not sure what this is..
CMD ["/usr/bin/php"]




