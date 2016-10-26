<?php

use Codeception\Util\Fixtures;
use Eccube\Entity\Master\CustomerStatus;

/**
 * @group front
 * @group other
 * @group ef6
 */
class EF06OtherCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function other_ログイン正常(\AcceptanceTester $I)
    {
        $I->wantTo('EF0601-UC01-T01 ログイン 正常パターン');
        $I->logoutAsMember();

        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);
        $I->loginAsMember($customer->getEmail(), 'password');
    }

    public function other_ログイン異常1(\AcceptanceTester $I)
    {
        $I->wantTo('EF0601-UC01-T02 ログイン 異常パターン(仮会員)');
        $I->logoutAsMember();

        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->findOneBy(array('Status' => CustomerStatus::NONACTIVE));

        $I->amOnPage('/mypage/login');
        $I->submitForm('#login_mypage', [
            'login_email' => 'gege@ec-cube.net',
            'login_pass' => 'password'
        ]);

        $I->see('ログインできませんでした。', '#login_mypage .text-danger');
    }

    public function other_ログイン異常2(\AcceptanceTester $I)
    {
        $I->wantTo('EF0601-UC01-T03 ログイン 異常パターン(入力ミス)');
        $I->logoutAsMember();

        $I->amOnPage('/mypage/login');
        $I->submitForm('#login_mypage', [
            'login_email' => 'gege@ec-cube.net',
            'login_pass' => 'password'
        ]);

        $I->see('ログインできませんでした。', '#login_mypage .text-danger');
    }

    public function other_パスワード再発行(\AcceptanceTester $I)
    {
        $I->wantTo('EF0602-UC01-T01 パスワード再発行');
        $I->logoutAsMember();

        // TOPページ→ログイン（「ログイン情報をお忘れですか？」リンクを押下する）→パスワード再発行
        $I->amOnPage('/mypage/login');
        //$I->click('ログイン情報をお忘れですか', '#login_mypage #login_box .btn_area ul li a');
        $I->amOnPage('/forgot');

        // TOPページ>ログイン>パスワード再発行
        $I->see('パスワードの再発行', '#main .page-heading');

        // メールアドレスを入力する
        // 「次のページへ」ボタンを押下する
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->submitForm('#form1',[
            'login_email' => $customer->getEmail()
        ]);
        $I->see('パスワード発行メールの送信 完了', '#main .page-heading');

        // メールは受け取れないので、新しいパスワードでのログインテストは不可
    }

    public function other_ログアウト(\AcceptanceTester $I)
    {
        $I->wantTo('EF0603-UC01-T01 ログアウト');
        $I->logoutAsMember();

        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);
        $I->loginAsMember($customer->getEmail(), 'password');

        $I->logoutAsMember();
    }

    public function other_当サイトについて(\AcceptanceTester $I)
    {
        $I->wantTo('EF0604-UC01-T01 当サイトについて');
        $I->amOnPage('/');

        $I->click('#footer ul li:nth-child(1) a');
        $I->see('当サイトについて', '#main h1');
        $baseinfo = Fixtures::get('baseinfo');
        $I->see($baseinfo->getShopName(), '#main .dl_table dl:nth-child(1) dd');
    }

    public function other_プライバシーポリシー(\AcceptanceTester $I)
    {
        $I->wantTo('EF0605-UC01-T01 プライバシーポリシー');
        $I->amOnPage('/');

        $I->click('#footer ul li:nth-child(2) a');
        $I->see('プライバシーポリシー', '#main h1');
        $I->see('個人情報保護の重要性に鑑み、「個人情報の保護に関する法律」及び本プライバシーポリシーを遵守し、お客さまのプライバシー保護に努めます。', '#main p');
    }

    public function other_特定商取引法に基づく表記(\AcceptanceTester $I)
    {
        $I->wantTo('EF0606-UC01-T01 特定商取引法に基づく表記');
        $I->amOnPage('/');

        $I->click('#footer ul li:nth-child(3) a');
        $I->see('特定商取引法に基づく表記', '#main h1');
    }

    public function other_お問い合わせ1(\AcceptanceTester $I)
    {
        $I->wantTo('EF0607-UC01-T01 お問い合わせ');
        $I->amOnPage('/');

        $I->click('#footer ul li:nth-child(4) a');
        $I->see('お問い合わせ', '#main h1');

        // メールは受け取れないので、テスト不可
    }
}
