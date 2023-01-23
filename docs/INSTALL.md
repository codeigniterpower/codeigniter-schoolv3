
# Deploy development

this works for any deb based distro (including Devuan or LMDE or other deb based):

1. install venenux repos and necesary packages
2. enable rewrite, userlog and userdir modules
3. setup the root database credentials


```
apt-get --no-install-recommends -y install base-files lsb-release apt-transport-https

cat > /etc/apt/sources.list.d/50venenux.list << EOF
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/lamp-vnx/Debian_$(lsb_release -rs|cut -d. -f1)/ /
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/deploy-vnx1/Debian_$(lsb_release -rs|cut -d. -f1)/ /
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/internet-vnx1/Debian_$(lsb_release -rs|cut -d. -f1)/ /
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/system-vnx1/Debian_$(lsb_release -rs|cut -d. -f1)/ /
EOF

apt update

apt-get -y --force-yes install ca-certificates apt-transport-https \
 apache2 apache2-bin apache2-utils libapache2-mod-php7.4 \
 php7.4 php7.4-common php7.4-cli php7.4-intl php7.4-mbstring \
 php7.4-curl php7.4-readline php7.4-gd php7.4-gmp php7.4-imap \
 php7.4-mysql php7.4-odbc php7.4-pgsql php7.4-sqlite3 php7.4-sybase \
 php7.4-opcache php7.4-snmp php7.4-bz2 php7.4-bcmath php7.4-enchant \
 default-mysql-server default-mysql-client git

wget http://ftp.us.debian.org/debian/pool/main/a/adminer/adminer_4.8.1-1_all.deb

dpkg -i ./adminer_4.8.1-1_all.deb

/usr/sbin/a2dismod php7.3 php7.2 php7.1 php5.6 php8.0 php8.1
/usr/sbin/a2enmod rewrite php7.4 userdir usertrack
/usr/sbin/a2enconf adminer
sed -s -i -r 's#php_admin_flag engine Off#php_admin_flag engine On#g' /etc/apache2/mods-available/php*.conf
/usr/sbin/service apache2 restart
```

Now get out of super user and run as normal user, we demand that the 
development user must be named `general` and their home must be `/home/general`

```
mkdir /home/general/public_html && /home/general/public_html
rm -rf /home/general/public_html/school
git clone https://gitlab.com/codeigniterpower/codeigniter-schoolv3 /home/general/public_html/school

touch /home/general/public_html/school/ENV_DEVEL
rm -rf /home/general/public_html/school/ENV_PROD
cp /home/general/public_html/school/docs/htaccess-devel /home/general/public_html/school/.htaccess

mysql -u root -p -e "DROP DATABASE mdacademico;"

mysql -u root -p -e "CREATE DATABASE mdacademico;"

cat /home/general/public_html/school/docs/database.sql | mysql -u root -p mdacademico
```

Here the `-p` argument means you should input tyhe DBMS password, but 
if you are using mariadb and socket auth.. just remove it.

The only vars that you must change are the DB user and password at
the file `/home/general/public_html/school/mvc/config/development/database.php`
**IMPORTANT** on `mysql` commands if you use socket auth mariadb, dont use `-p`.

To ejecute just visit `http://localhost/~general/` or 
directly go to `http://localhost/~general/school/` to use the app.
To check php use `http://localhost/~general/info.php` after 
run the following command:

```
echo "<?php echo phpinfo(); ?>" > /home/general/public_html/info.php
```

To use the web database editor go to `http://localhost/adminer` and login to your mysql.

# Deploy production

Debian/Devuan based servers distros:

### Preparation of server:

1. install venenux repos to get sure php 5.6 exits on all debians
2. install the necesary packages, must be apache2 due the rewrite rule
3. setup the php, mysql and apache instalations
4. setup the timezone inside mysql and restart the daemons

Run those commands as `root` superuser:

