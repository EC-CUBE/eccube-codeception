<?php
use AcceptanceTester;
use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin02
 * @group customer
 * @group ea5
 */
class EA05CustomerCest
{
    public function _before(AcceptanceTester $I)
    {
        // すべてのテストケース実施前にログインしておく
        // ログイン後は管理アプリのトップページに遷移している
        $I->loginAsAdmin();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function customer_検索(AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC01-T01 検索');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);

        $I->fillField('#admin_search_customer_multi', $customer->getEmail());
        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');

        $I->see('検索結果 1 件 が該当しました','#search_form > div.row > div > div > div.box-header.with-arrow > h3');
    }

    public function customer_検索結果なし(AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC01-T02 検索 結果なし');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $I->fillField('#admin_search_customer_multi', 'testacount@ec-cube.com');
        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');

        $I->see('検索条件に該当するデータがありませんでした。','#search_form > div.row > div > div > div > h3');
    }

    public function customer_会員登録(AcceptanceTester $I)
    {
        $I->wantTo('EA0502-UC01-T02(& UC01-T02) 会員登録');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer/new');
        $I->see('会員管理会員登録・編集', '#main .page-header');

        $I->fillField('#admin_customer_name_name01', 'testuser');
        $I->fillField('#admin_customer_name_name02', 'testuser');
        $I->fillField('#admin_customer_kana_kana01', 'テストユーザー');
        $I->fillField('#admin_customer_kana_kana02', 'テストユーザー');
        $I->fillField('#admin_customer_zip_zip01', '530');
        $I->fillField('#admin_customer_zip_zip02', '0001');
        $I->selectOption('#admin_customer_address_pref', '大阪');
        $I->fillField('#admin_customer_address_addr01', '大阪市北区梅田2-4-9');
        $I->fillField('#admin_customer_address_addr02', 'ブリーゼタワー13F');
        $I->fillField('#admin_customer_email', 'test@test.test');
        $I->fillField('#admin_customer_tel_tel01', '111');
        $I->fillField('#admin_customer_tel_tel02', '111');
        $I->fillField('#admin_customer_tel_tel03', '111');
        $I->fillField('#admin_customer_password_first', 'password');
        $I->fillField('#admin_customer_password_second', 'password');
        $I->click('#button_box__insert_button > div > button');

        $I->see('会員情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->amOnPage('/'.$config['admin_route'].'/customer/new');
        $I->see('会員管理会員登録・編集', '#main .page-header');

        $I->click('#button_box__insert_button > div > button');
        /* ブラウザによるhtml5のエラーなのでハンドリング不可 */
    }

    public function customer_会員編集(AcceptanceTester $I)
    {
        $I->wantTo('EA0502-UC02-T02(& UC02-T02) 会員編集');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $I->fillField('#admin_search_customer_multi', 'test@test.test');
        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');
        $I->see('検索結果 1 件 が該当しました','#search_form > div.row > div > div > div.box-header.with-arrow > h3');

        $I->click('#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr > td.icon_edit > div > a');
        $I->click('#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr > td.icon_edit > div > ul > li:nth-child(1) > a');

        $I->fillField('#admin_customer_name_name01', 'testuser-1');
        $I->click('#button_box__insert_button > div > button');
        $I->see('会員情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->fillField('#admin_customer_name_name01', '');
        $I->click('#button_box__insert_button > div > button');
        /* ブラウザによるhtml5のエラーなのでハンドリング不可 */
    }

    public function customer_会員削除(AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC03-T01(& UC03-T02) 会員削除');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);

        $I->fillField('#admin_search_customer_multi', $customer->getEmail());
        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');

        $I->click('#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr > td.icon_edit > div > a');
        $I->click('#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr > td.icon_edit > div > ul > li:nth-child(2) > a');

        /* ToDo: popup */
    }

    public function customer_CSV出力(AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC05-T01 CSV出力');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');
        $I->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li:nth-child(2) > a');
        $I->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li.dropdown.open > ul > li:nth-child(1) > a');

        /**
         * clientに指定しているphantomjsのdockerコンテナにダウンロードされているかどうかは現在確認不可
         */
    }

    public function customer_CSV出力項目設定(AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC04-T01 CSV出力項目設定');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');
        $I->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li:nth-child(2) > a');
        $I->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li.dropdown.open > ul > li:nth-child(2) > a');

        $I->see('システム設定CSV出力項目設定', '#main .page-header');
        $value = $I->grabValueFrom('#csv-form #csv-type');
        $I->assertEquals('2', $value);
    }

    public function customer_仮会員メール再送(AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC06-T01(& UC06-T02) 仮会員メール再送');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/customer');
        $I->see('会員管理会員マスター', '#main .page-header');

        $I->fillField('#admin_search_customer_multi', 'test@test.test');
        $I->click('#search_form > div.search-box > div.row.btn_area > div > button');

        $I->click('#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr > td.icon_edit > div > a');
        $I->click('#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr > td.icon_edit > div > ul > li:nth-child(3) > a');

        /* ToDo: popup */
    }
}
