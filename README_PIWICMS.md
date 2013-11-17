### Rename parameters file

Rename the app/config/parameters.yml.dist to app/config/parameters.yml and fill it with your own parameters.

### Use Composer (*recommended*)

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

### Install vendor files

Download composer (see above) and run the
following command:

    php composer.phar install

### create database

To create the database tables for hotflo 2 run the update method of doctrine:

    php app/console doctrine:schema:update --force

### enable cache writing

    sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

### Run the datafixtures to initially fill the database:

    php app/console doctrine:fixtures:load --append