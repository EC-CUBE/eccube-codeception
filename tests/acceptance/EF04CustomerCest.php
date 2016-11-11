<?php

use Codeception\Util\Fixtures;

/**
 * @group front
 * @group customer
 * @group ef4
 */
class EF04CustomerCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function customer_会員登録正常(\AcceptanceTester $I)
    {
        $I->wantTo('EF0401-UC01-T01 会員登録 正常パターン');
        $I->amOnPage('/entry');
        $faker = Fixtures::get('faker');
        $new_email = microtime(true).'.'.$faker->safeEmail;
        // 会員情報入力フォームに、会員情報を入力する
        // 「同意する」ボタンを押下する
        $I->submitForm("#main_middle form",[
            'entry[name][name01]' => '姓',
            'entry[name][name02]' => '名',
            'entry[kana][kana01]' => 'セイ',
            'entry[kana][kana02]' => 'メイ',
            'entry[zip][zip01]' => '530',
            'entry[zip][zip02]' => '0001',
            'entry[address][pref]' => 27,
            'entry[address][addr01]' => '大阪市北区',
            'entry[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'entry[tel][tel01]' => '111',
            'entry[tel][tel02]' => '111',
            'entry[tel][tel03]' => '111',
            'entry[email][first]' => $new_email,
            'entry[email][second]' => $new_email,
            'entry[password][first]' => 'password',
            'entry[password][second]' => 'password',
        ]);

        // 入力した会員情報を確認する。
        $I->see('姓 名', '#main_middle form .dl_table dl:nth-child(1) dd');
        $I->see('111 - 111 - 111', '#main_middle form .dl_table dl:nth-child(5) dd');
        $I->see($new_email, '#main_middle form .dl_table dl:nth-child(7) dd');

        $I->resetEmails();
        // 「会員登録をする」ボタンを押下する
        $I->click('#main_middle form .btn_group p:nth-child(1) button');

        $I->seeEmailCount(2);
        foreach (array($new_email, 'admin@example.com') as $email) {
            $I->seeInLastEmailSubjectTo($email, '会員登録のご確認');
            $I->seeInLastEmailTo($email, '姓 名 様');
            $I->seeInLastEmailTo($email, 'この度は会員登録依頼をいただきまして、有り難うございます。');
        }

        // 「トップページへ」ボタンを押下する
        $I->click('#main_middle .btn_group p a');
        $I->see('新着情報', '#contents_bottom #news_area h2');


        // アクティベートURL取得
        $activateUrl = $I->grabFromLastEmailTo($new_email, '@/entry/activate/(.*)@');
        $I->resetEmails();

        // アクティベートURLからトップページへ
        $I->amOnPage($activateUrl);
        $I->see('新規会員登録（完了）', '#contents #main #main_middle h1');

        $I->seeEmailCount(2);
        foreach (array($new_email, 'admin@example.com') as $email) {
            $I->seeInLastEmailSubjectTo($email, '会員登録が完了しました。');
            $I->seeInLastEmailTo($email, '姓 名 様');
            $I->seeInLastEmailTo($email, '本会員登録が完了いたしました。');
        }

        $I->click('#main_middle .btn_group p a');
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }

    public function customer_会員登録異常1(\AcceptanceTester $I)
    {
        $I->wantTo('EF0401-UC01-T02 会員登録 異常パターン 重複');
        $I->amOnPage('/entry');

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();

        // 会員情報入力フォームに、会員情報を入力する
        // 「同意する」ボタンを押下する
        $I->submitForm("#main_middle form",[
            'entry[name][name01]' => '姓',
            'entry[name][name02]' => '名',
            'entry[kana][kana01]' => 'セイ',
            'entry[kana][kana02]' => 'メイ',
            'entry[zip][zip01]' => '530',
            'entry[zip][zip02]' => '0001',
            'entry[address][pref]' => 27,
            'entry[address][addr01]' => '大阪市北区',
            'entry[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'entry[tel][tel01]' => '111',
            'entry[tel][tel02]' => '111',
            'entry[tel][tel03]' => '111',
            'entry[email][first]' => $customer->getEmail(), // 会員登録済みのメールアドレスを入力する
            'entry[email][second]' => $customer->getEmail(),
            'entry[password][first]' => 'password',
            'entry[password][second]' => 'password',
        ]);

        // 入力した会員情報を確認する。
        $I->see('既に利用されているメールアドレスです', '#main_middle form .dl_table dl:nth-child(7) dd');
    }

    public function customer_会員登録異常2(\AcceptanceTester $I)
    {
        $I->wantTo('EF0401-UC01-T03 会員登録 異常パターン 入力ミス');
        $I->amOnPage('/entry');

        // 会員情報入力フォームに、会員情報を入力する
        // 「同意する」ボタンを押下する
        $I->submitForm("#main_middle form",[
            'entry[name][name01]' => '',
            'entry[name][name02]' => '名',
            'entry[kana][kana01]' => 'セイ',
            'entry[kana][kana02]' => 'メイ',
            'entry[zip][zip01]' => '530',
            'entry[zip][zip02]' => '0001',
            'entry[address][pref]' => 27,
            'entry[address][addr01]' => '大阪市北区',
            'entry[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'entry[tel][tel01]' => '111',
            'entry[tel][tel02]' => '111',
            'entry[tel][tel03]' => '111',
            'entry[email][first]' => 'acctest@ec-cube.net',
            'entry[email][second]' => 'acctest@ec-cube.net',
            'entry[password][first]' => 'password',
            'entry[password][second]' => 'password',
        ]);

        // 入力した会員情報を確認する。
        $I->see('新規会員登録', '#main_middle h1');
    }

    public function customer_会員登録同意しない(\AcceptanceTester $I)
    {
        $I->wantTo('EF0401-UC01-T04 会員登録 同意しないボタン');
        $I->amOnPage('/entry');

        $I->click('#main_middle form .no-padding .btn_group p:nth-child(2) a');
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }

    public function customer_会員登録戻る(\AcceptanceTester $I)
    {
        $I->wantTo('EF0401-UC01-T05 会員登録 戻るボタン');
        $I->amOnPage('/entry');

        // 会員情報入力フォームに、会員情報を入力する
        // 「同意する」ボタンを押下する
        $I->submitForm("#main_middle form",[
            'entry[name][name01]' => '姓',
            'entry[name][name02]' => '名',
            'entry[kana][kana01]' => 'セイ',
            'entry[kana][kana02]' => 'メイ',
            'entry[zip][zip01]' => '530',
            'entry[zip][zip02]' => '0001',
            'entry[address][pref]' => 27,
            'entry[address][addr01]' => '大阪市北区',
            'entry[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'entry[tel][tel01]' => '111',
            'entry[tel][tel02]' => '111',
            'entry[tel][tel03]' => '111',
            'entry[email][first]' => 'acctest2@ec-cube.net',
            'entry[email][second]' => 'acctest2@ec-cube.net',
            'entry[password][first]' => 'password',
            'entry[password][second]' => 'password',
        ]);

        $I->click('#main_middle form .btn_group p:nth-child(2) button');
        $I->see('新規会員登録', '#main_middle h1');
    }

    public function customer_会員登録利用規約(\AcceptanceTester $I)
    {
        $I->wantTo('EF0404-UC01-T01 会員登録 利用規約');
        $I->amOnPage('/entry');

        $I->click('#main_middle form .form_terms_link a');
        // 別ウィンドウで開く
        // codeceptionのwebdriverでは名前なしの別ウィンドウにアクセスできない...
        //$I->see('利用規約', '#main .page-heading');
    }
}
