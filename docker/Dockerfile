FROM nginx:mainline-alpine
MAINTAINER Christian Haschek <office@haschek-solutions.com>

ENV S6_VERSION v1.22.1.0

# Add PHP 7
RUN set -x \
    && apk upgrade -U \
    && apk --update \
        add \
        openssl \
        nano \
        file \
        unzip \
        bash \
        curl \
        php7 \
        php7-pdo \
        php7-exif \
        php7-mcrypt \
        php7-curl \
        php7-gd \
        php7-json \
        php7-fpm \
        php7-openssl \
        php7-ctype \
        php7-opcache \
        php7-mbstring \
        php7-sodium \
        php7-xml \
        php7-ftp \
        php7-simplexml \
        php7-session \
        php7-fileinfo \
        php7-pcntl \
    && rm -rf /var/cache/apk/*

# s6 overlay
# all supported architectures look at "assets" on
# https://github.com/just-containers/s6-overlay/releases
RUN /bin/bash -c 'set -ex && \
    ARCH="$(apk --print-arch)" && \
    case "${ARCH##*-}" in \
        x86_64) S6_PLATFORM="amd64" ;; \
        armv7l) S6_PLATFORM="armhf" ;; \
	armv7) S6_PLATFORM="armhf" ;; \
        armv6) S6_PLATFORM="armhf" ;; \
	armhf) S6_PLATFORM="armhf" ;; \
        arm) S6_PLATFORM="arm" ;; \
        aarch64) S6_PLATFORM="aarch64" ;;\
        i386) S6_PLATFORM="x86" ;;\
        *) echo >&2 "unsupported architecture: ${ARCH}"; exit 1 ;; \
    esac; \
    curl -L -s https://github.com/just-containers/s6-overlay/releases/download/${S6_VERSION}/s6-overlay-${S6_PLATFORM}.tar.gz \
  | tar xvzf - -C / '


COPY /rootfs /

RUN set -x \
    && rm /usr/bin/php \
    && ln -s /etc/php7 /etc/php \
    && ln -s /usr/bin/php7 /usr/bin/php \
    && ln -s /usr/sbin/php-fpm7 /usr/bin/php-fpm \
    && ln -s /usr/lib/php7 /usr/lib/php \
    && mkdir -p /var/log/php-fpm \
    && ln -s /dev/stderr /var/log/php-fpm/fpm-error.log

# Enable default sessions
RUN set -x \
    && mkdir -p /var/lib/php7/sessions \
    && chown nginx:nginx /var/lib/php7/sessions

# ADD SOURCE
RUN set -x \
    && mkdir -p /usr/share/nginx/html

WORKDIR /usr/share/nginx/html

RUN set -x \
    && curl --silent --remote-name https://codeload.github.com/HaschekSolutions/0xf.at/zip/master \
    && unzip -q master \
    && mv 0xf.at-master/* . \
    && rm -r master 0xf.at-master \
    && mv inc/example.config.inc.php inc/config.inc.php \
    && chown -R nginx:nginx /usr/share/nginx/html

VOLUME /usr/share/nginx/html/comments
VOLUME /usr/share/nginx/html/stats
VOLUME /usr/share/nginx/html/data/users

EXPOSE 80

ENTRYPOINT ["bash", "/0xf.sh"]