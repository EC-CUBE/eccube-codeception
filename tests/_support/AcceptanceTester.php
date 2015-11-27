<?php
use Codeception\Util\Fixtures;
use Eccube\Common\Constant;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    public function loginAsAdmin($user = '', $password = '')
    {
        if(!$user || !$password) {
            $account = Fixtures::get('admin_account');
            $user = $account['member'];
            $password = $account['password'];
        }
        
        $I = $this;
        $this->goToAdminPage();

        $I->submitForm('#form1', [
            'login_id' => $user, 
            'password' => $password
        ]);

        $I->see('ホーム', '#main .page-header');
    }

    public function logoutAsAdmin()
    {
        $I = $this;
        $isLogin = $I->grabTextFrom('#header .navbar-menu .dropdown .dropdown-toggle');
        if ($isLogin == '管理者 様') {
            $I->click('#header .navbar-menu .dropdown .dropdown-toggle');
            $I->click('#header .navbar-menu .dropdown .dropdown-menu a');
            $I->see('ログイン', '.login-box #form1 .btn_area button');
        }
    }

    public function goToAdminPage()
    {
        $I = $this;
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route']);
    }

    public function loginAsMember($email = '', $password = '')
    {
        $I = $this;
        $I->amOnPage('/mypage/login');
        $I->submitForm('#login_mypage', [
            'login_email' => $email,
            'login_pass' => $password
        ]);
        $I->see('新着情報', '#contents_bottom #news_area h2');
        $I->see('ログアウト', '#header #member .member_link li:nth-child(3) a');
    }

    public function logoutAsMember()
    {
        $I = $this;
        $I->amOnPage('/');
        $isLogin = $I->grabTextFrom('#header #member .member_link li:nth-child(2) a');
        if ($isLogin == 'ログアウト') {
            $I->click('#header #member .member_link li:nth-child(2) a');
            $I->see('新着情報', '#contents_bottom #news_area h2');
            $I->see('ログイン', '#header #member .member_link li:nth-child(2) a');
        }
    }

    public function setStock($pid, $stock = 0)
    {
        if(!$pid) {
            return;
        }
        $app = Fixtures::get('app');

        if (!is_array($stock)) {
            $pc = $app['orm.em']->getRepository('Eccube\Entity\ProductClass')->findOneBy(array('Product' => $pid));
            $pc->setStock($stock);
            $pc->setStockUnlimited(Constant::DISABLED);
            $ps = $app['orm.em']->getRepository('Eccube\Entity\ProductStock')->findOneBy(array('ProductClass' => $pc->getId()));
            $ps->setStock($stock);
            $app['orm.em']->persist($pc);
            $app['orm.em']->persist($ps);
            $app['orm.em']->flush();
        } else {
            $pcs = $app['orm.em']->getRepository('Eccube\Entity\ProductClass')
                ->createQueryBuilder('o')
                ->where('o.Product = '.$pid)
                ->andwhere('o.ClassCategory1 > 0')
                ->getQuery()
                ->getResult();
            foreach ($pcs as $key => $pc) {
                $pc->setStock($stock[$key]);
                $pc->setStockUnlimited(Constant::DISABLED);
                $pc->setSaleLimit(2);
                $ps = $app['orm.em']->getRepository('Eccube\Entity\ProductStock')->findOneBy(array('ProductClass' => $pc->getId()));
                $ps->setStock($stock[$key]);
                $app['orm.em']->persist($pc);
                $app['orm.em']->persist($ps);
                $app['orm.em']->flush();
            }
        }
    }

    public function buyThis($num = 1)
    {
        $I = $this;
        $I->fillField("#form1 #quantity", $num);
        $I->click('#form1 .btn_area button');
    }

    public function makeEmptyCart()
    {
        $I = $this;
        $I->click('#form_cart .item_box .icon_edit a');
        /* ToDo: popup*/
    }
}   
