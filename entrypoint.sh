#!/bin/ash

sed -i "s/^Listen.*/Listen $ITFLOW_PORT/g" /etc/apache2/httpd.conf

# Set up git configuration
git config --global --add safe.directory /var/www/localhost/htdocs

# Persist config.php and uploads to volume
mkdir -p /var/itflow-data

# Ensure persistent data directory has correct ownership
chown -R apache:apache /var/itflow-data

# Link persistent config.php if it exists
if [[ -f /var/itflow-data/config.php ]]; then
    ln -sf /var/itflow-data/config.php /var/www/localhost/htdocs/config.php
fi

# Link persistent uploads directory
if [[ -d /var/itflow-data/uploads ]]; then
    rm -rf /var/www/localhost/htdocs/uploads
    ln -sf /var/itflow-data/uploads /var/www/localhost/htdocs/uploads
fi

# Set permissions on itflow repository (must happen AFTER symlinks are created)
chown -R apache:apache /var/www/localhost/htdocs

# This updates the config.php file once initialization through setup.php has completed
if [[ -f /var/www/localhost/htdocs/config.php ]]; then 
    # Company Name
    sed -i "s/\$config_app_name.*';/\$config_app_name = '$ITFLOW_NAME';/g" /var/www/localhost/htdocs/config.php

    # MariaDB Host
    sed -i "s/\$dbhost.*';/\$dbhost = '$ITFLOW_DB_HOST';/g" /var/www/localhost/htdocs/config.php

    # Database Password
    sed -i "s/\$dbpassword.*';/\$dbpassword = '$ITFLOW_DB_PASS';/g" /var/www/localhost/htdocs/config.php

    # Base URL - should be domain only without protocol
    BASE_URL="${ITFLOW_URL#http://}"
    BASE_URL="${BASE_URL#https://}"
    sed -i "s|\$config_base_url.*';|\$config_base_url = '$BASE_URL';|g" /var/www/localhost/htdocs/config.php

    # Repo Branch (used by ITFlow's update functionality)
    sed -i "s/\$repo_branch.*';/\$repo_branch = '$ITFLOW_REPO_BRANCH';/g" /var/www/localhost/htdocs/config.php
    
    find /var/www/localhost/htdocs -type d -exec chmod 775 {} \;
    find /var/www/localhost/htdocs -type f -exec chmod 664 {} \;
    chmod 640 /var/www/localhost/htdocs/config.php
    
    # Copy config to persistent storage
    cp -f /var/www/localhost/htdocs/config.php /var/itflow-data/config.php
else 
    chmod -R 777 /var/www/localhost/htdocs
fi

# Ensure uploads directory is persisted
if [[ ! -d /var/itflow-data/uploads ]]; then
    mv /var/www/localhost/htdocs/uploads /var/itflow-data/uploads 2>/dev/null || mkdir -p /var/itflow-data/uploads
    ln -sf /var/itflow-data/uploads /var/www/localhost/htdocs/uploads
    # Uploads moved, fix ownership
    chown -R apache:apache /var/itflow-data/uploads
fi

# Start Cron
crond &

# Execute the command in the dockerfile's CMD
exec "$@"
