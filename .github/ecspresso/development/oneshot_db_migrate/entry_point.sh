#!/bin/bash

if [[ "$DB_MIGRATE_APPLY" -eq 1 ]]; then
    echo '[Development] mysqldefでスキーマ変更を実行します'
    mysqldef --config=database/sqldef/config.yml -u${DB_USERNAME} -p${DB_PASSWORD_ROOT} -h ${DB_HOST} ${DB_DATABASE} < database/sqldef/schema_development.sql
else
    echo '[Development] mysqldefでdry runを実行します'
    mysqldef --config=database/sqldef/config.yml --dry-run -u${DB_USERNAME} -p${DB_PASSWORD_ROOT} -h ${DB_HOST} ${DB_DATABASE} < database/sqldef/schema_development.sql
fi
