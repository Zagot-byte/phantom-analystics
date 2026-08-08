#!/usr/bin/env bash
# Phantom Analytics web layer installer (run as root on the target VM)
set -e

if [ "$(id -u)" -ne 0 ]; then
    echo "Please run as root: sudo ./install.sh" >&2
    exit 1
fi

SRC_DIR="$(cd "$(dirname "$0")" && pwd)/webroot"
WEBROOT=/var/www/html

echo "==> Installing system packages"
export DEBIAN_FRONTEND=noninteractive
apt-get update
apt-get install -y apache2 php libapache2-mod-php php-mongodb php-curl curl gnupg ca-certificates

echo "==> Adding MongoDB repository"
. /etc/os-release
if [ "$ID" = "ubuntu" ]; then
    MONGO_REPO_BASE="http://repo.mongodb.org/apt/ubuntu"
else
    MONGO_REPO_BASE="http://repo.mongodb.org/apt/debian"
fi
MONGOVER=8.0
curl -fsSL "https://www.mongodb.org/static/pgp/server-${MONGOVER}.asc" | gpg --dearmor -o /usr/share/keyrings/mongodb-server-${MONGOVER}.asc
echo "deb [ signed-by=/usr/share/keyrings/mongodb-server-${MONGOVER}.asc ] ${MONGO_REPO_BASE} ${VERSION_CODENAME}/mongodb-org/${MONGOVER} main" > /etc/apt/sources.list.d/mongodb-org-${MONGOVER}.list
apt-get update
apt-get install -y mongodb-org-server

echo "==> Configuring Apache (PHP handler set)"
PHP_CONF=$(ls /etc/apache2/mods-available/php*.conf 2>/dev/null | head -1)
if [ -n "$PHP_CONF" ]; then
cat > "$PHP_CONF" <<'EOF'
<FilesMatch ".+\.ph(p|p5)$">
    SetHandler application/x-httpd-php
</FilesMatch>
EOF
fi

cat > /etc/apache2/conf-available/phantom-analytics.conf <<'EOF'
<Directory /var/www/html/uploads>
    AllowOverride All
</Directory>
EOF
a2enconf phantom-analytics || true

echo "==> Deploying application files"
mkdir -p "$WEBROOT"
cp -r "$SRC_DIR"/. "$WEBROOT"/
mkdir -p "$WEBROOT/uploads/avatars"

echo "==> Vendoring mongodb PHP library (MongoDB\\Client wrapper)"
mkdir -p "$WEBROOT/includes/mongo-library"
curl -sL -o /tmp/mongo-lib.tgz https://github.com/mongodb/mongo-php-library/archive/refs/tags/1.17.1.tar.gz
tar xzf /tmp/mongo-lib.tgz -C /tmp
cp -r /tmp/mongo-php-library-1.17.1/src "$WEBROOT/includes/mongo-library/"
rm -rf /tmp/mongo-lib.tgz /tmp/mongo-php-library-1.17.1
cat > "$WEBROOT/includes/mongo-library/autoload.php" <<'PHPEOF'
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

chown -R www-data:www-data "$WEBROOT"
find "$WEBROOT" -type d -exec chmod 755 {} \;
find "$WEBROOT" -type f -exec chmod 644 {} \;
chmod 775 "$WEBROOT/uploads/avatars"

echo "==> Host resolution entry"
grep -q "Sunset" /etc/hosts || echo "127.0.0.1 Sunset" >> /etc/hosts

echo "==> Service account (shared credentials)"
id appdev >/dev/null 2>&1 || useradd -m -s /bin/bash appdev
echo 'appdev:Apricot@Sunset#9' | chpasswd

echo "==> Starting services"
systemctl enable mongod apache2
systemctl restart mongod
sleep 2

echo "==> Creating database account"
php -r '
$m = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
$cmd = new MongoDB\Driver\Command([
    "createUser" => "appdev",
    "pwd" => "Apricot@Sunset#9",
    "roles" => [["role" => "root", "db" => "admin"]],
]);
$m->executeCommand("admin", $cmd);
'
systemctl stop mongod
sed -i 's/#security:/security:/; s/#   authorization: "enabled"/   authorization: "enabled"/' /etc/mongod.conf
systemctl start mongod
sleep 2

echo "==> Applying connection settings"
sed -i 's/Apricot@Sunset#9/Apricot%40Sunset%239/' "$WEBROOT/config.php"
systemctl restart apache2

echo "==> Seeding database"
php "$WEBROOT/seed.php"

echo "==> Done. Web layer is live on http://localhost/"
