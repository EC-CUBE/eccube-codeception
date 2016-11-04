# EC-CUBE3 Continuous Delivery

[![Build Status](https://travis-ci.org/EC-CUBE/eccube-codeception.svg?branch=master)](https://travis-ci.org/EC-CUBE/eccube-codeception)

* [EC-CUBE3](https://github.com/EC-CUBE/ec-cube)の開発における継続的デリバリー環境構築用


## 実行方法

以下のコマンドで、 PostgreSQL, Selenium/Firefox, EC-CUBE3, Codeception の各コンテナが生成され、テストを実行します。
`--env default` は 必ず指定して下さい。(変更方法は後述)
テストレポートは `tests/_output` 以下へ保存されます。

```
docker-compose run --rm codecept run -d --env default --html report.html
```

### テスト対象 EC-CUBE バージョンの変更方法

`docker-compose.yml` の `ECCUBE_BRANCH`, `ECCUBE_REPOS` を変更すると、お好みのリポジトリ、ブランチを使用できます。
`ECCUBE_BRANCH` には、タグも使用できます。
バージョンを変更した場合は、 `docker-compose build --no-cache` でイメージを作成し直してください。

```
docker-compose build --no-cache
docker-compose run --rm codecept run -d --env default --html report.html
```

ローカルでの確認用にポートを固定したい場合は、`docker-compose.dev.yml`も読み込んでください。(並列実行時には利用できません。)
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml run --rm codecept run -d --html report.html
```

### 並列実行

`--project-name <project name> run -d` オプションで並列実行が可能です。

```
docker-compose --project-name front run -d --rm codecept run -d -g front --env default  --html report-front.html & \
  docker-compose --project-name admin01 run -d --rm codecept run -d -g admin01 --env default --html report-admin01.html & \
  docker-compose --project-name admin02 run -d --rm codecept run -d -g admin02 --env default --html report-admin02.html
```

`logs -f` で実行中のログを参照できます。

```
docker-compose --project-name front logs -f
```

### コンテナの削除

本テスト環境は、データベースをロールバックしません。
再実行する場合は、コンテナの削除をおすすめします。

```
docker-compose stop
docker-compose rm
```

`--project-name` を指定した場合は、 `--project-name` ごとに削除してください。

```
docker-compose --project-name front stop
docker-compose --project-name front rm
```

### 実行中の Selenium へアクセス

テストを実行中の Selenium へ VNC を使用してアクセスが可能です。

`docker ps` で、`selenium/*` の IMAGE のポートにアクセスします。

```
docker ps

CONTAINER ID        IMAGE                                      COMMAND                  CREATED             STATUS              PORTS                               NAMES
d8c3d147e103        front_codecept                             "codecept run -d -g f"   5 minutes ago       Up 5 minutes                                            front_codecept_run_1
f5377e65ea82        front_eccube3                              "/wait-for-postgres.s"   5 minutes ago       Up 5 minutes        0.0.0.0:32804->80/tcp               front_eccube3_1
1dfeff84330c        postgres:9.4                               "/docker-entrypoint.s"   5 minutes ago       Up 5 minutes        5432/tcp                            front_postgres_1
9f3c7bcc17f9        selenium/standalone-firefox-debug:2.53.1   "/opt/bin/entry_point"   5 minutes ago       Up 5 minutes        4444/tcp, 0.0.0.0:32803->5900/tcp   front_firefox_1
```

上記の例の場合は、 `vnc://127.0.0.1::32803` へアクセスします。初期パスワードは `secret` です。
Mac の場合は、 `⌘ + k` で画面共有、 Windows の場合は [TightVNC viewer](http://www14.plala.or.jp/campus-note/vine_linux/server_vnc/tightvnc.html) などを使用すると良いでしょう。

### 異なる環境でのテスト

`docker-compose -f` で `docker-compose.<browser>.yml` 及び `docker-compose.<db>.yml` をオーバーライドすることで、デフォルト以外のブラウザ, データベースでもテスト可能です。
この場合、 Codeception の `--env` オプションにもブラウザ種別, データベースを指定してください。

*現在のところ、PhantomJS でのテストは JavaScript alert の箇所で失敗してしまいます*

```
### Chrome, MySQL
docker-compose -f docker-compose.yml -f docker-compose.chrome.yml -f docker-compose.mysql.yml --project-name chrome_mysql run --rm codecept run -d --env chrome,mysql --html report_chrome.html

### PhantomJS, PostgreSQL
docker-compose -f docker-compose.yml -f docker-compose.phantomjs.yml -f docker-compose.pgsql.yml --project-name phantomjs_pgsql run --rm codecept run -d --env phantomjs,pgsql --html report_phantomjs.html
```

ブラウザ種別は、以下を選択可能です。

- firefox
- chrome
- phantomjs

データベースは、以下を選択可能です。

- pgsql
- mysql

## Status

- 2016/10/26 Travis CI にて並列テスト実行環境構築
- 2015/11/27 本repository作成および並列テスト実行環境に関して検証中
- 2015/11/20 EC-CUBE3 Ver.3.0.6に対応(Acception Test記述: フロント側 100% / 管理画面 40%)
- 2015/11/13 [eccube3-doc](https://github.com/EC-CUBE/eccube3-doc)のIntegrationTestにあるテスト項目を順次Acception Testとして記述
- 2015/11/06 Acception Test作成に[Codeception](http://codeception.com/)を採用

## In near future...

- 【Acceptance Test】Acceptance Test記述を完了
- 【Acceptance Test】環境構築用ドキュメント作成
- 【Deployment - Bootstrapping / Configuration】AWSなど各種クラウド環境へのEC-CUBE3自動デプロイ手法確立
- 【Deployment - Bootstrapping / Configuration】EC-CUBE3自動デプロイ手法実装


## See Also.

- [Codeception Parallel Execution](http://codeception.com/docs/12-ParallelExecution)
- [Acceptance Tests Demo Repository](https://github.com/dmstr/docker-acception)
- [Docker Yii 2.0 Application](https://github.com/dmstr/docker-yii2-app)
