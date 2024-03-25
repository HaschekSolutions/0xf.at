FROM alpine:3.19

RUN apk add --no-cache php83 nginx php83-openssl php83-bcmath php83-gd php83-mbstring php83-session php83-json php83-fpm nodejs
RUN mkdir -p /var/www/0xf
WORKDIR /var/www/0xf

ADD . /var/www/0xf/.

ADD docker/rootfs/start.sh /etc/start.sh
RUN chmod +x /etc/start.sh

# nginx stuff
ADD docker/rootfs/nginx.conf /etc/nginx/http.d/default.conf
RUN mkdir -p /run/nginx
RUN mkdir -p /var/log/nginx
RUN sed -i 's/nobody/nginx/g' /etc/php83/php-fpm.d/www.conf
RUN sed -i 's/E_ALL \& ~E_DEPRECATED \& ~E_STRICT/E_ALL \& ~E_DEPRECATED \& ~E_STRICT \& ~E_NOTICE \& ~E_WARNING/g' /etc/php83/php.ini

WORKDIR /var/www/0xf

# Volumes to mount
VOLUME /var/www/0xf/data/users
VOLUME /var/www/0xf/comments

EXPOSE 80 2323 2727

#CMD ["/bin/ash"]
ENTRYPOINT ["/etc/start.sh"]
