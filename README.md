# EC-CUBE3 Continuous Delivery

[![Build Status](https://travis-ci.org/EC-CUBE/eccube-codeception.svg?branch=master)](https://travis-ci.org/EC-CUBE/eccube-codeception)

* [EC-CUBE3](https://github.com/EC-CUBE/ec-cube)の開発における継続的デリバリー環境構築用


## 実行方法

以下のコマンドで、 PostgreSQL, EC-CUBE3, Codeception の各コンテナが生成され、テストを実行します。
テストレポートは `tests/_output` 以下へ保存されます。

```
docker-compose run --rm codecept run -d --html report.html
```

### テスト対象 EC-CUBE バージョンの変更方法

`docker-compose.yml` の `ECCUBE_BRANCH`, `ECCUBE_REPOS` を変更すると、お好みのリポジトリ、ブランチを使用できます。
`ECCUBE_BRANCH` には、タグも使用できます。
バージョンを変更した場合は、 `docker-compose build --no-cache` でイメージを作成し直してください。

```
docker-compose build --no-cache
docker-compose run --rm codecept run -d --html report.html
```

### 並列実行

`--project-name <project name> run -d` オプションで並列実行が可能です。

```
docker-compose --project-name front run -d --rm codecept run -d -g front --env front --html report-front.html & \
  docker-compose --project-name admin01 run -d --rm codecept run -d -g admin01 --env admin01 --html report-admin01.html & \
  docker-compose --project-name admin02 run -d --rm codecept run -d -g admin02 --env admin02 --html report-admin02.html
```

`logs -f` で実行中のログを参照できます。

```
docker-compose --project-name front logs -f
```

* Status
    * 2016/10/26 Travis CI にて並列テスト実行環境構築
    * 2015/11/27 本repository作成および並列テスト実行環境に関して検証中
    * 2015/11/20 EC-CUBE3 Ver.3.0.6に対応(Acception Test記述: フロント側 100% / 管理画面 40%)
    * 2015/11/13 [eccube3-doc](https://github.com/EC-CUBE/eccube3-doc)のIntegrationTestにあるテスト項目を順次Acception Testとして記述
    * 2015/11/06 Acception Test作成に[Codeception](http://codeception.com/)を採用
* In near future...
    * 【Acceptance Test】PostgreSQL/MySQL/Firefox/Chrome 各環境の並列テスト実行環境構築
    * 【Acceptance Test】Acceptance Test記述を完了
    * 【Acceptance Test】環境構築用ドキュメント作成
    * 【Deployment - Bootstrapping / Configuration】AWSなど各種クラウド環境へのEC-CUBE3自動デプロイ手法確立
    * 【Deployment - Bootstrapping / Configuration】EC-CUBE3自動デプロイ手法実装
