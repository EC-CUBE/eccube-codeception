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
 *
 * Warning: 
 * createCustomer(),createProduct(),createOrder() functionは、Unitテストのコードを流用
 * 将来、Eccube\Util上に共通クラスとして作成され置き換えられる
 * https://github.com/EC-CUBE/ec-cube/issues/1127
 */
require_once $config['eccube_path'].'autoload.php'; 
use Symfony\Component\Yaml\Yaml;
$dbyml = $config['eccube_path'].'app/config/eccube/database.yml';
$database = $database_org = Yaml::parse($dbyml);
$database['database']['dbname'] = $config['db'];
$database['database']['host'] = $config['dbhost'];
$database['database']['port'] = ($config['dbport']) ? $config['dbport'] : null;
$database['database']['user'] = $config['user'];
$database['database']['password'] = ($config['password']) ? $config['password'] : '';
$database['database']['charset'] = $config['charset'];
file_put_contents($dbyml,Yaml::dump($database));

$app = new Eccube\Application();
$app->initialize();
$app->initializePlugin();
$app->run();
Fixtures::add('app', $app);
file_put_contents($dbyml,Yaml::dump($database_org));

use Eccube\Common\Constant;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Entity\Order;
use Eccube\Entity\OrderDetail;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
use Eccube\Entity\ProductImage;
use Eccube\Entity\ProductStock;
use Eccube\Entity\Shipping;
use Eccube\Entity\ShipmentItem;

$num = $app['orm.em']->getRepository('Eccube\Entity\Customer')
    ->createQueryBuilder('o')
    ->select('count(o.id)')
    ->where('o.del_flg = 0')
    ->getQuery()
    ->getSingleScalarResult();
