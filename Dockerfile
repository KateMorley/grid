FROM php:8.3-cli-alpine AS php
RUN docker-php-ext-install mysqli

FROM nginx:alpine-slim AS web
RUN sed -i -e '/default_type/a\' -e '    charset utf-8;' /etc/nginx/nginx.conf
