<?php
use Codeception\Util\Fixtures;
use Faker\Factory as Faker;

$config = parse_ini_file('tests/acceptance/config.ini',true);

/**
 * envで指定された値を読み、config.iniなどのの切り替えに使う
 */
$argv = $_SERVER['argv'];
$check = false;
$env = '';
foreach ($argv as $arg) {
    if (!$check && $arg == '--env') {
        $check = true;
        continue;
    }
    if ($check) {
        $env = $arg;
        break;
    }
}
if ($env != '') {
    if (isset($config[$env])) {
        $config['eccube_path'] = $config[$env]['eccube_path'];
        $config['hostname'] = $config[$env]['hostname'];
        $config['db'] = $config[$env]['db'];
        $config['dbhost'] = $config[$env]['dbhost'];
        $config['dbport'] = $config[$env]['dbport'];
        $config['user'] = $config[$env]['user'];
        $config['password'] = $config[$env]['password'];
        $config['charset'] = $config[$env]['charset'];
    }
}

/**
 * create fixture
 * このデータは$appを使って直接eccubeのデータベースに作成される
 * よってCodeceptionの設定によってコントロールされず、テスト後もデータベース内にこのデータは残る
 * データの件数によって、作成するかどうか判定される
 */
require_once $config['eccube_path'].'autoload.php';
use Symfony\Component\Yaml\Yaml;
$dbyml = $config['eccube_path'].'app/config/eccube/database.yml';
$database = $database_org = Yaml::parse(file_get_contents($dbyml));
$database['database']['dbname'] = $config['db'];
$database['database']['host'] = $config['dbhost'];
$database['database']['port'] = ($config['dbport']) ? $config['dbport'] : null;
$database['database']['user'] = $config['user'];
$database['database']['password'] = ($config['password']) ? $config['password'] : '';
$database['database']['charset'] = $config['charset'];
file_put_contents($dbyml,Yaml::dump($database));

$app = Eccube\Application::getInstance();
// Disable to TransactionListener.
$app->setTestMode(true);
$app->initialize();
$app->initializePlugin();
$app->register(new \Eccube\Tests\ServiceProvider\FixtureServiceProvider());
$app->run();
Fixtures::add('app', $app);
file_put_contents($dbyml,Yaml::dump($database_org));

use Eccube\Common\Constant;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\CustomerStatus;


$num = $app['orm.em']->getRepository('Eccube\Entity\Customer')
    ->createQueryBuilder('o')
    ->select('count(o.id)')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getSingleScalarResult();
if ($num < $config['fixture_customer_num']) {
    $num = $config['fixture_customer_num'] - $num;
    for ($i = 0; $i < $num; $i++) {
        $customer = createCustomer($app);
        $order = createOrder($app, $customer);
    }
    createCustomer($app, null, false); // non-active member
}

$num = $app['orm.em']->getRepository('Eccube\Entity\Product')
    ->createQueryBuilder('o')
    ->select('count(o.id)')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getSingleScalarResult();
if($num==($config['fixture_customer_num']+2)) {
    for ($i = 0; $i < $config['fixture_product_num']; $i++) {
        createProduct($app);
    }
}
createProduct($app, '規格なし商品', 0);

function createCustomer($app, $email = null, $active = true)
{
    $Customer = $app['eccube.fixture.generator']->createCustomer($email);
    if ($active) {
        $Status = $app['orm.em']->getRepository('Eccube\Entity\Master\CustomerStatus')->find(CustomerStatus::ACTIVE);
    } else {
        $Status = $app['orm.em']->getRepository('Eccube\Entity\Master\CustomerStatus')->find(CustomerStatus::NONACTIVE);
    }
    $Customer->setStatus($Status);
    $app['orm.em']->flush($Customer);
    return $Customer;
}

function createProduct($app, $product_name = null, $product_class_num = 3)
{
    return $app['eccube.fixture.generator']->createProduct($product_name, $product_class_num);
}

function createOrder($app, Customer $Customer)
{
    $Order = $app['eccube.fixture.generator']->createOrder($Customer);
    $Order->setOrderStatus($app['eccube.repository.order_status']->find($app['config']['order_new']));
    $app['orm.em']->flush($Order);
    return $Order;
}

/**
 * fixtureとして、対象eccubeのconfigおよびデータベースからデータを取得する
 * [codeception path]/tests/acceptance/config.iniに対象eccubeのpathを記述すること
 * つまり、対象eccubeとcodeception作業ディレクトリはファイルシステム上で同一マシンにある（様にみえる）ことが必要
 * fixtureをテスト内で利用する場合は、Codeception\Util\Fixtures::getメソッドを使う
 * ちなみに、Fixturesとは関係なく、CodeceptionのDbモジュールで直接データベースを利用する場合は、
 * [codeception path]/codeception.ymlのDbセクションに対象eccubeで利用しているデータベースへの接続情報を記述して利用する
 */
Fixtures::add('admin_account',array(
    'member' => $config['admin_user'],
    'password' => $config['admin_password'],
));
Fixtures::add('config', $app['config']);
Fixtures::add('test_config', $config);

$baseinfo = $app['orm.em']->getRepository('Eccube\Entity\BaseInfo')
    ->createQueryBuilder('o')
    ->getQuery()
    ->getResult();
Fixtures::add('baseinfo', $baseinfo[0]);

$categories = $app['orm.em']->getRepository('Eccube\Entity\Category')
    ->createQueryBuilder('o')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getResult();
Fixtures::add('categories', $categories);

$news = $app['orm.em']->getRepository('Eccube\Entity\News')
    ->createQueryBuilder('o')
    ->where('o.del_flg = 0')
    ->orderBy('o.date', 'DESC')
    ->getQuery()
    ->getResult();
Fixtures::add('news', $news);
