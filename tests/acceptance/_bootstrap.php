<?php
use Codeception\Util\Fixtures;
use Faker\Factory as Faker;

$config = parse_ini_file('tests/acceptance/config.ini',true);

/**
 * create fixture
 * このデータは$appを使って直接eccubeのデータベースに作成される
 * よってCodeceptionの設定によってコントロールされず、テスト後もデータベース内にこのデータは残る
 * データの件数によって、作成するかどうか判定される
 */
require_once $config['eccube_path'].'autoload.php';

$app = Eccube\Application::getInstance();
// Disable to TransactionListener.
$app->setTestMode(true);
$app->initialize();
$app->initializePlugin();
$app->register(new \Eccube\Tests\ServiceProvider\FixtureServiceProvider());
$app->boot();
// この Fixture は Cest ではできるだけ使用せず, 用途に応じた Fixture を使用すること
Fixtures::add('app', $app);

use Eccube\Common\Constant;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\CustomerStatus;

$faker = Faker::create('ja_JP');
Fixtures::add('faker', $faker);
$num = $app['orm.em']->getRepository('Eccube\Entity\Customer')
    ->createQueryBuilder('o')
    ->select('count(o.id)')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getSingleScalarResult();
if ($num < $config['fixture_customer_num']) {
    $num = $config['fixture_customer_num'] - $num;
    for ($i = 0; $i < $num; $i++) {
        $email = microtime(true).'.'.$faker->safeEmail;
        $customer = createCustomer($app, $email);
    }
    createCustomer($app, null, false); // non-active member
}

$num = $app['orm.em']->getRepository('Eccube\Entity\Product')
    ->createQueryBuilder('o')
    ->select('count(o.id)')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getSingleScalarResult();
// 受注生成件数 + 初期データの商品が生成されているはず
if ($num < ($config['fixture_customer_num']+2)) {
    // 規格なしも含め $config['fixture_product_num'] の分だけ生成する
    for ($i = 0; $i < $config['fixture_product_num'] - 1; $i++) {
        createProduct($app);
    }
    createProduct($app, '規格なし商品', 0);
}

$Customers = $app['orm.em']->getRepository('Eccube\Entity\Customer')->findAll();
$Products = $app['orm.em']->getRepository('Eccube\Entity\Product')->findAll();
$Deliveries = $app['orm.em']->getRepository('Eccube\Entity\Delivery')->findAll();
foreach ($Customers as $Customer) {
    $Delivery = $Deliveries[$faker->numberBetween(0, count($Deliveries) - 1)];
    $Product = $Products[$faker->numberBetween(0, count($Products) - 1)];
    $charge = $faker->randomNumber(4);
    $discount = $faker->randomNumber(4);
    for ($i = 0; $i < $config['fixture_order_num']; $i++) {
        $Status = $app['eccube.repository.order_status']->find($faker->numberBetween(1, 8));
        $OrderDate = $faker->dateTimeThisYear();
        createOrder($app, $Customer, $Product->getProductClasses()->toArray(), $Delivery, $charge, $discount, $Status, $OrderDate);
    }
}

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

function createOrder($app, Customer $Customer, array $ProductClasses, $Delivery, $charge, $discount, $Status, $OrderDate)
{
    $Order = $app['eccube.fixture.generator']->createOrder($Customer, $ProductClasses, $Delivery, $charge, $discount);
    $Order->setOrderStatus($Status);
    $Order->setOrderDate($OrderDate);
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

/** 管理画面アカウント情報. */
Fixtures::add('admin_account',array(
    'member' => $config['admin_user'],
    'password' => $config['admin_password'],
));
/** $app['config'] 情報. */
Fixtures::add('config', $app['config']);

/** config.ini 情報. */
Fixtures::add('test_config', $config);

$baseinfo = $app['orm.em']->getRepository('Eccube\Entity\BaseInfo')->get();
/** BaseInfo. */
Fixtures::add('baseinfo', $baseinfo);

$categories = $app['orm.em']->getRepository('Eccube\Entity\Category')
    ->createQueryBuilder('o')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getResult();
/** カテゴリ一覧の配列. */
Fixtures::add('categories', $categories);

$news = $app['orm.em']->getRepository('Eccube\Entity\News')
    ->createQueryBuilder('o')
    ->where('o.del_flg = 0')
    ->orderBy('o.date', 'DESC')
    ->getQuery()
    ->getResult();
/** 新着情報一覧. */
Fixtures::add('news', $news);

$findOrders = function () use ($app) {
    return $app['orm.em']->getRepository('Eccube\Entity\Order')
    ->createQueryBuilder('o')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getResult();
};
/** 受注を検索するクロージャ. */
Fixtures::add('findOrders', $findOrders);

$findProducts = function () use ($app) {
    return $app['orm.em']->getRepository('Eccube\Entity\Product')
        ->createQueryBuilder('p')
        ->where('p.del_flg = 0')
        ->getQuery()
        ->getResult();
};
/** 商品を検索するクロージャ. */
Fixtures::add('findProducts', $findProducts);

$createCustomer = function ($email = null, $active = true) use ($app, $faker) {
    if (is_null($email)) {
        $email = microtime(true).'.'.$faker->safeEmail;
    }
    return createCustomer($app, $email, $active);
};
/** 会員を生成するクロージャ. */
Fixtures::add('createCustomer', $createCustomer);

$createOrders = function ($Customer, $numberOfOrders = 5) use ($app, $faker) {
    $Orders = array();
    for ($i = 0; $i < $numberOfOrders; $i++) {
        $Order = $app['eccube.fixture.generator']->createOrder($Customer);
        $Status = $app['eccube.repository.order_status']->find($faker->numberBetween(1, 7));
        $OrderDate = $faker->dateTimeThisYear();
        $Order->setOrderStatus($Status);
        $Order->setOrderDate($OrderDate);
        $app['orm.em']->flush($Order);
        $Orders[] = $Order;
    }
    return $Orders;
};
/** 受注を生成するクロージャ. */
Fixtures::add('createOrders', $createOrders);

$findPlugins = function () use ($app) {
    return $app['orm.em']->getRepository('Eccube\Entity\Plugin')->findAll();
};
/** プラグインを検索するクロージャ */
Fixtures::add('findPlugins', $findPlugins);

$findPluginByCode = function ($code = null) use ($app) {
    return $app['orm.em']->getRepository('Eccube\Entity\Plugin')->findOneBy(['code' => $code]);
};
/** プラグインを検索するクロージャ */
Fixtures::add('findPluginByCode', $findPluginByCode);
