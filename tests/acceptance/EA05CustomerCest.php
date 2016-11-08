<?php

use Codeception\Util\Fixtures;
use Page\Admin\CsvSettingsPage;
use Page\Admin\CustomerManagePage;
use Page\Admin\CustomerEditPage;

/**
 * @group admin
 * @group admin02
 * @group customer
 * @group ea5
 */
class EA05CustomerCest
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

    public function customer_検索(\AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC01-T01 検索');


        $CustomerListPage = CustomerManagePage::go($I);

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();

        $CustomerListPage->検索($customer->getEmail());
        $I->see('検索結果 1 件 が該当しました', CustomerManagePage::$検索結果メッセージ);
    }

    public function customer_検索結果なし(\AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC01-T02 検索 結果なし');

        CustomerManagePage::go($I)
            ->検索('testacount@ec-cube.com');
        $I->see('検索条件に該当するデータがありませんでした。', CustomerManagePage::$検索結果メッセージ);
    }

    public function customer_会員登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0502-UC01-T02(& UC01-T02) 会員登録');

        $CustomerRegisterPage = CustomerEditPage::go($I)
            ->入力_姓('testuser')
            ->入力_名('testuser')
            ->入力_セイ('テストユーザー')
            ->入力_メイ('テストユーザー')
            ->入力_都道府県(['27' => '大阪府'])
            ->入力_郵便番号1('530')
            ->入力_郵便番号2('0001')
            ->入力_市区町村名('大阪市北区梅田2-4-9')
            ->入力_番地_ビル名('ブリーゼタワー13F')
            ->入力_Eメール('test@test.test')
            ->入力_電話番号1('111')
            ->入力_電話番号2('111')
            ->入力_電話番号3('111')
            ->入力_パスワード('password')
            ->入力_パスワード確認('password')
            ->登録();

        $I->see('会員情報を保存しました。', CustomerEditPage::$登録完了メッセージ);

        $CustomerRegisterPage->登録();
        /* TODO [html5] ブラウザによるhtml5のエラーなのでハンドリング不可 */
    }

    public function customer_会員編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0502-UC02-T02(& UC02-T02) 会員編集');

        $CustomerListPage = CustomerManagePage::go($I)
            ->検索('test@test.test');

        $I->see('検索結果 1 件 が該当しました',CustomerManagePage::$検索結果メッセージ);

        $CustomerListPage->一覧_編集(1);

        $CustomerRegisterPage = CustomerEditPage::at($I)
            ->入力_姓('testuser-1')
            ->登録();
        $I->see('会員情報を保存しました。', CustomerEditPage::$登録完了メッセージ);

        $CustomerRegisterPage
            ->入力_姓('')
            ->登録();
        /* TODO [html5] ブラウザによるhtml5のエラーなのでハンドリング不可 */
    }

    public function customer_会員削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC03-T01(& UC03-T02) 会員削除');

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();

        CustomerManagePage::go($I)
            ->検索($customer->getEmail())
            ->一覧_削除(1);

        $I->acceptPopup();

        // TODO [漏れ] UC03-T02 会員削除キャンセル
    }

    public function customer_CSV出力(\AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC05-T01 CSV出力');

        CustomerManagePage::go($I)
            ->検索()
            ->CSVダウンロード();

        /**
         * TODO [download] clientに指定しているphantomjsのdockerコンテナにダウンロードされているかどうかは現在確認不可
         */
    }

    public function customer_CSV出力項目設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC04-T01 CSV出力項目設定');


        CustomerManagePage::go($I)
            ->検索()
            ->CSV出力項目設定();

        CsvSettingsPage::at($I);
        $value = $I->grabValueFrom(CsvSettingsPage::$CSVタイプ);
        $I->assertEquals('2', $value);
    }

    public function customer_仮会員メール再送(\AcceptanceTester $I)
    {
        $I->wantTo('EA0501-UC06-T01(& UC06-T02) 仮会員メール再送');

        $I->resetEmails();

        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer(null, false);

        CustomerManagePage::go($I)
            ->検索($customer->getEmail())
            ->一覧_仮会員メール再送(1);
        $I->acceptPopup();
        $I->wait(10);

        $I->seeEmailCount(2);
        foreach (array($customer->getEmail(), 'admin@example.com') as $email) {
            $I->seeInLastEmailSubjectTo($email, '会員登録のご確認');
            $I->seeInLastEmailTo($email, $customer->getName01().' '.$customer->getName02().' 様');
        }
    }
}
