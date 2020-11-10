#!/bin/bash
if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "Script must be running as root. Exiting..."
    exit
fi
# AWS ALREADY FILTERS THIS
#iptables -F # Removing default policies
#iptables -P INPUT DROP # Dropping everything inbound by default
#iptables -P OUTPUT ACCEPT # Accepting all outbound because we'll need to be able to conduct updates, DNS queries
#iptables -P FORWARD DROP # Not a router, hence forwarding is disabled
#iptables -A INPUT -i lo -j ACCEPT # Loopback enabled
#iptables -A OUTPUT -o lo -j ACCEPT # Loopback enabled
#iptables -A INPUT -p tcp --dport 22 -j ACCEPT # SSH allowed
#iptables -A INPUT -p tcp --dport 443 -j ACCEPT # HTTPS allowed
#iptables -A INPUT -p tcp -m state --state RELATED,ESTABLISHED -j ACCEPT # Required to continue conversations with servers; will temporarily unlock TCP ports as need
#iptables -A INPUT -p udp -m state --state RELATED,ESTABLISHED -j ACCEPT # Required to continue conversations with servers; will temporarily unlock UDP ports as need

DEBIAN_FRONTEND=noninteractive apt install -y iptables-persistent # Installing persistence service
invoke-rc.d netfilter-persistent save # Enabling persistence
apt update # Updating software list
timedatectl set-timezone America/Los_Angeles # Setting up the timezone to get proper log date & time
#REQUIREMENTS FOR SNORT AS WELL AS ADDITIONAL PACKAGES
DEBIAN_FRONTEND=noninteractive apt install -y build-essential autotools-dev libdumbnet-dev libluajit-5.1-dev libpcap-dev zlib1g-dev pkg-config libhwloc-dev cmake # Required tools for Snort
DEBIAN_FRONTEND=noninteractive apt install -y liblzma-dev openssl libssl-dev cpputest libsqlite3-dev uuid-dev # Optional but recommended tools for snort
DEBIAN_FRONTEND=noninteractive apt install -y libtool git autoconf # Required to use with Git
DEBIAN_FRONTEND=noninteractive apt install -y bison flex # Required for Snort DAQ
DEBIAN_FRONTEND=noninteractive apt install -y libnetfilter-queue-dev libmnl-dev # Required for inline
DEBIAN_FRONTEND=noninteractive apt install -y openssh-server libpcre3-dev
DEBIAN_FRONTEND=noninteractive apt install -y snort

# Installing flatbuffers, recommended by Snort 3; memory efficient serialization library
cd ~/snort_src
wget https://github.com/google/flatbuffers/archive/v1.11.0.tar.gz -O flatbuffers-v1.11.0.tar.gz
tar -xzf flatbuffers-v1.11.0.tar.gz
mkdir flatbuffers-build
cd flatbuffers-build
cmake ../flatbuffers-1.11.0
make
make install

ldconfig # Updating shared libraries

#Adding require environment variables to all users in the system + in sudoers group
echo 'export LUA_PATH=/usr/local/include/snort/lua/\?.lua\;\;' | tee -a /home/*/.bashrc
echo 'export SNORT_LUA_PATH=/usr/local/etc/snort' | tee -a /home/*/.bashrc
echo 'Defaults env_keep += "LUA_PATH SNORT_LUA_PATH"' > /etc/sudoers.d/snort-lua
chmod 440 /etc/sudoers.d/snort-lua

interface="$(route | grep '^default' | grep -o '[^ ]*$')" # Determining interfaces that uses the internet

#Creating service that disables LRO & GRO as required by Snort
echo "[Unit]" > /lib/systemd/system/snort-ethtool.service
echo "Description=Ethtool Configration for Snort 3; disables LRO & GRO" >> /lib/systemd/system/snort-ethtool.service
echo "" >> /lib/systemd/system/snort-ethtool.service
echo "[Service]" >> /lib/systemd/system/snort-ethtool.service
echo "Requires=network.target" >> /lib/systemd/system/snort-ethtool.service
echo "Type=oneshot" >> /lib/systemd/system/snort-ethtool.service
echo "ExecStart=/sbin/ethtool -K $interface gro off" >> /lib/systemd/system/snort-ethtool.service
echo "ExecStart=/sbin/ethtool -K $interface lro off" >> /lib/systemd/system/snort-ethtool.service
echo "" >> /lib/systemd/system/snort-ethtool.service
echo "[Install]" >> /lib/systemd/system/snort-ethtool.service
echo "WantedBy=multi-user.target" >> /lib/systemd/system/snort-ethtool.service

#Enabling and starting service
systemctl enable snort-ethtool
service snort-ethtool start

#Installing community rules
cd ~/snort_src/
wget https://www.snort.org/downloads/community/snort3-community-rules.tar.gz
tar -xzf snort3-community-rules.tar.gz
cd snort3-community-rules
mkdir /usr/local/etc/snort/rules
mkdir /usr/local/etc/snort/builtin_rules
mkdir /usr/local/etc/snort/so_rules
mkdir /usr/local/etc/snort/lists
cp snort3-community.rules /usr/local/etc/snort/rules
cp sid-msg.map /usr/local/etc/snort/rules

#Enabling Snort's built-in rules
sed -i -e 's/--enable_builtin_rules = true/enable_builtin_rules = true/g' /usr/local/etc/snort/snort.lua

