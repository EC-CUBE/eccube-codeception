<?php

use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin03
 * @group basicinformation
 * @group ea7
 */
class EA07BasicinfoCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function basicinfo_ショップマスター(\AcceptanceTester $I)
    {
        $I->wantTo('EA0701-UC01-T01 ショップマスター');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop');
        $I->see('基本情報設定SHOPマスター', '#main .page-header');

        // 値変更
        $I->fillField('#point_form #shop_master_company_name', '会社名');
        $I->click('#point_form #aside_column button');
        $I->see('基本情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function basicinfo_特定商取引法(\AcceptanceTester $I)
    {
        $I->wantTo('EA0702-UC01-T01 特定商取引法');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/tradelaw');
        $I->see('ショップ設定特定商取引法', '#main .page-header');

        // 値変更
        $I->fillField('#tradelaw_form #tradelaw_law_company', '販売業者');
        $I->fillField('#tradelaw_form #tradelaw_law_manager', '運営責任者');
        $I->fillField('#tradelaw_form #tradelaw_law_zip_law_zip01', '530');
        $I->fillField('#tradelaw_form #tradelaw_law_zip_law_zip02', '0001');
        $I->selectOption('#tradelaw_form #tradelaw_law_address_law_pref', '大阪府');
        $I->fillField('#tradelaw_form #tradelaw_law_address_law_addr01', '大阪市北区');
        $I->fillField('#tradelaw_form #tradelaw_law_address_law_addr02', '梅田2-4-9 ブリーゼタワー13F');
        $I->fillField('#tradelaw_form #tradelaw_law_tel_law_tel01', '111');
        $I->fillField('#tradelaw_form #tradelaw_law_tel_law_tel02', '111');
        $I->fillField('#tradelaw_form #tradelaw_law_tel_law_tel03', '111');
        $I->fillField('#tradelaw_form #tradelaw_law_email', 'eccube@ec-cube.net');
        $I->fillField('#tradelaw_form #tradelaw_law_url', 'http://www.ec-cube.net');
        $I->fillField('#tradelaw_form #tradelaw_law_term01', 'term01');
        $I->fillField('#tradelaw_form #tradelaw_law_term02', 'term02');
        $I->fillField('#tradelaw_form #tradelaw_law_term03', 'term03');
        $I->fillField('#tradelaw_form #tradelaw_law_term04', 'term04');
        $I->fillField('#tradelaw_form #tradelaw_law_term05', 'term05');
        $I->fillField('#tradelaw_form #tradelaw_law_term06', 'term06');
        $I->click('#tradelaw_form #aside_column button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function basicinfo_利用規約(\AcceptanceTester $I)
    {
        $I->wantTo('EA0703-UC01-T01 利用規約');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/customer_agreement');
        $I->see('ショップ設定利用規約管理', '#main .page-header');

        // 値変更
        $I->fillField('#form1 #customer_agreement_customer_agreement', '会員規約');
        $I->click('#form1 #aside_column button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function basicinfo_支払方法一覧(\AcceptanceTester $I)
    {
        $I->wantTo('EA0704-UC01-T01 支払方法 一覧');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/payment');
        $I->see('ショップ設定支払方法管理', '#main .page-header');

        $I->see('支払方法', '#main .container-fluid .box-title');
        $I->see('郵便振替', '#main .container-fluid .table_list table tbody tr td:nth-child(1)');
    }

    public function basicinfo_支払方法入れ替え(\AcceptanceTester $I)
    {
        $I->wantTo('EA0704-UC02-T01 支払方法 入れ替え');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/payment');
        $I->see('ショップ設定支払方法管理', '#main .page-header');

        // 入れ替え
        $I->see('郵便振替', '#main .container-fluid .table_list table tbody tr td:nth-child(1)');
        $I->click('#main .container-fluid .table_list table tbody tr:nth-child(2) td:nth-child(4) a');
        $I->click('#main .container-fluid .table_list table tbody tr:nth-child(2) td:nth-child(4) ul li:nth-child(4) a'); // 上へ
        $I->see('ランクの移動が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function basicinfo_支払方法登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0705-UC01-T01 支払方法 登録');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/payment');
        $I->see('ショップ設定支払方法管理', '#main .page-header');

        // 登録フォーム
        $I->click('#main .container-fluid div:nth-child(2) .btn_area a');

        // 登録
        $I->fillField('#form1 #payment_register_method', 'payment method1');
        $I->fillField('#form1 #payment_register_charge', '100');
        $I->fillField('#form1 #payment_register_rule_min', '1');
        $I->click('#form1 #aside_column button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('payment method1', '#main .container-fluid .table_list table tbody tr td:nth-child(1)');
    }

    public function basicinfo_支払方法編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0705-UC02-T01 支払方法 編集');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/payment');
        $I->see('ショップ設定支払方法管理', '#main .page-header');

        // 編集フォーム
        $I->click('#main .container-fluid .table_list table tbody tr:nth-child(2) .icon_edit a');
        $I->click('#main .container-fluid .table_list table tbody tr:nth-child(2) .icon_edit ul li:nth-child(1) a');

        // 編集
        $I->fillField('#form1 #payment_register_method', 'payment method2');
        $I->fillField('#form1 #payment_register_charge', '1000');
        $I->click('#form1 #aside_column button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('payment method2', '#main .container-fluid .table_list table tbody tr td:nth-child(1)');
    }

    public function basicinfo_支払方法削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0704-UC03-T01 支払方法 削除');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/payment');
        $I->see('ショップ設定支払方法管理', '#main .page-header');

        // 削除
        $I->click('#main .container-fluid .table_list table tbody tr:nth-child(2) .icon_edit a');
        $I->click('#main .container-fluid .table_list table tbody tr:nth-child(2) .icon_edit ul li:nth-child(2) a');
        $I->acceptPopup();
    }

    public function basicinfo_配送方法一覧(\AcceptanceTester $I)
    {
        $I->wantTo('EA0706-UC01-T01 配送方法 一覧');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/delivery');
        $I->see('ショップ設定配送方法管理', '#main .page-header');

        $I->see('配送方法一覧', '#main .container-fluid .box-title');
        $I->see('サンプル宅配', '#delivery_list__name--2 a');
    }

    public function basicinfo_配送方法登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0707-UC01-T01 配送方法 登録');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/delivery');
        $I->see('ショップ設定配送方法管理', '#main .page-header');

        // 登録フォーム
        $I->click('#delivery_list__name--2 > a');

        // 登録
        $I->fillField('#form1 #delivery_name', '配送業者名');
        $I->fillField('#form1 #delivery_service_name', '名称');
        $I->checkOption('#form1 #delivery_payments_1');
        $I->checkOption('#form1 #delivery_payments_4');
        $I->fillField('#form1 #delivery_free_all', '100');
        $I->click('#form1 #set_fee_all');
        $I->click('#form1 #aside_wrap div:nth-child(2) button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('配送業者名', '#delivery_list__name--2 a');
    }

    public function basicinfo_配送方法編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0707-UC02-T01 配送方法 編集');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/delivery');
        $I->see('ショップ設定配送方法管理', '#main .page-header');

        // 編集フォーム
        $I->click('#main .container-fluid .sortable_list .tableish .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .sortable_list .tableish .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(1) a');

        // 編集
        $I->fillField('#form1 #delivery_name', '配送業者名1');
        $I->click('#form1 #aside_wrap div:nth-child(2) button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('配送業者名1', '#main .container-fluid .sortable_list .tableish div:nth-child(1)');
    }

    public function basicinfo_配送方法削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0706-UC03-T01 配送方法 削除');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/delivery');
        $I->see('ショップ設定配送方法管理', '#main .page-header');

        // 削除
        $I->click('#main .container-fluid .sortable_list .tableish .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .sortable_list .tableish .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(2) a');
        $I->acceptPopup();
    }

    public function basicinfo_税率設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0708-UC01-T01 税率設定');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/tax');
        $I->see('基本情報設定税率設定', '#main .page-header');

        // 一覧
        $I->see('税率一覧', '#form1 div div div:nth-child(5) .box-header h3');
        $I->see('8%', '#tax_rule_list__tax_rate--1');

        // 登録
        $I->fillField('#form1 #tax_rule_tax_rate', '10');
        $I->fillField('#form1 #tax_rule_apply_date', date('Y-m-d').' 00:00:00');
        $I->click('#form1 div div div:nth-child(4) button');
        $I->see('税率設定情報を保存しました。', '#main .container-fluid .alert-success');
        $I->see('10%', '#tax_rule_list__tax_rate--2');

        // 編集
        $I->click('#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(1) .icon_edit .dropdown a');
        $I->click('#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(1) .icon_edit .dropdown ul li:nth-child(1) a');
        $I->fillField('#form1 #tax_rule_tax_rate', '12');
        $I->click('#form1 div div div:nth-child(4) button');
        $I->see('税率設定情報を保存しました。', '#main .container-fluid .alert-success');
        $I->see('12%', '#tax_rule_list__tax_rate--1');

        // 削除
        $I->click('#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(1) .icon_edit .dropdown a');
        $I->click('#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(1) .icon_edit .dropdown ul li:nth-child(2) a');

        // 個別税率設定
        $I->selectOption('#form1 #tax_rule_option_product_tax_rule_0', '1'); // 有効
        $I->click('#form1 div div div:nth-child(2) button');
        $I->see('税率設定情報を保存しました。', '#main .container-fluid .alert-success');
        $value = $I->grabValueFrom('#form1 div div div:nth-child(1) #tax_rule_option_product_tax_rule input[type=radio]:checked');
        $I->assertTrue(($value == 1));
    }

    public function basicinfo_メール設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0709-UC02-T01  メール設定'); // EA0709-UC01-T01 はメールテンプレート登録機能がないのでテスト不可

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/mail');
        $I->see('ショップ設定メール管理', '#main .page-header');

        // テンプレートロード
        $I->selectOption('#form1 #mail_template', 'ご注文ありがとうございます');

        // 編集
        $I->fillField('#form1 #mail_subject', 'ご注文有難うございました');
        $I->click('#form1 #aside_column button');
        $I->see('メールテンプレート情報を保存しました。', '#main .container-fluid .alert-success');
    }

    public function basicinfo_CSV出力項目(\AcceptanceTester $I)
    {
        $I->wantTo('EA0710-UC01-T01  CSV出力項目設定');

        // 表示
        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/setting/shop/csv');
        $I->see('システム設定CSV出力項目設定', '#main .page-header');

        // テンプレートロード
        $I->selectOption('#csv-form #csv-type', '配送CSV');

        // 編集
        $I->selectOption('#csv-form #csv-output', '誕生日');
        $I->click('#csv-form #remove');
        $I->click('#common_button_box__confirm_button button');
        $I->see('CSV出力を設定しました。', '#main .container-fluid .alert-success');
    }
}
