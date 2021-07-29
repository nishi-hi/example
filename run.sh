#!/bin/bash

docker run -d -h example -p 80:80 \
--mount type=bind,source="$(pwd)/mount/etc-nginx-conf.d-example.conf",target="/etc/nginx/conf.d/example.conf" \
--mount type=bind,source="$(pwd)/mount/srv-www-example",target="/srv/www/example" \
--privileged example:latest /sbin/init
