<?php

use Codeception\Util\Fixtures;
use Page\Admin\CategoryCsvUploadPage;
use Page\Admin\CategoryManagePage;
use Page\Admin\CsvSettingsPage;
use Page\Admin\ProductClassCategoryPage;
use Page\Admin\ProductClassPage;
use Page\Admin\ProductCsvUploadPage;
use Page\Admin\ProductManagePage;
use Page\Admin\ProductEditPage;

/**
 * @group admin
 * @group admin01
 * @group product
 * @group ea3
 */
class EA03ProductCest
{
    const ページタイトル = '#main .page-header';

    public function _before(\AcceptanceTester $I)
    {
        // すべてのテストケース実施前にログインしておく
        // ログイン後は管理アプリのトップページに遷移している
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function product_商品検索(\AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC01-T01 商品検索');

        ProductManagePage::go($I)->検索('フォーク');

        $I->see("検索結果 1 件 が該当しました", ProductManagePage::$検索結果_メッセージ);
        $I->see("ディナーフォーク", ProductManagePage::$検索結果_一覧);
    }

    public function product_商品検索結果無(\AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC01-T02 商品検索 検索結果なし');

        ProductManagePage::go($I)->検索('お箸');

        $I->see("検索条件に該当するデータがありませんでした。", ProductManagePage::$検索結果_メッセージ);
    }

    /**
     * @env firefox
     * @env chrome
     */
    public function product_CSV出力(\AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC02-T01 CSV出力');

        $findProducts = Fixtures::get('findProducts');
        $Products = $findProducts();
        ProductManagePage::go($I)
            ->検索()
            ->CSVダウンロード();

        $I->see("検索結果 ".count($Products)." 件 が該当しました", ProductManagePage::$検索結果_メッセージ);

        $ProductCSV = $I->getLastDownloadFile('/^product_\d{14}\.csv$/');
        $I->assertGreaterOrEquals(count($Products), count(file($ProductCSV)), '検索結果以上の行数があるはず');
    }

    public function product_CSV出力項目設定(\AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC02-T02 CSV出力項目設定');

        ProductManagePage::go($I)
            ->検索()
            ->CSV出力項目設定();

        $I->see('システム設定CSV出力項目設定', self::ページタイトル);
        $value = $I->grabValueFrom(CsvSettingsPage::$CSVタイプ);
        $I->assertEquals('1', $value);
    }

    public function product_一覧からの規格編集規格なし失敗(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC01-T02 一覧からの規格編集 規格なし 失敗');

        ProductManagePage::go($I)
            ->検索('規格なし商品')
            ->検索結果_規格設定(1);

        $I->see('商品管理商品登録(商品規格)', self::ページタイトル);

        $I->click('#main > div > div > div > form > div > div.box-body > button');
        $I->seeElement(['css' => '#form_class_name1:invalid']); //規格1がエラー
        $I->dontSeeElement(['id' => 'result_box__list']);
    }

    public function product_一覧からの規格編集規格なし(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC01-T01 一覧からの規格編集 規格なし');

        ProductManagePage::go($I)
            ->検索('規格なし商品')
            ->検索結果_規格設定(1);

        $I->see('商品管理商品登録(商品規格)', self::ページタイトル);

        $I->selectOption(['id' => 'form_class_name1'], '材質');
        $I->click('#main > div > div > div > form > div > div.box-body > button');

        $I->see('検索結果 3 件 が該当しました', '#product-class-form > div:nth-child(2) > div > div > div.box-header > h3');
        $I->checkOption(['id' => 'form_product_classes_0_add']);
        $I->checkOption(['id' => 'form_product_classes_1_add']);
        $I->checkOption(['id' => 'form_product_classes_2_add']);

        $I->click("#product-class-form div:nth-child(3) .btn_area button");
        $I->waitForElement(['css' => '#main .container-fluid div:nth-child(1) .alert-success']);
        $I->see('商品規格を登録しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('商品規格を初期化', '#delete');
    }

    public function product_一覧からの規格編集規格あり2(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC02-T02 一覧からの規格編集 規格あり2');

        $findProducts = Fixtures::get('findProducts');
        $Products = array_filter($findProducts(), function ($Product) {
            return $Product->hasProductClass();
        });
        $Product = array_pop($Products);
        ProductManagePage::go($I)
            ->検索($Product->getName())
            ->検索結果_規格設定(1);

        $I->see('商品管理商品登録(商品規格)', self::ページタイトル);

        $I->click("#product-class-form > div:nth-child(3) > div > button");
        $I->see('商品規格を更新しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_一覧からの商品複製(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC05-T02 一覧からの商品複製');

        $findProducts = Fixtures::get('findProducts');
        $Products = array_filter($findProducts(), function ($Product) {
            return $Product->hasProductClass();
        });
        $Product = array_pop($Products);
        ProductManagePage::go($I)
            ->検索($Product->getName())
            ->検索結果_複製(1);

        $I->acceptPopup();
    }

    /**
     * ATTENTION 削除すると後続の規格編集関連のテストが失敗するため、最後に実行する
     */
    public function product_一覧からの規格編集規格あり1(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC02-T01 一覧からの規格編集 規格あり1');

        $findProducts = Fixtures::get('findProducts');
        $Products = array_filter($findProducts(), function ($Product) {
            return $Product->hasProductClass();
        });
        $Product = array_pop($Products);
        ProductManagePage::go($I)
            ->検索($Product->getName())
            ->検索結果_規格設定(1);

        $I->see('商品管理商品登録(商品規格)', self::ページタイトル);

        $I->click('#delete');
        $I->acceptPopup();
    }

    public function product_商品登録非公開(\AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T01 商品登録 非公開');

        ProductEditPage::go($I)
            ->入力_商品名('test product1')
            ->入力_販売価格('1000')
            ->登録();

        $I->see('登録が完了しました。', ProductEditPage::$登録結果メッセージ);
    }

    public function product_商品登録公開(\AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T02 商品登録 公開');

        ProductEditPage::go($I)
            ->入力_商品名('test product2')
            ->入力_販売価格('1000')
            ->入力_公開()
            ->登録();

        $I->see('登録が完了しました。', ProductEditPage::$登録結果メッセージ);
    }

    public function product_商品編集規格なし(\AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T03 商品編集 規格なし');

        ProductManagePage::go($I)
            ->検索('test product1')
            ->検索結果_選択(1);

        ProductEditPage::at($I)
            ->入力_商品名('test product11')
            ->登録();

        $I->see('登録が完了しました。', ProductEditPage::$登録結果メッセージ);
    }

    public function product_商品編集規格あり(\AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T04 商品編集 規格あり');

        /**
         * TODO [other] テストの意味が不明？旧バージョンの内容？
         */
    }

    public function product_一覧からの商品削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC05-T03 一覧からの商品削除');

        ProductManagePage::go($I)
            ->検索('test product2')
            ->検索結果_削除(1);

        $I->acceptPopup();
    }

    public function product_規格登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC01-T01 規格登録');

        ProductClassPage::go($I)
            ->入力_規格名('test class1')
            ->企画作成();

        $I->see('規格を保存しました。', ProductClassPage::$登録完了メッセージ);
    }