#Configuring rule defaults
sed -i -e 's/..\/rules/\/usr\/local\/etc\/snort\/rules/g' /usr/local/etc/snort/snort_defaults.lua
sed -i -e 's/..\/builtin_rules/\/usr\/local\/etc\/snort\/builtin_rules/g' /usr/local/etc/snort/snort_defaults.lua
sed -i -e 's/..\/so_rules/\/usr\/local\/etc\/snort\/so_rules/g' /usr/local/etc/snort/snort_defaults.lua
sed -i -e 's/..\/lists/\/usr\/local\/etc\/snort\/lists/g' /usr/local/etc/snort/snort_defaults.lua

# Including ruleset in ips.include s.t. Snort can detect multiple independent rule files
echo "" >> /usr/local/etc/snort/rules/local.rules
echo "include rules/snort3-community.rules" >> /usr/local/etc/snort/rules/ips.include
echo "include rules/local.rules" >> /usr/local/etc/snort/rules/ips.include
awk '/\x27snort3-community.rules\x27/ { print; print "    include = RULE_PATH .. \x27/ips.include\x27,"; next }1' /usr/local/etc/snort/snort.lua | tee /usr/local/etc/snort/snort2.lua
mv /usr/local/etc/snort/snort2.lua /usr/local/etc/snort/snort.lua

# Modified fast alerts to output to a file by default and limit filesizes to 100MB
sed -i -e 's/--alert_fast = { }/alert_fast = {\n    file = true,\n    limit = 100,\n}/g' /usr/local/etc/snort/snort.lua

# Setting up Snort user and group
groupadd snort
useradd snort -r -s /sbin/nologin -c SNORT_IDS -g snort

# Clearing log files if any were made
rm -rf /var/log/snort

# Recreating log directory
mkdir /var/log/snort

# Granting rights to snort in the log directory
chmod -R 5775 /var/log/snort
chown -R snort:snort /var/log/snort

#Creating Snort service for SystemD
echo "[Unit]" > /lib/systemd/system/snort3.service
echo "Description=Snort3 NIDS Daemon" >> /lib/systemd/system/snort3.service
echo "After=syslog.target network.target" >> /lib/systemd/system/snort3.service
echo "" >> /lib/systemd/system/snort3.service
echo "[Service]" >> /lib/systemd/system/snort3.service
echo "Type=simple" >> /lib/systemd/system/snort3.service
echo "ExecStart=/usr/local/bin/snort -c /usr/local/etc/snort/snort.lua -s 65535 -k none -l /var/log/snort -D -u snort -g snort -i $interface -m 0x1b"  >> /lib/systemd/system/snort3.service
echo "" >> /lib/systemd/system/snort3.service
echo "[Install]" >> /lib/systemd/system/snort3.service
echo "WantedBy=multi-user.target" >> /lib/systemd/system/snort3.service

systemctl enable snort3
service snort3 start

DEBIAN_FRONTEND=noninteractive apt -y install dnsutils

DOMAIN=$(dig @resolver1.opendns.com ANY myip.opendns.com +short)

if [ -z "$DOMAIN" ]; then
  echo "Usage: $(basename $0) <domain>"
  exit 11
fi
fail_if_error() {
  [ $1 != 0 ] && {
    unset PASSPHRASE
    exit 10
  }
}
# Generate a passphrase
sed -i "/RANDFILE/ s/^#*/#/ " /etc/ssl/openssl.cnf #comment out RANDFILE line in ssl.conf


export PASSPHRASE=$(head -c 500 /dev/urandom | tr -dc a-z0-9A-Z | head -c 128; echo)
# Certificate details
subj="
C=US
ST=CA
O=Comp424
localityName=California
commonName=$DOMAIN
organizationalUnitName=424Group
emailAddress=JordanBradshaw27@.com
"
# Generate the server private key
openssl genrsa -des3 -out $DOMAIN.key -passout env:PASSPHRASE 2048
fail_if_error $?
# Generate the CSR
openssl req \
    -new \
    -batch \
    -subj "$(echo -n "$subj" | tr "\n" "/")" \
    -key $DOMAIN.key \
    -out $DOMAIN.csr \
    -passin env:PASSPHRASE
fail_if_error $?
cp $DOMAIN.key $DOMAIN.key.org
fail_if_error $?
# Strip the password so we don't have to type it every time we restart Apache
openssl rsa -in $DOMAIN.key.org -out $DOMAIN.key -passin env:PASSPHRASE
fail_if_error $?
# Generate the cert (good for 10 years)
openssl x509 -req -days 3650 -in $DOMAIN.csr -signkey $DOMAIN.key -out $DOMAIN.crt
fail_if_error $?

echo "moving .cert and .key to the apache2 folder"
mkdir /etc/apache2/certs
mv $DOMAIN.crt /etc/apache2/certs/$DOMAIN.crt
mv $DOMAIN.key /etc/apache2/certs/$DOMAIN.key

echo "backing up openssl.cnf"
[[ ! -f /etc/ssl/openssl_backup.cnf ]] && cp /etc/ssl/openssl.cnf /etc/ssl/openssl_backup.cnf
echo "backing up apache cert"
[[ ! -f /etc/apache2/sites-enabled/000-default_backup.conf ]] && cp /etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default_backup.conf

echo "setting VirtualHost in sites-enabled config"
echo "<VirtualHost *:443>
        
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html
        ServerName $DOMAIN
        SSLEngine on
        SSLCertificateFile certs/$DOMAIN.crt
        SSLCertificateKeyFile certs/$DOMAIN.key
        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
      
</VirtualHost>" > /etc/apache2/sites-enabled/000-default.conf
#enable sslEngine
a2enmod ssl
systemctl restart apache2

