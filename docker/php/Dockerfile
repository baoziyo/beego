FROM baoziyoo/base-images:php-cli-8.1-swoole-4.8.11

ENV DEBIAN_FRONTEND noninteractive

LABEL maintainer="ogg"

RUN set -ex \
    # && sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories \
#    && apk update \
#    &&  /usr/sbin/usermod -o -u 1000 -g www-data
#    && sed -i 's/user = www-data/user = root/g' /usr/local/etc/php-fpm.d/www.conf \
#    && sed -i 's/group = www-data/group = root/g' /usr/local/etc/php-fpm.d/www.conf

ENV LC_ALL POSIX

WORKDIR /var/www

EXPOSE 9501

ENTRYPOINT ["php", "/var/www/bin/hyperf.php", "start"]
