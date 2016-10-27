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

        $I->see('ログインできませんでした。', '.login-box #form1 .text-danger');
    }
}
