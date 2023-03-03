FROM php:7.4-apache as base

ENV HOME /app
WORKDIR ${HOME}

COPY src/ ${HOME}
