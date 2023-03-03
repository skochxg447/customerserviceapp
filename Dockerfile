# Install base packages
FROM php:7.4-apache as base

ENV HOME /app
WORKDIR ${HOME}

RUN apt-get update && apt-get install -y \
    software-properties-common \
    make \
    npm
RUN npm install npm@latest -g && \
    npm install n -g && \
    n latest
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
FROM base as develop

COPY Makefile ${HOME}

COPY composer.json ${HOME}
COPY composer.lock ${HOME}
COPY package.json ${HOME}
COPY package-lock.json ${HOME}

RUN make _install-deps

# Build app
FROM develop as app

COPY src/ ${HOME}
