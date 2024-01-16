#!/bin/bash

# Check if the script is run as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 
   exit 1
fi


: '
NB: Password Needs to be maintained password Policy
Password Policy:
Length Requirement: Minimum length: 15 characters, Maximum length: 20 characters
Character Requirements: At least one uppercase letter (A-Z), At least one lowercase letter (a-z), At least one digit (0-9), At least one special character from the set [@#%^+=]
Forbidden Names: The passwords should not contain certain forbidden names, such as "admin," "superadmin," "cdac," "phpmyadmin," and "ctf."
Uniqueness: Ensure that your password is unique and not the same as any other passwords.
'

# Set the MySQL root password
mysql_root_password="Test@Password123"

# Password for phpmyadmin
phpmyadmin_db_password="Test@Password123&"

# Username and password for securing phpMyAdmin instance
phpmyadmin_instance_username="test"
phpmyadmin_instance_password="TestPassword#123"

# Username & password for users with limited essentials permissions
code_username="codeCTF"
code_user_pass="Test@Password123#"

# CTF superAdmin (Login with phpmyadmin)
admin_username='superadmin'
admin_password='Tes$%t@Password12'

# restrict login to specific host
secure_host='localhost'

# DB name for CTF
ctf_db_name="CDAC-K_CTF"

# Phpmyadmin port number
port_number=9999


# List of variables to check
variables=("mysql_root_password" "phpmyadmin_db_password" "phpmyadmin_instance_username" "phpmyadmin_instance_password" "code_username" "code_user_pass" "admin_username" "admin_password" "secure_host" "ctf_db_name" "port_number")

# Check if any variable is blank
for variable in "${variables[@]}"; do
    if [ -z "${!variable}" ]; then
        echo "$variable is blank. Exiting script."
        exit 1
    fi
done


declare -A encountered_passwords=()

# Password policy check function
function validate_password() {
  local password="$1"

  # Define forbidden names
  local forbidden_names=("admin" "superadmin" "cdac" "phpmyadmin" "ctf")

  # Check forbidden names
  for name in "${forbidden_names[@]}"; do
    if [[ "$password" == *"$name"* ]]; then
      echo "Error: Password \"$password\" contains a forbidden name."
      return 1
    fi
  done

  # Check pattern
  if [ ${#password} -lt 15 ] || [ ${#password} -gt 20 ]; then
    echo "Error: Password \"$password\" length should be between 15 and 20 characters."
    return 1
  fi

  local has_uppercase=$(echo "$password" | grep -q '[A-Z]' && echo true || echo false)
  local has_lowercase=$(echo "$password" | grep -q '[a-z]' && echo true || echo false)
  local has_digit=$(echo "$password" | grep -q '[0-9]' && echo true || echo false)
  local has_special=$(echo "$password" | grep -q '[@#%^+=]' && echo true || echo false)
  local has_double_hash=$(echo "$password" | grep -q '##' && echo true || echo false)

  # Check individual criteria
  if [ "$has_uppercase" != true ] || [ "$has_lowercase" != true ] || [ "$has_digit" != true ] || [ "$has_special" != true ] || [ "$has_double_hash" = true ]; then
    echo "Error: Password \"$password\" does not meet the criteria."
    return 1
  fi

  # Check uniqueness
  if [ -n "${encountered_passwords["$password"]}" ]; then
    echo "Error: Password \"$password\" is not unique."
    return 1
  fi

  # Add encountered password to the track list
  encountered_passwords["$password"]=1

  return 0
}

# Check password policies
if ! validate_password "$mysql_root_password"; then exit 1; fi
if ! validate_password "$phpmyadmin_db_password"; then exit 1; fi
if ! validate_password "$phpmyadmin_instance_password"; then exit 1; fi
if ! validate_password "$code_user_pass"; then exit 1; fi
if ! validate_password "$admin_password"; then exit 1; fi

# Get and store the current pwd
current_directory=$(pwd)

# Update package lists
sudo apt update

# Install Git, Apache2 web server
sudo apt install git apache2 -y

# Stop, start, and enable Apache2 service
sudo systemctl stop apache2.service && sudo systemctl start apache2.service && sudo systemctl enable apache2.service

# Install MariaDB database server
sudo apt install mariadb-server mariadb-client -y

# Stop, start, and enable MariaDB service
sudo systemctl stop mariadb.service && sudo systemctl start mariadb.service && sudo systemctl enable mariadb.service

# Secure MariaDB installation with the static password
echo -e "\nY\n\n${mysql_root_password}\n${mysql_root_password}\n\n\nn\n\n " | mysql_secure_installation

# Install PHP
sudo apt install php php-ldap php-common php-mysql php-gmp php-curl php-intl php-mbstring php-xmlrpc php-gd php-xml php-cli php-zip  -y

# Set debconf selections to automatically configure phpmyadmin with apache2
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | sudo debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $mysql_root_password" | sudo debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $phpmyadmin_db_password" | sudo debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | sudo debconf-set-selections

# Install phpMyAdmin
sudo apt install -y phpmyadmin

# Edit phpmyadmin.conf
phpmyadmin_conf_path="/etc/apache2/conf-available/phpmyadmin.conf"
if [ -e "$phpmyadmin_conf_path" ]; then
    if ! grep -q "AllowOverride All" "$phpmyadmin_conf_path"; then
        sed -i "/<Directory \/usr\/share\/phpmyadmin>/,/<\/Directory>/ s/DirectoryIndex index.php/DirectoryIndex index.php\n    AllowOverride All/" "$phpmyadmin_conf_path"
    fi
fi

# Create .htaccess file
htaccess_path="/usr/share/phpmyadmin/.htaccess"
echo "AuthType Basic" > "$htaccess_path"
echo 'AuthName "Restricted Files"' >> "$htaccess_path"
echo "AuthUserFile /etc/phpmyadmin/.htpasswd" >> "$htaccess_path"
echo "Require valid-user" >> "$htaccess_path"

# Create .htpasswd file if it doesn't exist
htpasswd_path="/etc/phpmyadmin/.htpasswd"
if [ ! -f "$htpasswd_path" ]; then
    htpasswd -bc "$htpasswd_path" "$phpmyadmin_instance_username" "$phpmyadmin_instance_password"
else
    htpasswd -b "$htpasswd_path" "$phpmyadmin_instance_username" "$phpmyadmin_instance_password"
fi

# Create the phpmyadmin.conf file for changing port
phpmyadmin_conf="/etc/apache2/sites-available/phpmyadmin.conf"
echo "
Listen $port_number

<VirtualHost *:$port_number>
    ServerName localhost
    DocumentRoot /usr/share/phpmyadmin

    <Directory /usr/share/phpmyadmin>
        Options Indexes FollowSymLinks
        DirectoryIndex index.php
        AllowOverride All
        Require all granted
    </Directory>

    Include /etc/phpmyadmin/apache.conf

    ErrorLog \${APACHE_LOG_DIR}/phpmyadmin.error.log
    CustomLog \${APACHE_LOG_DIR}/phpmyadmin.access.log combined
</VirtualHost>
" > "$phpmyadmin_conf"

# Disable old sites if any
sudo a2disconf phpmyadmin.conf

# Reload Apache2
sudo systemctl reload apache2

# Enable site
sudo a2ensite phpmyadmin.conf

# Reload Apache2 once again
sudo systemctl reload apache2

# Restart Apache
sudo systemctl restart apache2

# Log in to MySQL, create a database for CTF, add user for CTF with limited permission, change root password
echo "CREATE DATABASE \`${ctf_db_name}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
"GRANT ALL ON \`${ctf_db_name}\`.* TO '${admin_username}'@'${secure_host}' IDENTIFIED BY '${admin_password}';" \
"GRANT SELECT, INSERT, UPDATE ON \`${ctf_db_name}\`.* TO '${code_username}'@'localhost' IDENTIFIED BY '${code_user_pass}';" \
"ALTER USER 'root'@'localhost' IDENTIFIED BY '${mysql_root_password}';" \
"FLUSH PRIVILEGES;" | sudo mysql -u root

# Clone the repo
git clone https://github.com/ISS-CDACK/CCTF.git

# Edit config.php inside CCTF
config_php_path="${current_directory}/CCTF/config.php"

sed -i "s/\$username = \"ctf_code_USER\";/\$username = \"$code_username\";/g" $config_php_path
sed -i "s/\$password = \"ctf\";/\$password = \"$code_user_pass\";/g" $config_php_path
sed -i "s/\$database = \"CDAC-K_CTF\";/\$database = \"$ctf_db_name\";/g" $config_php_path


# Remove .git folder
rm -rf "${current_directory}/CCTF/.git"

# Clear html folder 
[ -d /var/www/html/* ] && rm -r /var/www/html/*

# Copy all contents of CCTF folder including hidden files and folders to html
cp -r "${current_directory}/CCTF/." /var/www/html/

# Remove CCTF
rm -rf "${current_directory}/CCTF"

# Enable htaccess rule
sudo sed -i '/<Directory \/var\/www\/>/,/AllowOverride/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

#Enable rewrite rule
sudo a2enmod rewrite

#Restart apache2
sudo service apache2 restart

# Set correct permissions on files
chmod 644 "$htaccess_path"
chmod 644 "$htpasswd_path"

sudo chown -R www-data:www-data /var/www/html
sudo chmod -R g+w /var/www/html
