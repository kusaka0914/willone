.PHONY: help init start stop restart down glog

AWS_PROFILE_DEVELOPMENT := "xjb-development"
AWS_PROFILE_PRODUCTION := "xjb-production"
TAG_NAME := $(shell date +%Y%m%d%H%M%S)

help: ## このページを表示
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo
	@echo 'よくある操作手順はこちら: https://sms-engineer.esa.io/posts/31727'

db_init:
	docker compose up woa-db -d
	sleep 30
	mysqldef --config=database/sqldef/config.yml -u root -proot -h 127.0.0.1 dev_woa < database/sqldef/schema_development.sql
	mysql -uroot -proot -h 127.0.0.1 dev_woa < ./database/sql/dump_local.sql

init: db_init start
	@open https://dev.willoneagent.smsx.io
	@open https://mail.willoneagent.smsx.io
	@open http://localhost:8080

start: ## 全てのコンテナを起動
	docker build -t woa-web-base -f ./docker/woa-web/base.dockerfile .
	docker compose build
	docker compose up -d
	docker exec -t woa-web /bin/bash -c "composer install"
	docker exec -t woa-web /bin/bash -c "php artisan cache:clear"
	docker exec -t woa-web /bin/bash -c "php artisan view:clear"
	docker exec -t woa-web /bin/bash -c "php artisan config:cache"
	docker exec -t woa-web /bin/bash -c "rm -f bootstrap/cache/config.php"
	docker exec -t woa-web /bin/bash -c "chown -R www-data. /var/www/html/storage/logs"
	docker exec -t woa-web /bin/bash -c "chown -R www-data. /var/www/html/storage/"

stop: ## 全てのコンテナを停止
	docker compose stop

down: ## 全てのコンテナを削除する(MySQLのデータは消えません)
	docker compose down

restart: ## 全てのコンテナを再起動する
	$(MAKE) stop
	$(MAKE) start

sqldef: ## ローカル環境にdatabase/sqldef/schema_development.sqlを実行する
	@printf "sqldefによる dryrun を実行します \n"
	@mysqldef --config=database/sqldef/config.yml --dry-run -u root -proot -h 127.0.0.1 dev_woa < database/sqldef/schema_development.sql
	@printf "sqldefを実行しますか? (yes/no): "
	@read answer; \
	if [ "$$answer" = "yes" ]; then \
		mysqldef --config=database/sqldef/config.yml -u root -proot -h 127.0.0.1 dev_woa < database/sqldef/schema_development.sql; \
		exit; \
	else \
		echo 'sqldefの実行をキャンセルしました'; \
	fi

development_batch_verify:
	@printf "Developmentのクレデンシャル情報を設定します \n"
	@aws sts get-caller-identity --profile $(AWS_PROFILE_DEVELOPMENT); \
		RC=$$? && \
		if [ $$RC -ne 0 ]; then \
			aws sso login --profile $(AWS_PROFILE_DEVELOPMENT); \
		fi
	@printf "Imageの作成とECRへのPushを行います \n"
	@aws ecr get-login-password --region ap-northeast-1 --profile $(AWS_PROFILE_DEVELOPMENT) | docker login --username AWS --password-stdin 967125328503.dkr.ecr.ap-northeast-1.amazonaws.com
	@docker build -t 967125328503.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:$(TAG_NAME) --platform linux/amd64 -f docker/woa-web/Dockerfile .
	@docker push 967125328503.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:$(TAG_NAME)
	@printf "実行したいコマンドを入力してください \n"
	@while true; do \
		read command; \
			if [ "$$command" != "quit" ]; then \
				export AWS_PROFILE=$(AWS_PROFILE_DEVELOPMENT) && \
				ecspresso run --config .github/ecspresso/development/oneshot_batch/ecspresso.yml --ext-str "COMMAND=$$command" --ext-str "TAG=$(TAG_NAME)"; \
				printf "継続して実行したい場合はコマンドを入力してください \n"; \
				printf "終了したい場合は quit と入力してください \n" ; \
			else \
				break; \
			fi; \
	done

production_sqldef: ## 本番環境にdatabase/sqldef/schema_production.sqlを実行する。
	@if [ $(shell git branch --show-current) = "main" ]; then \
		printf "Productionのクレデンシャル情報を設定します \n"; \
		aws sts get-caller-identity --profile $(AWS_PROFILE_PRODUCTION); \
		RC=$$? && \
		if [ $$RC -ne 0 ]; then \
			aws sso login --profile $(AWS_PROFILE_PRODUCTION); \
		fi ;\
		printf "Imageの作成とECRへのPushを行います \n"; \
		aws ecr get-login-password --region ap-northeast-1 --profile $(AWS_PROFILE_PRODUCTION) | docker login --username AWS --password-stdin 622803604774.dkr.ecr.ap-northeast-1.amazonaws.com ; \
		docker build -t 622803604774.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:$(TAG_NAME) --platform linux/amd64 -f docker/woa-web/Dockerfile . ; \
		docker push 622803604774.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:$(TAG_NAME) ; \
		printf "sqldefでproduction環境に dryrun を実行します \n"; \
		export AWS_PROFILE=$(AWS_PROFILE_PRODUCTION) && \
			ecspresso run --config .github/ecspresso/production/oneshot_db_migrate/ecspresso.yml --ext-str "DB_MIGRATE_APPLY=0" --ext-str "TAG=$(TAG_NAME)"; \
		printf "sqldefでproduction環境に Apply を実行しますか? (yes/no): "; \
		read answer; \
		if [ "$$answer" = "yes" ]; then \
			export AWS_PROFILE=$(AWS_PROFILE_PRODUCTION) && \
				ecspresso run --config .github/ecspresso/production/oneshot_db_migrate/ecspresso.yml --ext-str "DB_MIGRATE_APPLY=1" --ext-str "TAG=$(TAG_NAME)"; \
		else \
	    	echo 'sqldefでproduction環境への Apply をキャンセルしました'; \
		fi; \
	else \
		printf "mainブランチで実行してください  \n"; \
	fi

production_rollback_ecs: ## 本番環境のECSをロールバックする
	@printf "Productionのクレデンシャル情報を設定します \n"
	@aws sts get-caller-identity --profile $(AWS_PROFILE_PRODUCTION); \
		RC=$$? && \
		if [ $$RC -ne 0 ]; then \
			aws sso login --profile $(AWS_PROFILE_PRODUCTION); \
		fi
	@printf "ecspressoでproduction環境のECSをロールバックしますか? (yes/no): "
	@read answer; \
	if [ "$$answer" = "yes" ]; then \
		export AWS_PROFILE=$(AWS_PROFILE_PRODUCTION) && \
			ecspresso rollback --config .github/ecspresso/production/service_webapp/ecspresso.yml; \
	else \
	    echo 'ecspressoでのロールバックをキャンセルしました'; \
	fi

db_backup: ## mysqldumpをdocker/woa-db/backupに出力する
	docker run --rm --network willone-agent-web_default -v "$(shell pwd)/docker/woa-db/backup:/backup" mysql:5.7 bash -c "mysqldump -uroot -proot -h woa-db dev_woa > /backup/mysqldump_$(shell date +%Y%m%d_%H%M%S).sql"

query_log: ## MySQL general query logを表示する
	docker exec -it woa-db bash -c 'tail -f /tmp/mysql-general.log'
