FROM amazonlinux:2
LABEL maintainer="github.com/nishi-hi"

# Define uneditable variables
ARG HTTP_PORT="80"

# Set listen ports
EXPOSE ${HTTP_PORT}

# Change working directory
WORKDIR /root

# Construct software environment
RUN set -o pipefail -x && \
    : Change timezone && \
    { echo 'ZONE="Japan"'; \
      echo "UTC=true"; } |tee /etc/sysconfig/clock && \
    ln -fs /usr/share/zoneinfo/Japan /etc/localtime && \
    : Install packages && \
    amazon-linux-extras install -y nginx1 php7.2 GraphicsMagick1.3 epel && \
    yum install -y php-pear-Pager perl perl-Image-ExifTool && \
    : Change user and group of PHP-FPM && \
    sed -i -e 's:^\(\(user\|group\)\s*=\s*\)apache$:\1nginx:' -e 's:^\(listen.acl_users\s*=\s*.*\)$:; \1:' -e 's:^;\(listen\.\(owner\|group\)\s*=\s*\).*$:\1nginx:' /etc/php-fpm.d/www.conf && \
    : Make content directory && \
    mkdir -p /srv/www/example/ && \
    chmod 755 /srv/www/ && \
    chmod 750 /srv/www/example/ && \
    chown nginx:nginx /srv/www/example/ && \
    : Enable nginx and PHP-FPM service && \
    systemctl enable nginx && \
    systemctl enable php-fpm