    public function product_規格登録未登録時(\AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC01-T02 規格登録 未登録時');
        // TODO [fixture] 規格が1件も登録されていない状態にする
    }

    public function product_規格編集(\AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC02-T01 規格編集');

        $ProductClassPage = ProductClassPage::go($I)->一覧_編集(1);

        $value = $I->grabValueFrom(ProductClassPage::$規格名);
        $I->assertEquals('test class1', $value);

        $ProductClassPage->企画作成();

        $I->see('規格を保存しました。', ProductClassPage::$登録完了メッセージ);
    }

    public function product_規格削除(\AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC03-T01 規格削除');

        ProductClassPage::go($I)->一覧_削除(1);

        $I->acceptPopup();
    }

    // TODO [漏れ] EA0308-UC01-T01 規格表示順の変更

    public function product_分類登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0304-UC01-T01(& UC01-T02/UC02-T01/UC03-T01) 分類登録/編集/削除');

        $ProductClassPage = ProductClassPage::go($I)
            ->入力_規格名('test class2')
            ->企画作成();

        $I->see('規格を保存しました。', ProductClassPage::$登録完了メッセージ);

        $ProductClassPage->一覧_分類登録(1);
        $I->see('規格名： test class2', '#main > div > div:nth-child(1) > div > div > div.box-header > h3');

        $ProductClassCategoryPage = ProductClassCategoryPage::at($I)
            ->入力_分類名('test class2 category1')
            ->分類作成();

        $I->see('分類を保存しました。', ProductClassCategoryPage::$登録完了メッセージ);

        $ProductClassCategoryPage->一覧_編集(1);
        $value = $I->grabValueFrom(ProductClassCategoryPage::$分類名);
        $I->assertEquals('test class2 category1', $value);

        $ProductClassCategoryPage->分類作成();
        $I->see('分類を保存しました。', $ProductClassCategoryPage::$登録完了メッセージ);

