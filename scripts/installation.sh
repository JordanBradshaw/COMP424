#!/bin/bash
if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "Script must be running as root. Exiting..."
    exit
fi

if [[ ! -d "websiteCode" ]]; then
	echo "Please place your website files in the directory \"websiteCode\" before proceeding. Exiting..."
	exit
fi

apt update # Updating software list
DEBIAN_FRONTEND=noninteractive apt full-upgrade -y # Upgrading software on the operating system
DEBIAN_FRONTEND=noninteractive apt install -y openssh-server apache2 mysql-server php libapache2-mod-php php-mysql php-curl dnsutils # Installing required software
sed -i -e 's/index.html/index.php index.html/g' /etc/apache2/mods-enabled/dir.conf # Adding index.php as an option for Apache2
systemctl restart apache2 # Restarting Apache2 to make the above configuration take effect
mysql -e "create user 'webserver'@'localhost' identified by 'password123';" # Creating a user for the webserver
mysql -e "create database webserver;" # Creating a database for the webserver
mysql -e "create table webserver.users(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, birthdate DATE NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, sec_question VARCHAR(100) NOT NULL, sec_answer VARCHAR(255) NOT NULL, last_login DATE, num_logins INT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, activated BOOLEAN NOT NULL DEFAULT 0, totp VARCHAR(16));" # Creating a table to store user credentials
mysql -e "create table webserver.lockout(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(50) NOT NULL UNIQUE, num_fails INT DEFAULT 0)" # Creating separate table for lockout system
mysql -e "create table webserver.email_verification(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, email VARCHAR(100) NOT NULL UNIQUE, ver_key VARCHAR(255) NOT NULL);" # Creating separate table for email verification
mysql -e "create table webserver.password_reset(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, email VARCHAR(100) NOT NULL UNIQUE, ver_key VARCHAR(255) NOT NULL);" # Creating separate table for password resets
mysql -e "create table webserver.sign_on_logs(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(100) NOT NULL, success BOOLEAN NOT NULL, time DATETIME DEFAULT CURRENT_TIMESTAMP);" # Creating separate table for sign on logs
mysql -e "grant insert on webserver . users to 'webserver'@'localhost';" # Granting permission to insert registration data to the webserver user
mysql -e "grant update on webserver . users to 'webserver'@'localhost';" # Granting permission to update registration data to the webserver user
mysql -e "grant select on webserver . users to 'webserver'@'localhost';" # Granting permission to view registration data to the webserver user
mysql -e "grant insert on webserver . email_verification to 'webserver'@'localhost';" # Granting permission to insert data to the webserver user
mysql -e "grant update on webserver . email_verification to 'webserver'@'localhost';" # Granting permission to update data to the webserver user
mysql -e "grant select on webserver . email_verification to 'webserver'@'localhost';" # Granting permission to view data to the webserver user
mysql -e "grant delete on webserver . email_verification to 'webserver'@'localhost';" # Granting permission to delete data to the webserver user
mysql -e "grant insert on webserver . lockout to 'webserver'@'localhost';" # Granting permission to insert data to the webserver user
mysql -e "grant update on webserver . lockout to 'webserver'@'localhost';" # Granting permission to update data to the webserver user
mysql -e "grant select on webserver . lockout to 'webserver'@'localhost';" # Granting permission to view data to the webserver user
mysql -e "grant insert on webserver . password_reset to 'webserver'@'localhost';" # Granting permission to insert data to the webserver user
mysql -e "grant update on webserver . password_reset to 'webserver'@'localhost';" # Granting permission to update data to the webserver user
mysql -e "grant select on webserver . password_reset to 'webserver'@'localhost';" # Granting permission to view data to the webserver user
mysql -e "grant delete on webserver . password_reset to 'webserver'@'localhost';" # Granting permission to delete data to the webserver user
mysql -e "grant insert on webserver . sign_on_logs to 'webserver'@'localhost';" # Granting permission to insert logs to the webserver user
mysql -e "grant select on webserver . sign_on_logs to 'webserver'@'localhost';" # Granting permission to view logs to the webserver user
mysql -e "flush privileges;" # Updating the privileges to make the above configuration take effect

rm -rf /var/www/*
cp -r websiteCode/* /var/www/
chown -R root:www-data /var/www/* # Ownership limited to root & www-data

chmod -R 2750 /var/www/* # Permissions s.t. all new files are owned by groupdir so our webserver can read them; other has no perms in case accounts are compromised on server
rm -rf /root/.bash_history /root/.mysql_history # Removing histories to prevent users from reading the passwords saved in history
