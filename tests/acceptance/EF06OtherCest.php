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

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $I->loginAsMember($customer->getEmail(), 'password');
    }

    public function other_ログイン異常1(\AcceptanceTester $I)
    {
        $I->wantTo('EF0601-UC01-T02 ログイン 異常パターン(仮会員)');
        $I->logoutAsMember();

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer(null, false);

        $I->amOnPage('/mypage/login');
        $I->submitForm('#login_mypage', [
            'login_email' => $customer->getEmail(),
            'login_pass' => 'password'
        ]);

        $I->see('ログインできませんでした。', '#login_mypage .text-danger');
    }

    public function other_ログイン異常2(\AcceptanceTester $I)
    {
        $I->wantTo('EF0601-UC01-T03 ログイン 異常パターン(入力ミス)');
        $I->logoutAsMember();

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer(null, false);

        $I->amOnPage('/mypage/login');
        $I->submitForm('#login_mypage', [
            'login_email' => $customer->getEmail().'.bad',
            'login_pass' => 'password'
        ]);

        $I->see('ログインできませんでした。', '#login_mypage .text-danger');
    }

    public function other_パスワード再発行(\AcceptanceTester $I)
    {
        $I->wantTo('EF0602-UC01-T01 パスワード再発行');
        $I->logoutAsMember();
        $BaseInfo = Fixtures::get('baseinfo');

        // TOPページ→ログイン（「ログイン情報をお忘れですか？」リンクを押下する）→パスワード再発行
        $I->amOnPage('/mypage/login');
        //$I->click('ログイン情報をお忘れですか', '#login_mypage #login_box .btn_area ul li a');
        $I->amOnPage('/forgot');

        // TOPページ>ログイン>パスワード再発行
        $I->see('パスワードの再発行', '#main .page-heading');

        // メールアドレスを入力する
        // 「次のページへ」ボタンを押下する
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $I->resetEmails();
        $I->submitForm('#form1',[
            'login_email' => $customer->getEmail()
        ]);
        $I->see('パスワード発行メールの送信 完了', '#main .page-heading');

        $I->seeEmailCount(2);
        foreach (array($customer->getEmail(), $BaseInfo->getEmail01()) as $email) {
            $I->seeInLastEmailSubjectTo($email, 'パスワード変更のご確認');
        }
        $url = $I->grabFromLastEmailTo($customer->getEmail(), '@/forgot/reset/(.*)@');

        $I->resetEmails();
        $I->amOnPage($url);
        $I->see('パスワード変更(完了ページ)', '#contents #main h1');
        $I->seeEmailCount(2);
        foreach (array($customer->getEmail(), $BaseInfo->getEmail01()) as $email) {
            $I->seeInLastEmailSubjectTo($email, 'パスワード変更のお知らせ');
        }
        $new_password = $I->grabFromLastEmailTo($customer->getEmail(), '@新しいパスワード：(.*)@');
        $I->loginAsMember($customer->getEmail(), trim(str_replace('新しいパスワード：', '', $new_password)));
    }

    public function other_ログアウト(\AcceptanceTester $I)
    {
        $I->wantTo('EF0603-UC01-T01 ログアウト');
        $I->logoutAsMember();

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $I->loginAsMember($customer->getEmail(), 'password');

        $I->logoutAsMember();
    }

    public function other_当サイトについて(\AcceptanceTester $I)
    {
        $I->wantTo('EF0604-UC01-T01 当サイトについて');
        $I->amOnPage('/');

        $I->click('.ec-footerNavi .ec-footerNavi__link:nth-child(1) a');
        $I->see('当サイトについて', '#main h1');
        $baseinfo = Fixtures::get('baseinfo');
        $I->see($baseinfo->getShopName(), '#main .dl_table dl:nth-child(1) dd');
    }

    public function other_プライバシーポリシー(\AcceptanceTester $I)
    {
        $I->wantTo('EF0605-UC01-T01 プライバシーポリシー');
        $I->amOnPage('/');

        $I->click('.ec-footerNavi .ec-footerNavi__link:nth-child(2) a');
        $I->see('プライバシーポリシー', '#main h1');
        $I->see('個人情報保護の重要性に鑑み、「個人情報の保護に関する法律」及び本プライバシーポリシーを遵守し、お客さまのプライバシー保護に努めます。', '#main p');
    }

    public function other_特定商取引法に基づく表記(\AcceptanceTester $I)
    {
        $I->wantTo('EF0606-UC01-T01 特定商取引法に基づく表記');
        $I->amOnPage('/');

        $I->click('.ec-footerNavi .ec-footerNavi__link:nth-child(3) a');
        $I->see('特定商取引法に基づく表記', '#main h1');
    }

    public function other_お問い合わせ1(\AcceptanceTester $I)
    {
        $I->wantTo('EF0607-UC01-T01 お問い合わせ');
        $I->amOnPage('/');
        $I->resetEmails();
        $faker = Fixtures::get('faker');
        $new_email = microtime(true).'.'.$faker->safeEmail;
        $BaseInfo = Fixtures::get('baseinfo');

        $I->click('.ec-footerNavi .ec-footerNavi__link:nth-child(4) a');
        $I->see('お問い合わせ', '#main h1');

        $I->submitForm("#form1",[
            'contact[name][name01]' => '姓',
            'contact[name][name02]' => '名',
            'contact[kana][kana01]' => 'セイ',
            'contact[kana][kana02]' => 'メイ',
            'contact[zip][zip01]' => '530',
            'contact[zip][zip02]' => '0001',
            'contact[address][pref]' => 27,
            'contact[address][addr01]' => '大阪市北区',
            'contact[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'contact[tel][tel01]' => '111',
            'contact[tel][tel02]' => '111',
            'contact[tel][tel03]' => '111',
            'contact[email]' => $new_email,
            'contact[contents]' => 'お問い合わせ内容の送信'
        ]);

        $I->see('お問い合わせ', '#main h1');
        $I->click('#confirm_box__confirm_button > button');

        // 完了ページ
        $I->see('お問い合わせ完了', '#main h1');

        // メールチェック
        $I->seeEmailCount(2);
        foreach (array($new_email, $BaseInfo->getEmail01()) as $email) {
            $I->seeInLastEmailSubjectTo($email, 'お問い合わせを受け付けました');
            $I->seeInLastEmailTo($email, '姓 名 様');
            $I->seeInLastEmailTo($email, 'お問い合わせ内容の送信');
        }
    }
}
