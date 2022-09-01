#!/usr/bin/env bash
sudo apt-get update
sudo apt-get install libcurl4-openssl-dev
swoole_version=$(response=$(curl -s -i https://github.com/swoole/swoole-src/releases/latest | grep 'location' | head -n 1) && echo ${response##*/v} | tr -d '\r')
wget https://github.com/swoole/swoole-src/archive/v${swoole_version}.tar.gz -O swoole.tar.gz
mkdir -p swoole
tar -xf swoole.tar.gz -C swoole --strip-components=1
rm swoole.tar.gz
cd swoole
phpize
./configure --enable-openssl --enable-http2 --enable-swoole-curl --enable-swoole-json
make -j$(nproc)
sudo make install
sudo sh -c "echo extension=swoole > /etc/php/8.1/cli/conf.d/swoole.ini"
sudo sh -c "echo swoole.use_shortname='Off' >> /etc/php/8.1/cli/conf.d/swoole.ini"
php --ri swoole