if(!$num) {
    for ($i = 0; $i < $config['fixture_customer_num']; $i++) {
        $customer = createCustomer($app);
        $order = createOrder($app, $customer);
        $order->setOrderStatus($app['eccube.repository.order_status']->find($app['config']['order_new']));
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

function createCustomer($app, $email = null, $active = true)
{
    $faker = Faker::create("ja_JP");
    $Customer = new Customer();
    if (is_null($email)) {
        $email = $faker->email;
    }
    if ($active) {
        $Status = $app['orm.em']->getRepository('Eccube\Entity\Master\CustomerStatus')->find(CustomerStatus::ACTIVE);
    } else {
        $Status = $app['orm.em']->getRepository('Eccube\Entity\Master\CustomerStatus')->find(CustomerStatus::NONACTIVE);
    }
    $Customer
        ->setName01($faker->lastName)
        ->setName02($faker->firstName)
        ->setEmail($email)
        ->setSalt($app['eccube.repository.customer']->createSalt(5))
        ->setPassword('password')
        ->setPref($app['eccube.repository.master.pref']->find(13))
        ->setStatus($Status)
        ->setDelFlg(0)
        ->setPassword($app['eccube.repository.customer']->encryptPassword($app, $Customer))
        ->setSecretKey($app['eccube.repository.customer']->getUniqueSecretKey($app));

    $CustomerAddress = new \Eccube\Entity\CustomerAddress();
    $CustomerAddress->setName01($Customer->getName01())
        ->setName02($Customer->getName02())
        ->setKana01($Customer->getKana01())
        ->setKana02($Customer->getKana02())
        ->setCompanyName($Customer->getCompanyName())
        ->setZip01($Customer->getZip01())
        ->setZip02($Customer->getZip02())
        ->setZipcode($Customer->getZip01() . $Customer->getZip02())
        ->setPref($Customer->getPref())
        ->setAddr01($Customer->getAddr01())
        ->setAddr02($Customer->getAddr02())
        ->setTel01($Customer->getTel01())
        ->setTel02($Customer->getTel02())
        ->setTel03($Customer->getTel03())
        ->setFax01($Customer->getFax01())
        ->setFax02($Customer->getFax02())
        ->setFax03($Customer->getFax03())
        ->setDelFlg(Constant::DISABLED)
        ->setCustomer($Customer);
    
    $app['orm.em']->persist($Customer);
    $app['orm.em']->persist($CustomerAddress);
    $app['orm.em']->flush();
    return $Customer;
}

function createProduct($app, $product_name = null, $product_class_num = 3)
{
    $faker = Faker::create("ja_JP");
    $Member = $app['eccube.repository.member']->find(2);
    $Disp = $app['eccube.repository.master.disp']->find(\Eccube\Entity\Master\Disp::DISPLAY_SHOW);
    $ProductType = $app['eccube.repository.master.product_type']->find(1);
    $Product = new Product();
    if (is_null($product_name)) {
        $product_name = $faker->word;
    }

    $Product
        ->setName($product_name)
        ->setCreator($Member)
        ->setStatus($Disp)
        ->setDelFlg(Constant::DISABLED)
        ->setDescriptionList($faker->paragraph())
        ->setDescriptionDetail($faker->text());

    $app['orm.em']->persist($Product);
    $app['orm.em']->flush();

    for ($i = 0; $i < 3; $i++) {
        $ProductImage = new ProductImage();
        $ProductImage
            ->setCreator($Member)
            ->setFileName($faker->word.'.jpg')
            ->setRank($i)
            ->setProduct($Product);
        $app['orm.em']->persist($ProductImage);
        $Product->addProductImage($ProductImage);
    }

    for ($i = 0; $i < $product_class_num; $i++) {
        $ProductStock = new ProductStock();
        $ProductStock
            ->setCreator($Member)
            ->setStock($faker->randomNumber());
        $app['orm.em']->persist($ProductStock);
        $ProductClass = new ProductClass();
        $ProductClass
            ->setCreator($Member)
            ->setProductStock($ProductStock)
            ->setProduct($Product)
            ->setProductType($ProductType)
            ->setStockUnlimited(false)
            ->setPrice02($faker->randomNumber(5))
            ->setDelFlg(Constant::DISABLED);
        $app['orm.em']->persist($ProductClass);
        $Product->addProductClass($ProductClass);
    }

    $Categories = $app['eccube.repository.category']->findAll();
    $i = 0;
    foreach ($Categories as $Category) {
        $ProductCategory = new ProductCategory();
        $ProductCategory
            ->setCategory($Category)
            ->setProduct($Product)
            ->setCategoryId($Category->getId())
            ->setProductId($Product->getId())
            ->setRank($i);
        $app['orm.em']->persist($ProductCategory);
        $Product->addProductCategory($ProductCategory);
        $i++;
    }

    $app['orm.em']->flush();
    return $Product;
}

function createOrder($app, Customer $Customer)
{
    $faker = Faker::create("ja_JP");
    $quantity = $faker->randomNumber(2);
    $Pref = $app['eccube.repository.master.pref']->find(1);
    $Order = new Order();
    $Order->setCustomer($Customer)
        ->setCharge(0)
        ->setDeliveryFeeTotal(0)
        ->setDiscount(0)
        ->setOrderStatus($app['eccube.repository.order_status']->find($app['config']['order_new']))
        ->setDelFlg(Constant::DISABLED);
    $Order->copyProperties($Customer);
    $Order->setPref($Pref);
    $app['orm.em']->persist($Order);
    $app['orm.em']->flush();

    $Shipping = new Shipping();
    $Shipping->copyProperties($Customer);
    $Shipping->setPref($Pref);
    $Order->addShipping($Shipping);
    $Shipping->setOrder($Order);
    $app['orm.em']->persist($Shipping);

    $Product = createProduct($app);
    $ProductClasses = $Product->getProductClasses();
    $ProductClass = $ProductClasses[0];

    $OrderDetail = new OrderDetail();
    $TaxRule = $app['eccube.repository.tax_rule']->getByRule(); // デフォルト課税規則
    $OrderDetail->setProduct($Product)
        ->setProductClass($ProductClass)
        ->setProductName($Product->getName())
        ->setProductCode($ProductClass->getCode())
        ->setPrice($ProductClass->getPrice02())
        ->setQuantity($quantity)
        ->setTaxRule($TaxRule->getCalcRule()->getId())
        ->setTaxRate($TaxRule->getTaxRate());
    $app['orm.em']->persist($OrderDetail);
    $OrderDetail->setOrder($Order);
    $Order->addOrderDetail($OrderDetail);

    $ShipmentItem = new ShipmentItem();
    $ShipmentItem->setShipping($Shipping)
        ->setOrder($Order)
        ->setProductClass($ProductClass)
        ->setProduct($Product)
        ->setProductName($Product->getName())
        ->setProductCode($ProductClass->getCode())
        ->setPrice($ProductClass->getPrice02())
        ->setQuantity($quantity);
    $app['orm.em']->persist($ShipmentItem);

    $subTotal = $OrderDetail->getPriceIncTax() * $OrderDetail->getQuantity();
    // TODO 送料, 手数料の加算
    $Order->setSubTotal($subTotal);
    $Order->setTotal($subTotal);
    $Order->setPaymentTotal($subTotal);

    $app['orm.em']->flush();
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

