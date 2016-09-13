<?php
use AcceptanceTester;
use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin02
 * @group systeminformation
 * @group ea8
 */
class EA08SysteminfoCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->loginAsAdmin();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function systeminfo_システム情報(AcceptanceTester $I)
    {
        $I->wantTo('EA0801-UC01-T01 システム情報');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/system');
        $I->see('システム設定システム情報', '#main .page-header');

        $I->see('システム情報', '#main .container-fluid div:nth-child(1) .box-header .box-title');
        $I->see('PHP情報', '#main .container-fluid div:nth-child(2) .box-header .box-title');
    }

    public function systeminfo_メンバー管理表示(AcceptanceTester $I)
    {
        $I->wantTo('EA0802-UC01-T01 メンバー管理 - 表示');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->see('メンバー管理', '#main .container-fluid div:nth-child(1) .box-header .box-title');
        $I->see('新規登録', '#main .container-fluid .btn_area a');
    }

    public function systeminfo_メンバー管理登録実施(AcceptanceTester $I)
    {
        $I->wantTo('EA0803-UC01-T01 メンバー管理 - 登録 - 登録実施');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .btn_area a');
        $I->see('メンバー登録・編集', '#main .container-fluid div:nth-child(1) .box-header .box-title');

        $I->fillField('#form1 #admin_member_name', 'admintest');
        $I->fillField('#form1 #admin_member_department', 'admintest department');
        $I->fillField('#form1 #admin_member_login_id', 'admintest');
        $I->fillField('#form1 #admin_member_password_first', 'password');
        $I->fillField('#form1 #admin_member_password_second', 'password');
        $I->selectOption('#form1 #admin_member_Authority', 'システム管理者');
        $I->selectOption('#form1 #admin_member_Work_1', '稼働');
        $I->click('#aside_column button');
        $I->see('メンバーを保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->see('メンバー管理', '#main .container-fluid div:nth-child(1) .box-header .box-title');
        $I->see('admintest', '#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(1)');
    }

    public function systeminfo_メンバー管理登録未実施(AcceptanceTester $I)
    {
        $I->wantTo('EA0803-UC01-T02 メンバー管理 - 登録 - 登録未実施');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .btn_area a');
        $I->see('メンバー登録・編集', '#main .container-fluid div:nth-child(1) .box-header .box-title');

        $I->fillField('#form1 #admin_member_name', 'admintest2');
        $I->fillField('#form1 #admin_member_department', 'admintest department');
        $I->fillField('#form1 #admin_member_login_id', 'admintest');
        $I->fillField('#form1 #admin_member_password_first', 'password');
        $I->fillField('#form1 #admin_member_password_second', 'password');
        $I->selectOption('#form1 #admin_member_Authority', 'システム管理者');
        $I->selectOption('#form1 #admin_member_Work_1', '稼働');
        $I->click('#aside_wrap .btn_area a');

        $I->see('メンバー管理', '#main .container-fluid div:nth-child(1) .box-header .box-title');
        $I->dontSee('admintest2', '#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(1)');
    }

    public function systeminfo_メンバー管理登録異常(AcceptanceTester $I)
    {
        $I->wantTo('EA0803-UC01-T03 メンバー管理 - 登録 - 異常パターン');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .btn_area a');
        $I->see('メンバー登録・編集', '#main .container-fluid div:nth-child(1) .box-header .box-title');

        $I->click('#aside_column button');
        $I->see('入力されていません。', '#form1 div:nth-child(1) div');
    }

    public function systeminfo_メンバー管理編集実施(AcceptanceTester $I)
    {
        $I->wantTo('EA0803-UC02-T01 メンバー管理 - 編集 - 編集実施');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown a');
        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(1) a');
        $I->see('メンバー登録・編集', '#main .container-fluid div:nth-child(1) .box-header .box-title');

        $I->fillField('#form1 #admin_member_name', 'administrator');
        $I->click('#aside_column button');
        $I->see('メンバーを保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->see('メンバー管理', '#main .container-fluid div:nth-child(1) .box-header .box-title');
        $I->see('administrator', '#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(1)');
    }

    public function systeminfo_メンバー管理編集未実施(AcceptanceTester $I)
    {
        $I->wantTo('EA0803-UC02-T02 メンバー管理 - 編集 - 編集未実施');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown a');
        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(1) a');
        $I->see('メンバー登録・編集', '#main .container-fluid div:nth-child(1) .box-header .box-title');

        $I->fillField('#form1 #admin_member_name', 'administrator2');
        $I->click('#aside_wrap .btn_area a');

        $I->see('メンバー管理', '#main .container-fluid div:nth-child(1) .box-header .box-title');
        $I->dontSee('administrator2', '#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(1)');
    }

    public function systeminfo_メンバー管理編集異常(AcceptanceTester $I)
    {
        $I->wantTo('EA0803-UC03-T01 メンバー管理 - 編集 - 異常パターン');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown a');
        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(1) a');
        $I->see('メンバー登録・編集', '#main .container-fluid div:nth-child(1) .box-header .box-title');

        $I->fillField('#form1 #admin_member_name', '');
        $I->click('#aside_column button');

        $I->see('入力されていません。', '#form1 div:nth-child(1) div');
    }

    public function systeminfo_メンバー管理登録下へ(AcceptanceTester $I)
    {
        $I->wantTo('EA0802-UC01-T02 メンバー管理 - 下へ');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown a');
        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(3) a');

        $I->see('管理者', '#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(1)');
    }

    public function systeminfo_メンバー管理登録上へ(AcceptanceTester $I)
    {
        $I->wantTo('EA0802-UC01-T03 メンバー管理 - 上へ');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(2) td:nth-child(5) .dropdown a');
        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(2) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(3) a');

        $I->see('管理者', '#main .container-fluid .table_list .table tbody tr:nth-child(2) td:nth-child(1)');
    }

    public function systeminfo_メンバー管理削除(AcceptanceTester $I)
    {
        $I->wantTo('EA0802-UC01-T06 メンバー管理 - 削除');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown a');
        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(2) a');

        /* ToDo: popup
           alertによる確認あり
        $I->see('メンバーを削除しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('管理者', '#main .container-fluid .table_list .table tbody tr:nth-child(1) td:nth-child(1)');
        */
    }

    public function systeminfo_メンバー管理自ユーザー削除(AcceptanceTester $I)
    {
        $I->wantTo('EA0802-UC01-T07 メンバー管理 - 自ユーザー削除');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/member');
        $I->see('システム設定メンバー管理', '#main .page-header');

        $I->click('#main .container-fluid .table_list .table tbody tr:nth-child(2) td:nth-child(5) .dropdown a');
        $I->see('削除', '#main .container-fluid .table_list .table tbody tr:nth-child(2) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(2) a');
        $href = $I->grabAttributeFrom('#main .container-fluid .table_list .table tbody tr:nth-child(2) td:nth-child(5) .dropdown .dropdown-menu li:nth-child(2) a', 'href');
        $I->assertEquals('', $href);
    }

    public function systeminfo_セキュリティ管理表示(AcceptanceTester $I)
    {
        $I->wantTo('EA0804-UC01-T01 セキュリティ管理 - 表示');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/security');
        $I->see('システム設定セキュリティ管理', '#main .page-header');
        $I->see('セキュリティ機能設定', '#main .container-fluid div:nth-child(1) .box-header .box-title');
    }

    public function systeminfo_セキュリティ管理ディレクトリ名(AcceptanceTester $I)
    {
        $I->wantTo('EA0804-UC01-T02 セキュリティ管理 - ディレクトリ名変更');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/security');
        $I->see('システム設定セキュリティ管理', '#main .page-header');

        $I->fillField('form #admin_security_admin_route_dir', 'test');
        $I->click('#aside_column div div div div div button');
        $I->loginAsAdmin('', '', 'test');

        $I->amOnPage('/test/setting/system/security');
        $I->fillField('form #admin_security_admin_route_dir', $config['admin_route']);
        $I->click('#aside_column div div div div div button');
        $I->loginAsAdmin();
    }

    public function systeminfo_セキュリティ管理IP制限(AcceptanceTester $I)
    {
        $I->wantTo('EA0804-UC01-T03 セキュリティ管理 - IP制限');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/security');
        $I->see('システム設定セキュリティ管理', '#main .page-header');

        $I->fillField('form #admin_security_admin_allow_host', '1.1.1.1');
        $I->click('#aside_column div div div div div button');

        $I->amOnPage('/'.$config['admin_route']);
        $I->see('システムエラーが発生しました。', '#main .container-fluid h1');

        $test_config = Fixtures::get('test_config');
        $eccube = $test_config['eccube_path'];
        $configfile = $eccube."/app/config/eccube/config.yml";
        $lines = file($configfile);
        $fh = fopen($configfile, 'w');
        foreach ($lines as $line) {
            if(preg_match('/1\.1\.1\.1/', $line)) {
                continue;
            }
            fwrite($fh, $line);
        }
        fclose($fh);
    }

    public function systeminfo_セキュリティ管理SSL(AcceptanceTester $I)
    {
        $I->wantTo('EA0804-UC01-T04 セキュリティ管理 - SSL強制');

        /**
         * ToDo: SSL環境を用意してテストすべし。今はナイ。。
         */
    }

    public function systeminfo_権限管理追加(AcceptanceTester $I)
    {
        $I->wantTo('EA0805-UC01-T01 権限管理 - 追加');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/authority');
        $I->see('システム設定権限管理', '#main .page-header');

        $I->click('form .add');

        $I->selectOption('form #table-authority tbody tr:nth-child(1) td:nth-child(1) select', 'システム管理者');
        $I->fillField('form #table-authority tbody tr:nth-child(1) td:nth-child(2) input', '/content');
        $I->selectOption('form #table-authority tbody tr:nth-child(2) td:nth-child(1) select', 'システム管理者');
        $I->fillField('form #table-authority tbody tr:nth-child(2) td:nth-child(2) input', '/store');

        $I->click('form #aside_column button');

        $I->see('権限設定を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->dontSee('コンテンツ管理', '#side ul');
        $I->dontSee('オーナーズストア', '#side ul');
    }

    public function systeminfo_権限管理削除(AcceptanceTester $I)
    {
        $I->wantTo('EA0805-UC02-T01 権限管理 - 削除');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/authority');
        $I->see('システム設定権限管理', '#main .page-header');

        $I->click('form #table-authority tbody tr:nth-child(1) td:nth-child(3) button');
        $I->click('form #table-authority tbody tr:nth-child(1) td:nth-child(3) button');

        $I->click('form #aside_column button');

        $I->see('権限設定を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('コンテンツ管理', '#side ul');
        $I->see('オーナーズストア', '#side ul');
    }

    public function systeminfo_ログ表示(AcceptanceTester $I)
    {
        $I->wantTo('EA0806-UC01-T01 ログ表示');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/log');
        $I->see('システム設定EC-CUBE ログ表示', '#main .page-header');

        $log = $I->grabValueFrom('#form1 #admin_system_log_files');
        $expect = "site_".date('Y-m-d').".log";
        $I->assertEquals($expect, $log);

        $I->fillField('#form1 #admin_system_log_line_max', '1');
        $I->click('#form1 button');

        $I->dontSeeElement('#main .container-fluid .box table tbody tr:nth-child(2)');
    }

    public function systeminfo_マスターデータ管理(AcceptanceTester $I)
    {
        $I->wantTo('EA0807-UC01-T01 マスターデータ管理');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/system/masterdata');
        $I->see('システム設定マスターデータ管理', '#main .page-header');

        $I->selectOption('#form1 #admin_system_masterdata_masterdata', 'mtb_sex');
        $I->click('#form1 button');

        $I->fillField('#form2 table tbody tr:nth-child(4) td:nth-child(1) input', '3');
        $I->fillField('#form2 table tbody tr:nth-child(4) td:nth-child(2) input', '無回答');

        $I->click('#form2 #aside_column button');

        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->amOnPage('/'.$config['admin_route'].'/customer/new');
        $I->see('無回答', '#customer_form #admin_customer_sex');
    }
}
