FROM php:8.1-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        autoconf g++ make pkg-config libssl-dev libcurl4-openssl-dev \
        curl ca-certificates gnupg \
    && curl -sL -o /tmp/mongodb.tgz https://pecl.php.net/get/mongodb-1.17.3.tgz \
    && tar xzf /tmp/mongodb.tgz -C /tmp \
    && cd /tmp/mongodb-1.17.3 \
    && phpize \
    && ./configure --quiet \
    && make -j$(nproc) \
    && make install \
    && docker-php-ext-enable mongodb \
    && rm -rf /tmp/mongodb.tgz /tmp/mongodb-1.17.3 \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://www.mongodb.org/static/pgp/server-8.0.asc | gpg --dearmor -o /usr/share/keyrings/mongodb-server-8.0.asc \
    && echo "deb [ signed-by=/usr/share/keyrings/mongodb-server-8.0.asc ] http://repo.mongodb.org/apt/debian bookworm/mongodb-org/8.0 main" > /etc/apt/sources.list.d/mongodb-org-8.0.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends mongodb-org-server \
    && rm -rf /var/lib/apt/lists/*

RUN echo "127.0.0.1 Sunset" >> /etc/hosts

COPY php-handler.conf /etc/apache2/mods-available/php8.1.conf
COPY phantom-analytics.conf /etc/apache2/conf-available/phantom-analytics.conf
RUN a2enconf phantom-analytics

COPY entrypoint.sh /usr/local/bin/phantom-entrypoint
RUN chmod +x /usr/local/bin/phantom-entrypoint

ENTRYPOINT ["/usr/local/bin/phantom-entrypoint"]
