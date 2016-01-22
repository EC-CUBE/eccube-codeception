#!/bin/bash

if [[ $1 = "" ]]
then
    echo "usage: ./run.sh <group list> [<env list>]"
    echo ""
    echo "group list: codeceptionのグループを指定してください（必須）"
    echo "            複数groupを並列テストする場合は,区切りで"
    echo "            複数指定加納です"
    echo "env list  : 指定したグループに対するcodeceptionの環境を指定できます"
    echo "            省略した場合は、グループ名と同じ名前の環境が使われます"
    echo "            tests/acceptance.suite.ymlとtest/acceptance/config.ini"
    echo "            で環境毎(env)の設定を記述しておきます"
fi

### parse first argument for group
groups=( `echo $1 | tr -s ',' ' '`)
gcount=${#groups[@]}

### parse second argument for env
if [[ $2 = "" ]]
then
    envs=("${groups[@]}")
else
    envs=( `echo $2 | tr -s ',' ' '`)
fi
ecount=${#envs[@]}
    
if [ $gcount != $ecount ]
then
    echo "groupの数とenvの数が一致しません。一致するように指定してね"
    exit;
fi
