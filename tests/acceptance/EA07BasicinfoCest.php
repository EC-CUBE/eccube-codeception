<?php

use Codeception\Util\Fixtures;
use Page\Admin\CsvSettingsPage;
use Page\Admin\CustomerAgreementSettingPage;
use Page\Admin\DeliveryEditPage;
use Page\Admin\DeliveryManagePage;
use Page\Admin\MailSettingsPage;
use Page\Admin\PaymentEditPage;
use Page\Admin\PaymentManagePage;
use Page\Admin\ShopSettingPage;
use Page\Admin\TaxManagePage;
use Page\Admin\TradelawSettingPage;

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

        ShopSettingPage::go($I)
            ->入力_会社名('会社名')
            ->登録();

        $I->see('基本情報を保存しました。', ShopSettingPage::$登録完了メッセージ);
    }

    public function basicinfo_特定商取引法(\AcceptanceTester $I)
    {
        $I->wantTo('EA0702-UC01-T01 特定商取引法');

        TradelawSettingPage::go($I)
            ->入力_販売業者('販売業者')
            ->入力_運営責任者('運営責任者')
            ->入力_郵便番号1('530')
            ->入力_郵便番号2('0001')
            ->入力_都道府県('大阪府')
            ->入力_市区町村名('大阪市北区')
            ->入力_番地_ビル名('梅田2-4-9 ブリーゼタワー13F')
            ->入力_電話番号1('111')
            ->入力_電話番号2('111')
            ->入力_電話番号3('111')
            ->入力_Eメール('eccube@ec-cube.net')
            ->入力_URL('http://www.ec-cube.net')
            ->入力_商品代金以外の必要料金('term01')
            ->入力_注文方法('term02')
            ->入力_支払方法('term03')
            ->入力_支払期限('term04')
            ->入力_引き渡し時期('term05')
            ->入力_返品交換について('term06')
            ->登録();

        $I->see('登録が完了しました。', TradelawSettingPage::$登録完了メッセージ);
    }

    public function basicinfo_利用規約(\AcceptanceTester $I)
    {
        $I->wantTo('EA0703-UC01-T01 利用規約');

        CustomerAgreementSettingPage::go($I)
            ->入力_会員規約('会員規約')
            ->登録();

        $I->see('登録が完了しました。', CustomerAgreementSettingPage::$登録完了メッセージ);
    }

    public function basicinfo_支払方法一覧(\AcceptanceTester $I)
    {
        $I->wantTo('EA0704-UC01-T01 支払方法 一覧');

        // 表示
        $PaymentManagePage = PaymentManagePage::go($I);

        $I->see('支払方法', PaymentManagePage::$一覧_タイトル);
        $I->see('郵便振替', $PaymentManagePage->一覧_支払方法(1));
    }

    public function basicinfo_支払方法入れ替え(\AcceptanceTester $I)
    {
        $I->wantTo('EA0704-UC02-T01 支払方法 入れ替え');

        // 表示
        $PaymentManagePage = PaymentManagePage::go($I);

        // 入れ替え
        $I->see('郵便振替', $PaymentManagePage->一覧_支払方法(1));
        $PaymentManagePage->一覧_下に(1);
        $I->see('ランクの移動が完了しました。', PaymentManagePage::$登録完了メッセージ);
    }

    public function basicinfo_支払方法登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0705-UC01-T01 支払方法 登録');

        // 表示
        // 登録フォーム
        PaymentManagePage::go($I)
            ->新規入力();

        // 登録
        PaymentEditPage::at($I)
            ->入力_支払方法('payment method1')
            ->入力_手数料('100')
            ->入力_利用条件下限('1')
            ->登録();

        $PaymentManagePage = PaymentManagePage::at($I);
        $I->see('登録が完了しました。', PaymentManagePage::$登録完了メッセージ);
        $I->see('payment method1', $PaymentManagePage->一覧_支払方法(1));
    }

    public function basicinfo_支払方法編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0705-UC02-T01 支払方法 編集');

        // 表示
        PaymentManagePage::go($I)
            ->一覧_編集(1);

        // 編集
        PaymentEditPage::at($I)
            ->入力_支払方法('payment method2')
            ->入力_手数料('1000')
            ->登録();

        $PaymentManagePage = PaymentManagePage::at($I);
        $I->see('登録が完了しました。', PaymentManagePage::$登録完了メッセージ);
        $I->see('payment method2', $PaymentManagePage->一覧_支払方法(1));
    }

    public function basicinfo_支払方法削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0704-UC03-T01 支払方法 削除');

        // 表示
        // 削除
        PaymentManagePage::go($I)
            ->一覧_削除(1);
        $I->acceptPopup();
    }

    public function basicinfo_配送方法一覧(\AcceptanceTester $I)
    {
        $I->wantTo('EA0706-UC01-T01 配送方法 一覧');

        // 表示
        DeliveryManagePage::go($I);

        $I->see('サンプル宅配', '#delivery_list__name--2 a');
    }

    public function basicinfo_配送方法登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0707-UC01-T01 配送方法 登録');

        // 表示
        DeliveryManagePage::go($I)
            ->新規登録();

        // 登録
        DeliveryEditPage::at($I)
            ->入力_配送業者名('配送業者名')
            ->入力_名称('名称')
            ->入力_支払方法選択(['1', '4'])
            ->入力_全国一律送料('100')
            ->登録();

        DeliveryManagePage::at($I);
        $I->see('登録が完了しました。', DeliveryManagePage::$登録完了メッセージ);
        $I->see('配送業者名', '#delivery_list__name--2 a');
    }

    public function basicinfo_配送方法編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0707-UC02-T01 配送方法 編集');

        // 表示
        DeliveryManagePage::go($I)
            ->一覧_編集(1);

        // 編集
        DeliveryEditPage::at($I)
            ->入力_配送業者名('配送業者名1')
            ->登録();

        DeliveryManagePage::at($I);
        $I->see('登録が完了しました。', DeliveryManagePage::$登録完了メッセージ);
        $I->see('配送業者名1', '#main .container-fluid .sortable_list .tableish div:nth-child(1)');
    }

    public function basicinfo_配送方法削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0706-UC03-T01 配送方法 削除');

        DeliveryManagePage::go($I)
            ->一覧_削除(1);

        $I->acceptPopup();
    }

    // TODO [漏れ] EA0706-UC02-T01	配送方法一覧の順序入れ替え

    public function basicinfo_税率設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0708-UC01-T01 税率設定');

        // 表示
        $TaxManagePage = TaxManagePage::go($I);

        // 一覧
        $I->see('税率一覧', '#form1 div div div:nth-child(5) .box-header h3');
        $I->see('8%', '#tax_rule_list__tax_rate--1');

        // 登録
        $TaxManagePage
            ->入力_消費税率('10')
            ->入力_適用日時(date('Y-m-d').' 00:00:00')
            ->共通税率設定_登録();
        $I->see('10%', $TaxManagePage->一覧_税率(2));

        // 編集
        $TaxManagePage
            ->一覧_編集(1)
            ->入力_消費税率(12)
            ->共通税率設定_登録();

        $I->see('税率設定情報を保存しました。', TaxManagePage::$登録完了メッセージ);
        $I->see('12%', $TaxManagePage->一覧_税率(1));

        // 削除
        $TaxManagePage->一覧_削除(1);

        // 個別税率設定
        $TaxManagePage
            ->入力_個別税率設定('1')
            ->個別税率設定_登録();

        $I->see('税率設定情報を保存しました。', TaxManagePage::$登録完了メッセージ);
        $value = $I->grabValueFrom(['css' => '#tax_rule_option_product_tax_rule input[type=radio]:checked']);
        $I->assertTrue(($value == 1));
    }

    public function basicinfo_メール設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0709-UC02-T01  メール設定'); // EA0709-UC01-T01 はメールテンプレート登録機能がないのでテスト不可

        // 表示
        MailSettingsPage::go($I)
            ->入力_テンプレート('ご注文ありがとうございます')
            ->入力_件名('ご注文有難うございました')
            ->登録();

        $I->see('メールテンプレート情報を保存しました。', MailSettingsPage::$登録完了メッセージ);
    }

    public function basicinfo_CSV出力項目(\AcceptanceTester $I)
    {
        $I->wantTo('EA0710-UC01-T01  CSV出力項目設定');

        // 表示
        CsvSettingsPage::go($I)
            ->入力_CSVタイプ('配送CSV')
            ->選択_出力項目('誕生日')
            ->削除()
            ->設定();

        $I->see('CSV出力を設定しました。', CsvSettingsPage::$登録完了メッセージ);
    }
}
