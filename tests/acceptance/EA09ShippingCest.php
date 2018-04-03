<?php

use Codeception\Util\Fixtures;
use Page\Admin\CsvSettingsPage;
use Page\Admin\ShippingManagePage;
use Page\Admin\ShippingEditPage;
use Eccube\Entity\Master\ShippingStatus;

/**
 * @group admin
 * @group admin01
 * @group shipping
 * @group ea9
 */
class EA09ShippingCest
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

    public function shipping出荷検索(\AcceptanceTester $I)
    {
        $I->wantTo('EA0901-UC01-T01(& UC01-T02) 出荷検索');

        $TargetShippings = Fixtures::get('findShippings'); // Closure
        $Shippings = $TargetShippings();
        ShippingManagePage::go($I)->検索();
        $I->see('検索結果 : '.count($Shippings).' 件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        ShippingManagePage::go($I)->検索('gege@gege.com');
        $I->see('検索結果 : 0 件が該当しました', ShippingManagePage::$検索結果_メッセージ);
    }

    /**
     * @env firefox
     * @env chrome
     */
    public function shipping出荷CSVダウンロード(\AcceptanceTester $I)
    {
        $I->wantTo('EA0901-UC02-T01 出荷CSVダウンロード');

        $findShippings = Fixtures::get('findShippings'); // Closure
        $TargetShippings = array_filter($findShippings(), function ($Shipping) {
            return $Shipping->getShippingStatus()->getId() != ShippingStatus::PROCESSING;
        });
        $ShippingListPage = ShippingManagePage::go($I)->検索();
        $I->see('検索結果：'.count($TargetShippings).'件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        $ShippingListPage->出荷CSVダウンロード実行();
        // make sure wait to download file completely
        $I->wait(10);
        $ShippingCSV = $I->getLastDownloadFile('/^shipping\d{14}\.csv$/');
        $I->assertGreaterOrEquals(count($TargetShippings), count(file($ShippingCSV)), '検索結果以上の行数があるはず');
    }

    public function shipping出荷情報のCSV出力項目変更設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0901-UC02-T02 出荷情報のCSV出力項目変更設定');

        $findShippings = Fixtures::get('findShippings'); // Closure
        $TargetShippings = array_filter($findShippings(), function ($Shipping) {
            return $Shipping->getShippingStatus()->getId() != ShippingStatus::PROCESSING;
        });
        $ShippingListPage = ShippingManagePage::go($I)->検索();
        $I->see('検索結果：'.count($TargetShippings).'件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        /* 項目設定 */
        $ShippingListPage->出荷CSV出力項目設定();

        CsvSettingsPage::at($I);
        $value = $I->grabValueFrom(CsvSettingsPage::$CSVタイプ);
        $I->assertEquals(3, $value);
    }

    /**
     * TODO: will fix when apply style guide for admin shipping edit
     *
     * @skip
     */
    public function shipping出荷編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0901-UC05-T01(& UC05-T02/UC06-T01) 出荷編集');

        $findShippings = Fixtures::get('findShippings'); // Closure
        $TargetShippings = array_filter($findShippings(), function ($Shipping) {
            return $Shipping->getShippingStatus()->getId() != ShippingStatus::PROCESSING;
        });
        $ShippingListPage = ShippingManagePage::go($I)->検索();
        $I->see('検索結果：'.count($TargetShippings).'件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        /* 編集 */
        $ShippingListPage->一覧_編集(1);

        $ShippingRegisterPage = ShippingEditPage::at($I)
            ->入力_姓('')
            ->出荷情報登録();

        /* 異常系 */
        $I->see('入力されていません。', ShippingEditPage::$姓_エラーメッセージ);

        /* 正常系 */
        $ShippingRegisterPage
            ->入力_姓('aaa')
            ->入力_セイ('アアア')
            ->入力_メイ('アアア')
            ->入力_郵便番号1('060')
            ->入力_郵便番号2('0000')
            ->入力_都道府県(['1' => '北海道'])
            ->入力_市区町村名('bbb')
            ->入力_番地_ビル名('bbb')
            ->入力_電話番号1('111')
            ->入力_電話番号2('111')
            ->入力_電話番号3('111')
            ->入力_番地_ビル名('address 2')
            ->入力_支払方法(['4' => '郵便振替'])
            ->出荷情報登録();

        $I->see('出荷情報を保存しました。', ShippingEditPage::$登録完了メッセージ);

        /* ステータス変更 */
        $ShippingRegisterPage
            ->入力_出荷ステータス(['2' => '入金待ち'])
            ->出荷情報登録();

        $I->see('出荷情報を保存しました。', ShippingEditPage::$登録完了メッセージ);
    }

    public function shipping出荷削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0901-UC08-T01(& UC08-T02) 出荷削除');

        $findShippings = Fixtures::get('findShippings'); // Closure
        $TargetShippings = array_filter($findShippings(), function ($Shipping) {
            return $Shipping->getShippingStatus()->getId() != ShippingStatus::PROCESSING;
        });

        $ShippingListPage = ShippingManagePage::go($I)->検索();
        $I->see('検索結果：'.count($TargetShippings).'件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        // 削除
        $ShippingNumForDel = $ShippingListPage->一覧_注文番号(1);
        $ShippingListPage->一覧_削除(1);
        $I->acceptPopup();

        $I->getScenario()->incomplete('未実装：出荷マスターでの出荷削除が未実装');

        $I->see('出荷情報を削除しました', ['css' => '#main > div > div:nth-child(1) > div']);
        $I->assertNotEquals($ShippingNumForDel, $ShippingListPage->一覧_注文番号(1));

        // 削除キャンセル
        $ShippingNumForDontDel = $ShippingListPage->一覧_注文番号(1);
        $ShippingListPage->一覧_削除(1);
        $I->cancelPopup();

        $I->assertEquals($ShippingNumForDontDel, $ShippingListPage->一覧_注文番号(1));
    }

    public function shipping出荷メール通知(\AcceptanceTester $I)
    {
        $I->wantTo('EA0902-UC01-T01 出荷メール通知');

        $I->resetEmails();
        $findShippings = Fixtures::get('findShippings');
        $NewShippings = array_filter($findShippings(), function ($Shipping) {
            return $Shipping->getShippingStatus()->getId() == ShippingStatus::NEW;
        });
        $Shipping = array_pop($NewShippings);
        $ShippingListPage = ShippingManagePage::go($I)->検索($Shipping->getId());
        $I->see('検索結果：1件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        $ShippingListPage->一覧_メール通知(1);

        $I->selectOption(['id' => 'template-change'], ['1' => '注文受付メール']);
        $I->click(['id' => 'mailConfirm']);
        $I->scrollTo(['id' => 'sendMail'], 0, 100);
        $I->wait(1);
        $I->click(['id' => 'sendMail']);

        $I->wait(3);
        $I->seeEmailCount(2);

        $I->seeInLastEmailSubjectTo('admin@example.com', 'ご注文ありがとうございます');
    }

    public function shipping一括メール通知(\AcceptanceTester $I)
    {
        $I->wantTo('EA0902-UC02-T01(& UC02-T02) 一括メール通知');

        $I->resetEmails();

        $config = Fixtures::get('config');
        $findShippings = Fixtures::get('findShippings'); // Closure
        $TargetShippings = array_filter($findShippings(), function ($Shipping) use ($config) {
            return $Shipping->getShippingStatus()->getId() != ShippingStatus::PROCESSING;
        });
        $ShippingListPage = ShippingManagePage::go($I)->検索();
        $I->see('検索結果：'.count($TargetShippings).'件が該当しました', ShippingManagePage::$検索結果_メッセージ);

        $ShippingListPage
            ->一覧_全選択()
            ->メール一括通知();

        $I->selectOption(['id' => 'template-change'], ['1' => '注文受付メール']);
        $I->click(['id' => 'mailConfirm']);
        $I->scrollTo(['id' => 'sendMail'], 0, 100);
        $I->wait(1);
        $I->click(['id' => 'sendMail']);

        $I->wait(5);
        $I->seeEmailCount(20);
    }

    public function shipping出荷登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0905-UC01-T01(& UC01-T02) 出荷登録');

        $ShippingRegisterPage = ShippingEditPage::go($I)->出荷情報登録();

        /* 異常系 */
        $I->dontSee('出荷情報を保存しました。', ShippingEditPage::$登録完了メッセージ);

        $I->getScenario()->incomplete('未実装：出荷への商品の追加が未実装');

        /* 正常系 */
        $ShippingRegisterPage
            ->入力_出荷ステータス(['1' => '新規受付'])
            ->入力_姓('shipping1')
            ->入力_名('shipping1')
            ->入力_セイ('アアア')
            ->入力_メイ('アアア')
            ->入力_郵便番号1('060')
            ->入力_郵便番号2('0000')
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
            ->出荷情報登録();

        $I->see('出荷情報を保存しました。', ShippingEditPage::$登録完了メッセージ);
    }

}
