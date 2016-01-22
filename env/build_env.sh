#!/bin/sh

### check requirement
# php
isExistPHP=`php -v`
if [[ $isExistPHP =~ ^PHP[^\(]*\(cli\).*$ ]]
then
    echo "PHP: OK";
else
    echo "PHPがインストールされていないみたいだよ。インストールしてね";
fi

# docker daemon
isExistDocker=`ps -ef | grep "docker" | grep -v grep | wc -l`
if [ $isExistDocker = 0 ]
then
    echo "docker daemonが動いてないよ。インストールと起動を確認してね";
    exit;
else
    echo "docker daemon: OK";
fi

# git
isExistGit=`git --version`
if [[ $isExistGit =~ ^git\ version.*$ ]]
then
    echo "git: OK";
else
    echo "gitがインストールされていないみたいだよ。インストールしてね";
fi

# exist docker image of phantomjs
isExistPhantomJSImage=`docker images | grep "davert/phantomjs-env" | grep -v grep | wc -l`
if [ $isExistPhantomJSImage = 0 ]
then
    echo "PhantomJS用のdocker imageがないので作るよ";
    docker pull davert/phantomjs-env;
else
    echo "Docker Image of PhantomJS: OK";
fi

# exist docker image of eccube3
isExistECCUBEImage=`docker images | grep "ec-cube/ec-cube3" | grep -v grep | wc -l`
if [ $isExistECCUBEImage = 0 ]
then
    echo "ECCUBE3用のdocker imageがないので作るよ";
    echo "PHPのversion指定して、docker buildするからわりと時間かかるよ";
    
    # update Dockerfile for PHP version
    read -p "PHPのバージョン指定[5.5|5.6|7.0] (See: https://hub.docker.com/_/php/): " php
    cmd="sed -i -e '1c\FROM php:"$php"-apache' docker/Dockerfile"
    eval $cmd

    # update Dockerfine & conf/apache2.conf
    read -p "codeceptionテストを動かすユーザー: " username
    cmd='id '$username
    idstr=`eval $cmd`
    cmd="echo '"$idstr"' | sed -e 's/^uid=\([^\(]*\).*\ gid=\([^\(]*\)(\([^\)]*\)).*$/\1,\2,\3/'"
    idstr=`eval $cmd`
    ids=( `echo $idstr | tr -s ',' ' '`)

    cmd="sed -i -e '5c\ENV APACHEUSER "$username"' docker/Dockerfile"
    eval $cmd
    cmd="sed -i -e '6c\ENV APACHEUSERID "${ids[0]}"' docker/Dockerfile"
    eval $cmd
    cmd="sed -i -e '7c\ENV APACHEGROUP "${ids[2]}"' docker/Dockerfile"
    eval $cmd
    cmd="sed -i -e '8c\ENV APACHEGROUPID "${ids[1]}"' docker/Dockerfile"
    eval $cmd

    cmd="sed -i -e '9c\User "$username"' docker/config/apache2.conf"
    eval $cmd
    cmd="sed -i -e '10c\Group "${ids[2]}"' docker/config/apache2.conf"
    eval $cmd
    
    # build docker image
    docker build --rm -t ec-cube/ec-cube3 docker >/dev/null 2>&1
else
    echo "Docker Image of ECCUBE3: OK";
fi

### specify number of container
read -p "コンテナの数（並列テストする数）を指定[1以上]: " connum
if [ $connum -lt "1" ] 
then
    connum="1"
fi

### run phantomjs container
for (( i = 1 ; i <= $connum ; i++ ))
do
    cmd="docker ps | grep 'client"$i"' | grep -v grep | wc -l"
    count=`eval $cmd`
    if [ $count = 0 ]
    then
        cmd="docker ps -a | grep 'client"$i"' | grep -v grep | wc -l"
        count=`eval $cmd`
        if [ $count = 0 ]
        then
            # コンテナないからつくるよ
            echo "create PhantomJS Container $i..."; 
            cmd="docker run -d --name client"$i" -p 1000"$i":4444 davert/phantomjs-env >/dev/null 2>&1"
            eval $cmd
            echo "PhantomJS Container $i: OK"; 
        else
            # コンテナあるけど止まってるから動かすよ 
            echo "start PhantomJS Container $i..."; 
            cmd="docker start client"$i" >/dev/null 2>&1"
            eval $cmd
            echo "PhantomJS Container $i: OK"; 
        fi
    else
        echo "PhantomJS Container $i: OK"; 
    fi
done

### prepare eccube3
read -p "このホストのホスト名(IPアドレス): " hostname
read -p "ECCUBE3のタグ[3.0.0 <] (See: https://github.com/EC-CUBE/ec-cube/tags): " eccubetag
read -p "ECCUBE3をインストールするディレクトリ（最後は/を付けてね）: " cubedir
orgdir=`pwd`

for (( i = 1 ; i <= $connum ; i++ ))
do
    if [ ! -e $cubedir"cube3-"$i ]
    then
        # ディレクトリごとない...
        echo "clone ECCUBE3 [cube3-$i]...";
        cmd="git clone -b ${eccubetag} https://github.com/EC-CUBE/ec-cube.git ${cubedir}cube3-${i} >/dev/null 2>&1"
        eval $cmd
    fi
    echo "ECCUBE3 Dir [cube3-$i]: OK";
    if [ ! -e $cubedir"cube3-"$i"/composer.phar" ]
    then
        # composer.pharがない
        echo "get composer.phar [cube3-$i]...";
        cd ${cubedir}cube3-${i}
        curl -sS https://getcomposer.org/installer | php >/dev/null 2>&1
        cd ${orgdir}
    fi
    echo "ECCUBE3 composer.phar [cube3-$i]: OK";
    if [ ! -e $cubedir"cube3-"$i"/vendor" ]
    then
        # composer installされてない
        echo "composer.phar install [cube3-$i]...";
        cd ${cubedir}cube3-${i}
        php ./composer.phar install --dev --no-interaction >/dev/null 2>&1
        cd ${orgdir}
    fi
    echo "ECCUBE3 composer install [cube3-$i]: OK";

    chmod a+w $cubedir"cube3-"$i"/html"
    chmod a+w $cubedir"cube3-"$i"/app"
    chmod a+w $cubedir"cube3-"$i"/app/log"
    chmod a+w $cubedir"cube3-"$i"/app/template"
    chmod a+w $cubedir"cube3-"$i"/app/cache"
    chmod a+w $cubedir"cube3-"$i"/app/config"
    chmod a+w $cubedir"cube3-"$i"/app/config/eccube"
    chmod a+w $cubedir"cube3-"$i"/app/Plugin"
    echo "ECCUBE3 change some files attribute [cube3-$i]: OK";
done

### run ec-cube3 container
portstr=""
for (( i = 1 ; i <= $connum ; i++ ))
do
    cmd="docker ps | grep 'cube3-"$i"' | grep -v grep | wc -l"
    count=`eval $cmd`
    if [ $count = 0 ]
    then
        cmd="docker ps -a | grep 'cube3-"$i"' | grep -v grep | wc -l"
        count=`eval $cmd`
        if [ $count = 0 ]
        then
            # コンテナないからつくるよ
            cmd="docker run -d -v "$cubedir"cube3-"$i":"$cubedir"cube3-"$i" -e CUBEID="$i" --name cube3-"$i" -p 808"$i":80 ec-cube/ec-cube3 >/dev/null 2>&1"
            eval $cmd
        else
            # コンテナあるけど止まってるから動かすよ 
            cmd="docker start cube3-"$i" >/dev/null 2>&1"
            eval $cmd
        fi
    fi

    # nginx用confファイルを作るよ
    cmd="sed -e '2c\    listen 888"$i";' nginx-eccube.tmp > cubu3-"$i".conf"
    eval $cmd
    cmd="sed -i -e '3c\    server_name "$hostname";' cubu3-"$i".conf"
    eval $cmd
    cmd="sed -i -e '7c\        proxy_pass http://localhost:808"$i"/;' cubu3-"$i".conf"
    eval $cmd
    if [[ $portstr != "" ]]
    then
        portstr=$portstr"|"
    fi
    portstr=$portstr"888"$i
done

echo ""
echo "テスト環境構築準備完了です"
echo ""
echo "作成したECCUBE3コンテナ用にコンテナ数分("$connum"個)のデータベースを用意してください"
echo ""
echo "データベースの用意ができたら、外部からこのホストの各ポート["$portstr"]に"
echo "httpでアクセスしてECCUBE3のセットアップを行ってくださいね"
echo "（テストを実行する前には必ずinstall.phpでデータベースを初期化する必要があります）"
echo ""
echo "テスト実行前に以下の設定も確認してくださいね。"
echo "各環境(env)に関する設定値を確認＆設定するのがポイントです"
echo ""
echo "　・codeceptionの設定ファイル:     tests/acceptance.suite.yml"
echo "　・acceptance testの設定ファイル: tests/acceptance/config.ini"
echo ""
echo "それでは...Enjoy Testing! ;)"
exit;
