# willone-agent-web

# ローカル環境構築手順

## 初回の実行

### 自己署名証明書の発行

```shell
brew install mkcert
mkcert -install
mkcert -key-file docker/woa-rproxy/etc/nginx/ssl/_wildcard.willoneagent.smsx.io-key.pem \
  -cert-file docker/woa-rproxy/etc/nginx/ssl/_wildcard.willoneagent.smsx.io.pem "*.willoneagent.smsx.io"
```

### ローカル環境の操作コマンド体系

```shell
$ make
make help: このページを表示
make db_init: データベース初期化を行う
make init: データベース初期化を行い、全てのコンテナを起動
make start: 全てのコンテナを起動
make stop: 全てのコンテナを停止
make down: 全てのコンテナを削除する(MySQLのデータは消えません)
make restart: 全てのコンテナを再起動する
make sqldef: データベースのマイグレーションのdryrunと実行をする
make development_batch_verify: Development環境でのデプロイ前に動作確認を行う
make production_sqldef: Production環境のDBマイグレーション
make production_rollback_ecs: Production環境のECSロールバック
make db_backup: mysqldumpを出力する
make query_log:: MySQL general query logを表示する

よくある操作手順はこちら: https://sms-engineer.esa.io/posts/31727
```

### 初回起動

このコマンドは初回のみ。  
以降はmake start等の利用を想定。

```shell
make init
```

### 起動

```shell
make start
```

### 停止

```shell
make stop
```

### 再起動

```shell
make restart
```

### MySQLのクエリ実行ログを表示

```shell
make glog
```

### 起動後
WEB: https://ldev.willoneagent.smsx.io

Mail: https://lmail.willoneagent.smsx.io

phpMyAdmin: http://localhost:8080


### MySQLのデータ削除
```shell
docker volume rm willone-agent-web_mysql_data
```