        $ProductClassCategoryPage->一覧_削除(1);
        $I->acceptPopup();
    }

    // TODO [漏れ] EA0311-UC01-T01 分類表示順の変更

    public function product_カテゴリ登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0305-UC01-T01(& UC01-T02/UC02-T01/UC04-T01) カテゴリ登録/編集/削除');

        $CategoryPage = CategoryManagePage::go($I)
            ->入力_カテゴリ名('test category1')
            ->カテゴリ作成();

        $I->see('カテゴリを保存しました。', CategoryManagePage::$登録完了メッセージ);

        $CategoryPage->一覧_編集(1);

        $I->see('test category1', CategoryManagePage::$パンくず_1階層);

        $CategoryPage
            ->入力_カテゴリ名('test category11')
            ->カテゴリ作成();

        $I->see('カテゴリを保存しました。', $CategoryPage::$登録完了メッセージ);

        // csv EA0305-UC04-T01
        $CategoryPage
            ->CSVダウンロード実行()
            ->CSVダウンロードメニュー(); // 元に戻す
        /* csvがダウンロードされたかは確認不可 */

        // csv EA0305-UC04-T02
        $CategoryPage->CSV出力項目設定();

        CsvSettingsPage::at($I);
        $value = $I->grabValueFrom(CsvSettingsPage::$CSVタイプ);
        $I->assertEquals('5', $value);

        // サブカテゴリ EA0305-UC01-03 & UC01-04
        $CategoryPage = CategoryManagePage::go($I)
            ->一覧_選択(1);

        $I->see('test category11', CategoryManagePage::$パンくず_1階層);

        $CategoryPage
            ->入力_カテゴリ名('test category11-1')
            ->カテゴリ作成();
        $I->see('カテゴリを保存しました。', CategoryManagePage::$登録完了メッセージ);

        // カテゴリ削除
        $CategoryPage->一覧_削除(1);
        $I->acceptPopup();
    }

    // TODO [漏れ] EA0309-UC01-T01 カテゴリ表示順の変更

    public function product_商品CSV登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0306-UC01-T01 商品CSV登録');

        ProductManagePage::go($I)->検索('アップロード商品');
        $I->see('検索条件に該当するデータがありませんでした。', ProductManagePage::$検索結果_メッセージ);

        ProductCsvUploadPage::go($I)
            ->入力_CSVファイル('product.csv')
            ->CSVアップロード();
        $I->see('商品登録CSVファイルをアップロードしました', CategoryCsvUploadPage::$完了メッセージ);

        ProductManagePage::go($I)->検索('アップロード商品');
        $I->see('検索結果 3 件 が該当しました', ProductManagePage::$検索結果_メッセージ);
    }

    /**
     * @env firefox
     * @env chrome
     */
    public function product_商品CSV登録雛形ファイルダウンロード(\AcceptanceTester $I)
    {
        $I->wantTo('EA0306-UC01-T02 商品CSV登録雛形ファイルダウンロード');

        ProductCsvUploadPage::go($I)->雛形ダウンロード();
        $ProductTemplateCSV = $I->getLastDownloadFile('/^product\.csv$/');
        $I->assertEquals(1, count(file($ProductTemplateCSV)), 'ヘッダ行だけのファイル');
    }

    public function product_カテゴリCSV登録(\AcceptanceTester $I)
    {
        $I->wantTo('EA0307-UC01-T01(& UC01-T02) カテゴリCSV登録');

        CategoryManagePage::go($I);
        $I->dontSeeElement(['xpath' => '//div[@id="sortable_list_box"]//a[contains(text(), "アップロードカテゴリ")]']);

        CategoryCsvUploadPage::go($I)
            ->入力_CSVファイル('category.csv')
            ->CSVアップロード();

        $I->see('カテゴリ登録CSVファイルをアップロードしました', CategoryCsvUploadPage::$完了メッセージ);

        CategoryManagePage::go($I);
        $I->seeElement(['xpath' => '//div[@id="sortable_list_box"]//a[contains(text(), "アップロードカテゴリ1")]']);
        $I->seeElement(['xpath' => '//div[@id="sortable_list_box"]//a[contains(text(), "アップロードカテゴリ2")]']);
        $I->seeElement(['xpath' => '//div[@id="sortable_list_box"]//a[contains(text(), "アップロードカテゴリ3")]']);
    }

    /**
     * @env firefox
     * @env chrome
     */
    public function product_カテゴリCSV登録雛形ファイルダウンロード(\AcceptanceTester $I)
    {
        $I->wantTo('EA0307-UC01-T02 カテゴリCSV登録雛形ファイルダウンロード');

        // 雛形のダウンロード
        CategoryCsvUploadPage::go($I)->雛形ダウンロード();
        $CategoryTemplateCSV = $I->getLastDownloadFile('/^category\.csv$/');
        $I->assertEquals(1, count(file($CategoryTemplateCSV)), 'ヘッダ行だけのファイル');
    }

    /**
     * XXX [new window] 確認リンクをクリックすると別ウィンドウが立ち上がるため、後続のテストが失敗してしまう...
     */
    public function product_一覧からの商品確認(\AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC05-T01 一覧からの商品確認');

        ProductManagePage::go($I)
            ->検索('フォーク')
            ->検索結果_選択(1);

        // 確認リンク クリック
        // $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > ul > li:nth-child(2) > a');

        /**
         * 確認をクリックすると、別ウィンドウでフロント側の商品詳細ページが表示される為、phantomjsではハンドリングできない
         */
    }
}
