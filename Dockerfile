# Initial Setup
# RUN echo 'foo' > /tmp/foo.txt

# Php Config
# FROM php:8.2-cli
# COPY arcanist /var/www/arcanist
# COPY phorge /var/www/phorge
# WORKDIR /var/www/phorge
# RUN echo 'foo' > /tmp/foo.txt
# CMD ["./bin/storage", "upgrade"]

# NGINX Config
# FROM nginx
# COPY nginx/nginx.conf /etc/nginx/conf.d/default.conf 
# EXPOSE 9000
# CMD ["nginx", "-g", "daemon off;"]


# FROM php:8.2-cli
# FROM php:fpm-alpine

FROM php:8.2-fpm
RUN docker-php-ext-install mysqli
# WORKDIR /var/www/phorge
# CMD "./bin/config set mysql.pass ${MYSQL_PASS} \
#   && ./bin/config set mysql.host ${MYSQL_HOST} \
#   && ./bin/config set mysql.user ${MYSQL_USER} \
#   && ./bin/config set mysql.port ${MYSQL_PORT} \
#   && ./bin/storage upgrade"