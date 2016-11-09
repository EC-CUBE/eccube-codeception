<?php

use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin01
 * @group order
 * @group ea4
 */
class EA04OrderCest
{
    public function _before(\AcceptanceTester $I)
    {
        // すべてのテストケース実施前にログインしておく
        // ログイン後は管理アプリのトップページに遷移している
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function order_受注検索(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC01-T01(& UC01-T02) 受注検索');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->see('受注管理受注マスター', '#main .page-header');

        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', '#main > div > div.row > div > div > div.box-header.with-arrow > h3');

        $I->fillField(['id' => 'admin_search_order_multi'], 'gege@gege.com');
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索条件に該当するデータがありませんでした。', '#main > div > div.row > div > div > div > h3');
    }

    public function order_CSVダウンロード(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC02-T01(& UC02-T02/UC03-T01/UC03-T2) CSVダウンロード');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->see('受注管理受注マスター', '#main .page-header');

        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', '#main > div > div.row > div > div > div.box-header.with-arrow > h3');

        /* ダウンロード（ダウンロードはチェックできないので、テスト不可） */
        $I->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li:nth-child(2) > a');
        $I->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li.dropdown.open > ul > li:nth-child(1) > a');
        $I->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li.dropdown.open > ul > li:nth-child(2) > a');

        /* 項目設定 */
        $I->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li.dropdown.open > ul > li:nth-child(3) > a');
        $I->see('システム設定CSV出力項目設定', '#main .page-header');
        $value = $I->grabValueFrom(['id' => 'csv-type']);
        $I->assertEquals(3, $value);
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li:nth-child(2) > a');
        $I->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li.dropdown.open > ul > li:nth-child(4) > a');
        $I->see('システム設定CSV出力項目設定', '#main .page-header');
        $value = $I->grabValueFrom(['id' => 'csv-type']);
        $I->assertEquals(4, $value);
    }

    public function order_受注編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC05-T01(& UC05-T02/UC06-T01) 受注編集');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->see('受注管理受注マスター', '#main .page-header');

        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', '#main > div > div.row > div > div > div.box-header.with-arrow > h3');

        /* 編集 */
        $I->click('#dropdown-form > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > a');
        $I->click('#dropdown-form > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > ul > li:nth-child(1) > a');
        $I->see('受注管理受注登録・編集', '#main .page-header');

        /* 異常系 */
        $I->fillField(['id' => 'order_name_name01'], '');
        $I->click('#aside_wrap > form > div > div.row.btn_area > p > button');
        $I->see('入力されていません。', '#aside_wrap > form > div > div:nth-child(2) > div.box-body.accpanel > div > div:nth-child(2) > div > span > ul > p');

        /* 正常系 */
        $I->fillField(['id' => 'order_name_name01'], 'aaa');
        $I->fillField(['id' => 'order_kana_kana01'], 'アアア');
        $I->fillField(['id' => 'order_kana_kana02'], 'アアア');
        $I->fillField(['id' => 'order_zip_zip01'], '111');
        $I->fillField(['id' => 'order_zip_zip02'], '1111');
        $I->fillField(['id' => 'order_address_addr01'], 'bbb');
        $I->fillField(['id' => 'order_address_addr02'], 'bbb');
        $I->fillField(['id' => 'order_tel_tel01'], '111');
        $I->fillField(['id' => 'order_tel_tel02'], '111');
        $I->fillField(['id' => 'order_tel_tel03'], '111');
        $I->selectOption(['id' => 'order_Payment'], 4);
        $I->click('#aside_wrap > form > div > div:nth-child(5) > div.box-body.accpanel > div > div.btn_area > ul > li > a');
        $I->selectOption(['id' => 'order_Shippings_0_Delivery'], 1);
        $I->click('#aside_wrap > form > div > div.row.btn_area > p > button');
        $I->see('受注情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        /* ステータス変更 */
        $I->selectOption(['id' => 'order_OrderStatus'], 2);
        $I->click('#aside_wrap > form > div > div.row.btn_area > p > button');
        $I->see('受注情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function order_受注削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC08-T01(& UC08-T02) 受注削除');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->see('受注管理受注マスター', '#main .page-header');

        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', '#main > div > div.row > div > div > div.box-header.with-arrow > h3');

        $I->click('#dropdown-form > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > a');
        $I->click('#dropdown-form > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > ul > li:nth-child(2) > a');
        $I->acceptPopup();
    }

    public function order_受注メール通知(\AcceptanceTester $I)
    {
        $I->wantTo('EA0402-UC01-T01 受注メール通知');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->see('受注管理受注マスター', '#main .page-header');

        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', '#main > div > div.row > div > div > div.box-header.with-arrow > h3');

        $I->click('#dropdown-form > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > a');
        $I->click('#dropdown-form > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > ul > li:nth-child(3) > a');

        // TODO メール一括送信のテスト
    }

    public function order_一括メール通知(\AcceptanceTester $I)
    {
        $I->wantTo('EA0402-UC02-T01(& UC02-T02) 一括メール通知');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order');
        $I->see('受注管理受注マスター', '#main .page-header');

        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $I->click('#search_form > div.row.btn_area > div > button');
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', '#main > div > div.row > div > div > div.box-header.with-arrow > h3');

        $I->click('#dropmenu > a');
        $I->click('#dropmenu > ul > li > a');
        $I->acceptPopup();

        // TODO メール一括送信のテスト
    }

    public function order_受注登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0405-UC01-T01(& UC01-T02) 受注登録');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/order/new');
        $I->see('受注管理受注登録・編集', '#main .page-header');

        /* 異常系 */
        $I->click('#aside_wrap > form > div > div.row.btn_area > p > button');
        $I->dontSee('受注情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        /* 正常系 */
        $I->selectOption(['id' => 'order_OrderStatus'], 1);
        $I->fillField(['id' => 'order_name_name01'], 'order1');
        $I->fillField(['id' => 'order_name_name02'], 'order1');
        $I->fillField(['id' => 'order_kana_kana01'], 'アアア');
        $I->fillField(['id' => 'order_kana_kana02'], 'アアア');
        $I->fillField(['id' => 'order_zip_zip01'], '111');
        $I->fillField(['id' => 'order_zip_zip02'], '1111');
        $I->selectOption(['id' => 'order_address_pref'], 1);
        $I->fillField(['id' => 'order_address_addr01'], 'bbb');
        $I->fillField(['id' => 'order_address_addr02'], 'bbb');
        $I->fillField(['id' => 'order_email'], 'test@test.com');
        $I->fillField(['id' => 'order_tel_tel01'], '111');
        $I->fillField(['id' => 'order_tel_tel02'], '111');
        $I->fillField(['id' => 'order_tel_tel03'], '111');
        $I->click('#aside_wrap > form > div > div:nth-child(3) > div.box-body.accpanel > div > div.btn_area > ul > li:nth-child(1) > a');
        $I->fillField(['id' => 'admin_search_product_id'], 'パーコレータ');
        $I->click('#searchProductModalButton');
        $I->click('#searchProductModalList > div > table > tbody > tr > td.text-right > button');
        $I->selectOption(['id' => 'order_Payment'], 4);
        $I->click('#aside_wrap > form > div > div:nth-child(5) > div.box-body.accpanel > div > div.btn_area > ul > li > a');
        $I->selectOption(['id' => 'order_Shippings_0_Delivery'], 1);
        $I->click('#aside_wrap > form > div > div.row.btn_area > p > button');
        $I->see('受注情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

    }
}
