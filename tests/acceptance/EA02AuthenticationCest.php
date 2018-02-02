<?php

use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin01
 * @group authentication
 * @group ea2
 */
class EA02AuthenticationCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function authentication_パスワード認証(\AcceptanceTester $I)
    {
        $I->wantTo('EA0201-UC01-T01 パスワード認証');

        // _before()で正常系はテスト済み
        // 異常系のテスト
        $I->logoutAsAdmin();

        $I->submitForm('#form1', [
            'login_id' => "invalid",
            'password' => "invalidpassword"
        ]);

        $I->see('資格が無効です。', '.login-box #form1 .text-danger');
    }

    public function authentication_最終ログイン日時確認(\AcceptanceTester $I)
    {
        $I->wantTo('EA0201-UC01-T01 最終ログイン日時確認');

        $I->click(['css' => '.navbar-menu .dropdown-toggle']);
        $loginText = $I->grabTextFrom(['css' => '.navbar-menu .dropdown-menu']);
        $lastLogin = preg_replace('/.*(\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}).*/s', '$1', $loginText);
        // 表示されるログイン日時では秒数がわからないため、タイミングによっては1分ちょっと変わる
        $I->assertTrue((strtotime('now') - strtotime($lastLogin)) < 70, '最終ログイン日時が正しい');
    }
}
