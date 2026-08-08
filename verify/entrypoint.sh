#!/usr/bin/env bash
set -e

mkdir -p /data/db
chown -R mongodb:mongodb /data/db

grep -q "Sunset" /etc/hosts || echo "127.0.0.1 Sunset" >> /etc/hosts

su -s /bin/bash mongodb -c "mongod --dbpath /data/db --bind_ip 127.0.0.1 --logpath /data/db/mongod.log --fork"

until (echo > /dev/tcp/127.0.0.1/27017) 2>/dev/null; do
    sleep 1
done

chown -R www-data:www-data /var/www/html/uploads

if [ ! -f /var/www/html/includes/mongo-library/autoload.php ]; then
    mkdir -p /var/www/html/includes/mongo-library
    curl -sL -o /tmp/mongo-lib.tgz https://github.com/mongodb/mongo-php-library/archive/refs/tags/1.17.1.tar.gz
    tar xzf /tmp/mongo-lib.tgz -C /tmp
    cp -r /tmp/mongo-php-library-1.17.1/src /var/www/html/includes/mongo-library/
    cat > /var/www/html/includes/mongo-library/autoload.php <<'PHPEOF'
<?php
spl_autoload_register(function ($class) {
    $prefix = 'MongoDB\\';
    if (strncmp($class, $prefix, 8) !== 0) {
        return;
    }
    $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, 8)) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
PHPEOF
fi

php -r '
$m = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
try {
    $m->executeCommand("admin", new MongoDB\Driver\Command([
        "createUser" => "appdev",
        "pwd" => "Apricot@Sunset#9",
        "roles" => [["role" => "root", "db" => "admin"]],
    ]));
} catch (Exception $e) {
    echo "user exists or create failed: ", $e->getMessage(), "\n";
}
'

su -s /bin/bash mongodb -c "mongod --dbpath /data/db --shutdown" || true
sleep 1
su -s /bin/bash mongodb -c "mongod --dbpath /data/db --bind_ip 127.0.0.1 --auth --logpath /data/db/mongod.log --fork"
until (echo > /dev/tcp/127.0.0.1/27017) 2>/dev/null; do
    sleep 1
done

sed -i 's/Apricot@Sunset#9/Apricot%40Sunset%239/' /var/www/html/config.php

php /var/www/html/seed.php

exec apache2-foreground
