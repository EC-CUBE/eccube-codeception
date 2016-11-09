<?php

use Codeception\Util\Fixtures;
use Page\Admin\CsvSettingsPage;
use Page\Admin\OrderManagePage;
use Page\Admin\OrderEditPage;

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
        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        OrderManagePage::go($I)->検索();
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', OrderManagePage::$検索結果_メッセージ);

        OrderManagePage::go($I)->検索('gege@gege.com');
        $I->see('検索条件に該当するデータがありませんでした。', OrderManagePage::$検索結果_メッセージ);
    }

    public function order_CSVダウンロード(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC02-T01(& UC02-T02/UC03-T01/UC03-T2) CSVダウンロード');

        $config = Fixtures::get('config');
        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $OrderListPage = OrderManagePage::go($I)->検索();
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', OrderManagePage::$検索結果_メッセージ);

        /* ダウンロード（ダウンロードはチェックできないので、テスト不可） */
        $OrderListPage->受注CSVダウンロード実行();
        $OrderListPage->配送CSVダウンロード実行();

        /* 項目設定 */
        $OrderListPage->受注CSV出力項目設定();

        CsvSettingsPage::at($I);
        $value = $I->grabValueFrom(CsvSettingsPage::$CSVタイプ);
        $I->assertEquals(3, $value);

        OrderManagePage::go($I)
            ->検索()
            ->配送CSV出力項目設定();

        CsvSettingsPage::at($I);
        $value = $I->grabValueFrom(CsvSettingsPage::$CSVタイプ);
        $I->assertEquals(4, $value);
    }

    public function order_受注編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC05-T01(& UC05-T02/UC06-T01) 受注編集');

        $config = Fixtures::get('config');
        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $OrderListPage = OrderManagePage::go($I)->検索();
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', OrderManagePage::$検索結果_メッセージ);

        /* 編集 */
        $OrderListPage->一覧_編集(1);

        $OrderRegisterPage = OrderEditPage::at($I)
            ->入力_姓('')
            ->受注情報登録();

        /* 異常系 */
        $I->see('入力されていません。', OrderEditPage::$姓_エラーメッセージ);

        /* 正常系 */
        $OrderRegisterPage
            ->入力_姓('aaa')
            ->入力_セイ('アアア')
            ->入力_メイ('アアア')
            ->入力_郵便番号1('111')
            ->入力_郵便番号2('1111')
            ->入力_市区町村名('bbb')
            ->入力_番地_ビル名('bbb')
            ->入力_電話番号1('111')
            ->入力_電話番号2('111')
            ->入力_電話番号3('111')
            ->入力_支払方法(['4' => '郵便振替'])
            ->注文者情報をコピー()
            ->入力_配送業者(['1' => 'サンプル業者'])
            ->受注情報登録();

        $I->see('受注情報を保存しました。', OrderEditPage::$登録完了メッセージ);

        /* ステータス変更 */
        $OrderRegisterPage
            ->入力_受注ステータス(['2' => '入金待ち'])
            ->受注情報登録();

        $I->see('受注情報を保存しました。', OrderEditPage::$登録完了メッセージ);
    }

    public function order_受注削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0401-UC08-T01(& UC08-T02) 受注削除');

        $config = Fixtures::get('config');
        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });

        $OrderListPage = OrderManagePage::go($I)->検索();
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', OrderManagePage::$検索結果_メッセージ);

        $OrderListPage->一覧_削除(1);
        $I->acceptPopup();
    }

    public function order_受注メール通知(\AcceptanceTester $I)
    {
        $I->wantTo('EA0402-UC01-T01 受注メール通知');

        $config = Fixtures::get('config');
        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $OrderListPage = OrderManagePage::go($I)->検索();
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', OrderManagePage::$検索結果_メッセージ);

        $OrderListPage->一覧_メール通知(1);
    }

    public function order_一括メール通知(\AcceptanceTester $I)
    {
        $I->wantTo('EA0402-UC02-T01(& UC02-T02) 一括メール通知');

        $config = Fixtures::get('config');
        $findOrders = Fixtures::get('findOrders'); // Closure
        $TargetOrders = array_filter($findOrders(), function ($Order) use ($config) {
            return $Order->getOrderStatus()->getId() != $config['order_processing'];
        });
        $OrderListPage = OrderManagePage::go($I)->検索();
        $I->see('検索結果 '.count($TargetOrders).' 件 が該当しました', OrderManagePage::$検索結果_メッセージ);

        $OrderListPage
            ->一覧_全選択()
            ->メール一括通知();

        // TODO メール確認
    }

    public function order_受注登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0405-UC01-T01(& UC01-T02) 受注登録');

        $OrderRegisterPage = OrderEditPage::go($I)
            ->受注情報登録();

        /* 異常系 */
        $I->dontSee('受注情報を保存しました。', OrderEditPage::$登録完了メッセージ);


        /* 正常系 */
        $OrderRegisterPage
            ->入力_受注ステータス(['1' => '新規受付'])
            ->入力_姓('order1')
            ->入力_名('order1')
            ->入力_セイ('アアア')
            ->入力_メイ('アアア')
            ->入力_郵便番号1('111')
            ->入力_郵便番号2('1111')
            ->入力_都道府県(['1' => '北海道'])
            ->入力_市区町村名('bbb')
            ->入力_番地_ビル名('bbb')
            ->入力_Eメール('test@test.com')
            ->入力_電話番号1('111')
            ->入力_電話番号2('111')
            ->入力_電話番号3('111')
            ->商品検索('パーコレーター')
            ->商品検索結果_選択(1)
            ->入力_支払方法(['4'=> '郵便振替'])
            ->注文者情報をコピー()
            ->入力_配送業者(['1' => 'サンプル業者'])
            ->受注情報登録();

        $I->see('受注情報を保存しました。', OrderEditPage::$登録完了メッセージ);
    }
}
