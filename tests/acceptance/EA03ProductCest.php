<?php
use AcceptanceTester;
use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin01
 * @group product
 * @group ea3
 */
class EA03ProductCest
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

    public function product_商品検索(AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC01-T01 商品検索');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'フォーク');
        $I->click('#search_form button');

        $I->see("検索結果 1 件 が該当しました", "#main .container-fluid .box .box-title");
        $I->see("ディナーフォーク", "#main .container-fluid .box-body .item_list");
    }

    public function product_商品検索結果無(AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC01-T02 商品検索 検索結果なし');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'お箸');
        $I->click('#search_form button');

        $I->see("検索条件に該当するデータがありませんでした。", "#main .container-fluid .box .box-title");
    }

    public function product_CSV出力(AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC02-T01 CSV出力');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->click('#search_form button');
        // 「CSVダウンロード」ドロップダウン
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > a');
        // 「CSVダウンロード」リンク
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > ul > li:nth-child(1) > a');

        /**
         * clientに指定しているphantomjsのdockerコンテナにダウンロードされているかどうかは現在確認不可
         */
    }

    public function product_CSV出力項目設定(AcceptanceTester $I)
    {
        $I->wantTo('EA0301-UC02-T02 CSV出力項目設定');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->click('#search_form button');
        // 「CSVダウンロード」ドロップダウン
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > a');
        // 「CSV出力項目設定」リンク
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > ul > li:nth-child(2) > a');

        $I->see('システム設定CSV出力項目設定', '#main .page-header');
        $value = $I->grabValueFrom('#csv-form #csv-type');
        $I->assertEquals('1', $value);
    }

    public function product_一覧からの規格編集規格なし失敗(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC01-T02 一覧からの規格編集 規格なし 失敗');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', '規格なし商品');
        $I->click('#search_form button');

        // 規格アイコン クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > a');
        // 規格リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > ul > li:nth-child(1) > a');
        $I->see('商品管理商品登録(商品規格)', '#main .page-header');

        $I->click('#main > div > div > div > form > div > div.box-body > button');
        $I->cantSee('検索結果 3 件 が該当しました', '#product-class-form > div:nth-child(2) > div > div > div.box-header > h3');
        /**
         エラーになるが、html5のブラウザによるエラーハンドリングなのでチェックできない
         */
    }

    public function product_一覧からの規格編集規格なし(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC01-T01 一覧からの規格編集 規格なし');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', '規格なし商品');
        $I->click('#search_form button');

        // 規格アイコン クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > a');
        // 規格リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > ul > li:nth-child(1) > a');

        $I->see('商品管理商品登録(商品規格)', '#main .page-header');

        $I->selectOption('#form_class_name1', '材質');
        $I->click('#main > div > div > div > form > div > div.box-body > button');

        $I->see('検索結果 3 件 が該当しました', '#product-class-form > div:nth-child(2) > div > div > div.box-header > h3');
        $I->checkOption('#form_product_classes_0_add');
        $I->checkOption('#form_product_classes_1_add');
        $I->checkOption('#form_product_classes_2_add');

        /**
        ボタン押した後POSTされるが、POST処理後同ページにredirectしており、結果をcodeceptionでハンドリングできない...
        $I->click("#product-class-form div:nth-child(3) .btn_area button");
        $I->see('商品規格を登録しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('商品規格を初期化', '#delete');
        */
    }

    public function product_一覧からの規格編集規格あり1(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC02-T01 一覧からの規格編集 規格あり1');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'フォーク');
        $I->click('#search_form button');

        // 規格アイコン クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > a');
        // 規格リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > ul > li:nth-child(1) > a');

        $I->see('商品管理商品登録(商品規格)', '#main .page-header');

        $I->click('#delete');
        $I->acceptPopup();

        /**
         * ToDo: popup
         */
    }

    public function product_一覧からの規格編集規格あり2(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC02-T02 一覧からの規格編集 規格あり2');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'フォーク');
        $I->click('#search_form button');

        // 規格アイコン クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > a');
        // 規格リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(1) > div:nth-child(4) > div > ul > li:nth-child(1) > a');

        $I->see('商品管理商品登録(商品規格)', '#main .page-header');

        $I->click("#product-class-form > div:nth-child(3) > div > button");
        $I->see('商品規格を更新しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_一覧からの商品確認(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC05-T01 一覧からの商品確認');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'フォーク');
        $I->click('#search_form button');

        // アイコンクリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > a');
        // 確認リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > ul > li:nth-child(2) > a');

        /**
         * 確認をクリックすると、別ウィンドウでフロント側の商品詳細ページが表示される為、phantomjsではハンドリングできない
         */
    }

    public function product_一覧からの商品複製(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC05-T02 一覧からの商品複製');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'フォーク');
        $I->click('#search_form button');

        // アイコンクリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > a');
        // 複製リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > ul > li:nth-child(3) > a');

        /**
         * ToDo: popup
         */
    }

    public function product_商品登録非公開(AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T01 商品登録 非公開');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/product/new');
        $I->see('商品管理商品登録', '#main .page-header');

        $I->fillField('#form1 #admin_product_name', 'test product1');
        $I->fillField('#form1 #admin_product_class_price02', '1000');
        $I->click('#form1 #aside_column button:nth-child(1)');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_商品登録公開(AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T02 商品登録 公開');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/product/new');
        $I->see('商品管理商品登録', '#main .page-header');

        $I->fillField('#form1 #admin_product_name', 'test product2');
        $I->fillField('#form1 #admin_product_class_price02', '1000');
        $I->selectOption('#form1 #admin_product_Status input[type=radio]', '公開');
        $I->click('#form1 #aside_column button:nth-child(1)');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_商品編集規格なし(AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T03 商品編集 規格なし');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'test product1');
        $I->click('#search_form button');

        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(3) > a');

        $I->see('商品管理商品登録', '#main .page-header');
        $I->fillField('#form1 #admin_product_name', 'test product11');
        $I->click('#form1 #aside_column button:nth-child(1)');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_商品編集規格あり(AcceptanceTester $I)
    {
        $I->wantTo('EA0302-UC01-T04 商品編集 規格あり');

        /**
         * テストの意味が不明？旧バージョンの内容？
         */
    }

    public function product_一覧からの商品削除(AcceptanceTester $I)
    {
        $I->wantTo('EA0310-UC05-T03 一覧からの商品削除');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product');
        $I->see('商品管理商品マスター', '#main .page-header');

        $I->fillField('#search_form #admin_search_product_id', 'test product2');
        $I->click('#search_form button');

        // アイコンクリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > a');
        // 削除リンク クリック
        $I->click('#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div > div:nth-child(4) > div > ul > li:nth-child(4) > a');

        $I->acceptPopup();
        /**
         * ToDo: popup
         */
    }

    public function product_規格登録(AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC01-T01 規格登録');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/class_name');
        $I->see('商品管理規格編集', '#main .page-header');

        $I->fillField('#admin_class_name_name', 'test class1');
        $I->click('#form1 > div > div > button');

        $I->see('規格を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_規格登録未登録時(AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC01-T02 規格登録 未登録時');
    }

    public function product_規格編集(AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC02-T01 規格編集');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/class_name');
        $I->see('商品管理規格編集', '#main .page-header');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(2) a');
        $value = $I->grabValueFrom('#admin_class_name_name');
        $I->assertEquals('test class1', $value);
        $I->click('#form1 > div > div > button');

        $I->see('規格を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_規格削除(AcceptanceTester $I)
    {
        $I->wantTo('EA0303-UC03-T01 規格削除');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/class_name');
        $I->see('商品管理規格編集', '#main .page-header');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(3) a');

        /* ToDo: popup */
    }

    public function product_分類登録(AcceptanceTester $I)
    {
        $I->wantTo('EA0304-UC01-T01(& UC01-T02/UC02-T01/UC03-T01) 分類登録/編集/削除');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/class_name');
        $I->see('商品管理規格編集', '#main .page-header');

        $I->fillField('#admin_class_name_name', 'test class2');
        $I->click('#form1 > div > div > button');
        $I->see('規格を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(1) a');
        $I->see('規格名： test class2', '#main > div > div:nth-child(1) > div > div > div.box-header > h3');

        $I->fillField('#admin_class_category_name', 'test class2 category1');
        $I->click('#form1 > div > div > button');

        $I->see('分類を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(1) a');
        $value = $I->grabValueFrom('#admin_class_category_name');
        $I->assertEquals('test class2 category1', $value);
        $I->click('#form1 > div > div > button');

        $I->see('分類を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(2) a');

        /* ToDo: popup */
    }

    public function product_カテゴリ登録(AcceptanceTester $I)
    {
        $I->wantTo('EA0305-UC01-T01(& UC01-T02/UC02-T01/UC04-T01) カテゴリ登録/編集/削除');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/category');
        $I->see('商品管理カテゴリ編集', '#main .page-header');

        $I->fillField('#admin_category_name', 'test category1');
        $I->click('#form1 > div > div > button');
        $I->see('カテゴリを保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(1) a');
        $I->see('test category1', '#main > div > div > div.col-md-9 > div > div.box-header > div > a:nth-child(3)');

        $I->fillField('#admin_category_name', 'test category11');
        $I->click('#form1 > div > div > button');
        $I->see('カテゴリを保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown a');
        $I->click('#main .container-fluid .box .box-body .item_box:nth-child(1) .icon_edit .dropdown ul li:nth-child(2) a');

        /* ToDo: popup */

        // csv EA0305-UC04-T01
        $I->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > a');
        $I->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > ul > li:nth-child(1) a');
        $I->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > a'); // 元に戻す
        /* csvがダウンロードされたかは確認不可 */

        // csv EA0305-UC04-T02
        $I->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > a');
        $I->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > ul > li:nth-child(2) > a');
        $I->see('システム設定CSV出力項目設定', '#main .page-header');
        $value = $I->grabValueFrom('#csv-form #csv-type');
        $I->assertEquals('5', $value);

        // サブカテゴリ EA0305-UC01-03 & UC01-04
        $I->amOnPage('/'.$config['admin_route'].'/product/category');
        $I->see('商品管理カテゴリ編集', '#main .page-header');

        $I->click('#main > div > div > div.col-md-9 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(1) > div.item_pattern.td > a');
        $I->see('test category11', '#main > div > div > div.col-md-9 > div > div.box-header > div > a:nth-child(3)');

        $I->fillField('#admin_category_name', 'test category11-1');
        $I->click('#form1 > div > div > button');
        $I->see('カテゴリを保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
    }

    public function product_商品CSV登録(AcceptanceTester $I)
    {
        $I->wantTo('EA0306-UC01-T01(& UC01-T02) 商品CSV登録');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/product_csv_upload');
        $I->see('商品管理商品登録CSVアップロード', '#main .page-header');

        /* CSVのアップロードは不可 */

        // 雛形のダウンロード
        $I->click('#download-button');
        /* ダウンロードファイルの確認は不可*/
    }

    public function product_カテゴリCSV登録(AcceptanceTester $I)
    {
        $I->wantTo('EA0307-UC01-T01(& UC01-T02) カテゴリCSV登録');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/product/category_csv_upload');
        $I->see('商品管理カテゴリ登録CSVアップロード', '#main .page-header');

        /* CSVのアップロードは不可 */

        // 雛形のダウンロード
        $I->click('#download-button');
        /* ダウンロードファイルの確認は不可*/
    }
}
