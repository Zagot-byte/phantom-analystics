#!/usr/bin/env bash
# PhantomBoard challenge VM installer - parameterized
# Reproduces the exact PhantomBoard VM setup (web layer + user chain)
# Usage (as root): sudo ./install.sh <username> <password>
set -e

if [ $# -ne 2 ]; then
    echo "Usage: $0 <username> <password>" >&2
    exit 1
fi

USERNAME="$1"
PASSWORD="$2"

# --- tunables (edit if you want different values) ---
SECOND_USER="pooh"
SECOND_PASS="${PASSWORD}"
ROOT_PASS="${PASSWORD}"
HOSTNAME="phantomboard"
STATIC_IP="192.168.56.101/24"
IFACE="enp0s3"

if [ "$(id -u)" -ne 0 ]; then
    echo "Please run as root: sudo $0 $USERNAME ****" >&2
    exit 1
fi

export DEBIAN_FRONTEND=noninteractive
SRC_DIR="$(cd "$(dirname "$0")" && pwd)/webroot"
WEBROOT=/var/www/html/phantom-analystics/webroot

echo "==> Hostname"
hostnamectl set-hostname "$HOSTNAME"
grep -q "127.0.1.1 $HOSTNAME" /etc/hosts || echo "127.0.1.1 $HOSTNAME" >> /etc/hosts

echo "==> Static network config"
cat > /etc/netplan/99-static.yaml <<EOF
network:
  version: 2
  ethernets:
    ${IFACE}:
      dhcp4: yes
      addresses: [${STATIC_IP}]
EOF
netplan apply || true

echo "==> Installing system packages"
apt-get update
apt-get install -y apache2 php libapache2-mod-php php-mongodb php-curl curl gnupg ca-certificates composer git openssh-server

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

cat > /etc/apache2/sites-available/phantom-analytics.conf <<'EOF'
<VirtualHost *:80>
    ServerName phantomboard
    DocumentRoot /var/www/html/phantom-analystics/webroot
    SetHandler application/x-httpd-php

    <Directory /var/www/html/phantom-analystics/webroot>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php index.html
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/phantom-error.log
    CustomLog ${APACHE_LOG_DIR}/phantom-access.log combined
</VirtualHost>
EOF
a2dissite 000-default || true
a2ensite phantom-analytics
a2enmod php || true

echo "==> Creating OS accounts"

useradd -m -s /bin/bash -U "$USERNAME"
usermod -aG adm,cdrom,dip,plugdev,users,lxd "$USERNAME"
echo "${USERNAME}:${PASSWORD}" | chpasswd
mkdir -p "/home/${USERNAME}/.ssh"
touch "/home/${USERNAME}/.ssh/authorized_keys"
chmod 700 "/home/${USERNAME}/.ssh"
chmod 600 "/home/${USERNAME}/.ssh/authorized_keys"
chown -R "${USERNAME}:${USERNAME}" "/home/${USERNAME}/"

cat > "/home/${USERNAME}/flag.txt" <<'EOF'
seems like cron jobs are easier to overload here have teh flag for this user

CYV{JK_AND_USER_HAVE_MANY_SIMILARITIES}
EOF
chown "${USERNAME}:${USERNAME}" "/home/${USERNAME}/flag.txt"

cat > /etc/sudoers.d/${USERNAME}-git <<EOF
${USERNAME} ALL=(root) /usr/bin/git
EOF
chmod 440 /etc/sudoers.d/${USERNAME}-git

useradd -m -s /bin/bash -U "$SECOND_USER"
echo "${SECOND_USER}:${SECOND_PASS}" | chpasswd
chmod 750 "/home/${SECOND_USER}"
cat > "/home/${SECOND_USER}/flag.txt" <<'EOF'
inital foothold not badd 


hers the flag 
CYV{HAVE_it}
EOF
chown "${SECOND_USER}:${SECOND_USER}" "/home/${SECOND_USER}/flag.txt"

echo "==> Phantom cleanup scripts + cron chain"
mkdir -p /opt/phantom/scripts
cat > /opt/phantom/scripts/analytics_cleanup.sh <<'EOF'
#!/bin/bash
echo "$(date) - Analytics cleanup completed" >> /tmp/phantom-cleanup.log
EOF
chown "${SECOND_USER}:${SECOND_USER}" /opt/phantom/scripts/analytics_cleanup.sh
chmod 755 /opt/phantom/scripts/analytics_cleanup.sh

cat > /opt/phantom/scripts/welcome.txt <<'EOF'
new user pooh is created 

note : that the new employees are meant to change the username after the reciving of accoutn which is idk
EOF
chown root:root /opt/phantom/scripts/welcome.txt
chmod 644 /opt/phantom/scripts/welcome.txt

cat > /etc/cron.d/phantom-analytics <<EOF
* * * * * ${USERNAME} /opt/phantom/scripts/analytics_cleanup.sh
EOF
chmod 644 /etc/cron.d/phantom-analytics

echo "==> Root flag"
echo "${ROOT_PASS}" | chpasswd
cat > /root/flag.txt <<'EOF'
GTFO Bins seems to help too much isnt it 

CYV{Admin_successssss-full}
EOF
cat > /root/.gitconfig <<'EOF'
[core]
	pager = less
EOF

echo "==> Deploying application files"
mkdir -p "$(dirname "$WEBROOT")"
cp -r "$SRC_DIR"/. "$WEBROOT"/
mkdir -p "$WEBROOT/uploads/avatars"

echo "==> Composer dependencies"
[ -f "$WEBROOT/composer.json" ] || cat > "$WEBROOT/composer.json" <<'EOF'
{
    "require": {
        "mongodb/mongodb": "^2.1"
    }
}
EOF
composer install --working-dir="$WEBROOT" --no-dev --no-interaction

cat > "$WEBROOT/config.php" <<'EOF'
<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client("mongodb://127.0.0.1:27017");
$db = $client->phantomdb;
$collection = $db->users;
EOF

chown -R "${USERNAME}:${USERNAME}" "$WEBROOT"

echo "==> Starting services"
systemctl enable mongod apache2 ssh
systemctl restart mongod
sleep 2
systemctl restart apache2

echo "==> Seeding database"
php "$WEBROOT/seed.php"

echo "==> Done. Web layer is live on http://localhost/"
echo "SSH: ssh ${USERNAME}@<vm-ip>"
echo "root password: ${ROOT_PASS} (SSH root login stays disabled by default)"
