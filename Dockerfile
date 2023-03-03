FROM php:7.4-apache as base

ENV HOME /app
WORKDIR ${HOME}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN npm install --global prettier @prettier/plugin-php

COPY src/ ${HOME}
