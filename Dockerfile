FROM php:8.0-cli-alpine

WORKDIR /app

RUN apk add --update libcurl curl-dev && docker-php-ext-install curl

COPY index.php /app
ENTRYPOINT [ "php","-S","0.0.0.0:8080","-t","/app" ];