#!/usr/bin/env bash

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS livewrite;
    GRANT ALL PRIVILEGES ON \`livewrite%\`.* TO '$MYSQL_USER'@'%';
EOSQL