```
apt-get --no-install-recommends -y install base-files lsb-release apt-transport-https

cat > /etc/apt/sources.list.d/50venenux.list << EOF
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/lamp-vnx/Debian_$(lsb_release -rs|cut -d. -f1)/ /
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/deploy-vnx1/Debian_$(lsb_release -rs|cut -d. -f1)/ /
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/internet-vnx1/Debian_$(lsb_release -rs|cut -d. -f1)/ /
deb [trusted=yes] http://download.opensuse.org/repositories/home:/vegnuli:/system-vnx1/Debian_$(lsb_release -rs|cut -d. -f1)/ /
EOF
cat > /etc/apt/sources.list.d/50percona.list << EOF
deb https://repo.percona.com/apt/ $(lsb_release -s -c) main
deb http://repo.percona.com/tools/apt $(lsb_release -s -c) main
EOF

apt update

apt-get -y --force-yes install ca-certificates apt-transport-https \
 apache2 apache2-bin apache2-utils libapache2-mod-php7.4 \
 php7.4 php7.4-common php7.4-cli php7.4-intl php7.4-mbstring \
 php7.4-curl php7.4-readline php7.4-gd php7.4-gmp php7.4-imap \
 php7.4-mysql php7.4-odbc php7.4-pgsql php7.4-sqlite3 php7.4-sybase \
 php7.4-opcache php7.4-snmp php7.4-bz2 php7.4-bcmath php7.4-enchant \
 percona-server-server-5.7 percona-xtrabackup-24 libjemalloc1 \
 libperconaserverclient20 libmecab2 percona-server-tokudb-5.7

sed -s -i -r 's|.*cgi.fix_pathinfo=.*|cgi.fix_pathinfo=1|g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*safe_mode =.*#safe_mode = Off#g' /etc/php/*/*/php.ini
sed -s -i -r 's#expose_php =.*#expose_php = Off#g' /etc/php/*/*/php.ini
sed -s -i -r 's#memory_limit =.*#memory_limit = 512M#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*upload_max_filesize =.*#upload_max_filesize = 12M#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*post_max_size =.*#post_max_size = 64M#g' /etc/php/*/*/php.ini
sed -s -i -r 's#^file_uploads =.*#file_uploads = On#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*max_file_uploads =.*#max_file_uploads = 6#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*allow_url_fopen = .*#allow_url_fopen = On#g' /etc/php/*/*/php.ini
sed -s -i -r 's#^.*default_charset =.*#default_charset = "UTF-8"#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*max_execution_time =.*#max_execution_time = 120#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*max_input_time =.*#max_input_time = 90#g' /etc/php/*/*/php.ini
sed -s -i -r 's#.*default_socket_timeout =.*#default_socket_timeout = 90#g' /etc/php/*/*/php.ini
/sbin/a2enmod rewrite php5.6
echo "<?php echo phpinfo(); ?>" > /var/www/html/info.php
mkdir -p /etc/systemd/system/mysql.service.d
cat > /etc/systemd/system/mysql.service.d/percona.conf << EOF
[Service]
LimitNOFILE=65535
EOF
mkdir -p /etc/security/limits.d
cat > /etc/security/limits.d/mysql.conf << EOF
mysql        soft    nofile           65535
mysql        hard    nofile           65535
EOF
systemctl daemon-reload

 mysql_tzinfo_to_sql /usr/share/zoneinfo/ | mysql -u root mysql -p

/usr/sbin/service mysql restart

/sbin/service apache2 restart
```

### Instalation of application

1. get sure of duplication of files in place
2. download the last commit with the web application
3. uncompress the tarball downloaded
4. move to the right place and define as production environment
5. create database and the user by root user
6. upload and fil the database using new DB user
7. edit the file `production\database.php` and fill new credentials
8. edit the file `config.php` change the `base_url` with domain url

Run those commands as `root` superuser:

```
rm /var/www/html/*
wget -O school.tar.gz https://gitlab.com/codeigniterpower/codeigniter-schoolv3/-/archive/33752a9b4006371a20985c172641b974028746e2/codeigniter-schoolv3-33752a9b4006371a20985c172641b974028746e2.tar.gz

tar xf school.tar.gz -C /var/www/html/
mv /var/www/html/codeigniter-schoolv3* /var/www/html/school
touch /var/www/html/school/ENV_PROD

mysql -u root -p -e "DROP DATABASE mdacademico;"

mysql -u root -p -e "CREATE DATABASE mdacademico;"

mysql -u root -p -e "CREATE USER 'mdacademico'@'127.0.0.1' IDENTIFIED BY PASSWORD 'clave'"

mysql -u root -p -e "GRANT ALL PRIVILEGES ON `mdacademico`.* TO 'mdacademico'@'127.0.0.1' WITH GRANT OPTION;"

cat /var/www/html/school/docs/database.sql | mysql -u mdacademico -pclave mdacademico
```

**IMPORTANT** on `mysql` commands if you use socket auth mariadb, dont use `-p`.

At this point replace the variables at `config.php` named `base_url`, 
then replace the values of database conectivity at `production\database.php`, 
then finally visit the place at the `http://domain.com/school/`, 
its recommended to setup a SSL https layer with cerbot or similar.

# See also

* [README.md](README.md)
