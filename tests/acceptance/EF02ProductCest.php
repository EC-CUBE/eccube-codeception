<?php
use AcceptanceTester;
use Codeception\Util\Fixtures;

/**
 * @group front
 * @group product 
 * @group ef2
 */
class EF02ProductCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function product_商品一覧初期表示(AcceptanceTester $I)
    {
        $I->wantTo('EF0201-UC01-T01 商品一覧ページ 初期表示');
        $I->amOnPage('/');
        
        // TOPページ>商品一覧（ヘッダーのいずれかのカテゴリを選択）へ遷移
        $I->moveMouseOver(['css' => '#category .category-nav li:nth-child(2)']);
        $I->click('#header #category ul li:nth-child(2) ul li:nth-child(1) a');

        // 登録商品がカテゴリごとに一覧表示される
        $I->see('調理器具', '#topicpath ol');

        // 一覧ページで商品がサムネイル表示される
        $I->see('パーコレーター', '#item_list');
    }

    public function product_商品一覧ヘッダ以外のカテゴリリンク(AcceptanceTester $I)
    {
        $I->wantTo('EF0201-UC01-T02 商品一覧ページ ヘッダ以外のカテゴリリンク');
        $I->amOnPage('/');
    
        // MEMO: EF0201-UC01-T02... テスト項目の記述が意味不明なのでskip
    }
    
    public function product_商品一覧ソート(AcceptanceTester $I)
    {
        $I->wantTo('EF0201-UC03-T01 商品一覧ページ ソート');
        $I->amOnPage('/');
    
        // TOPページ>商品一覧（ヘッダーのいずれかのカテゴリを選択）へ遷移
        $I->moveMouseOver(['css' => '#category .category-nav li:nth-child(2)']);
        $I->click('#header #category ul li:nth-child(2) a');

        // 各商品のサムネイルが表示される デフォルトは価格順
        $products = $I->grabMultiple('#item_list .col-sm-3 .product_item a dl dt');
        $pPos = 0;
        $fPos = 0;
        foreach ($products as $key => $product) {
            if ($product == 'パーコレーター') {
                $pPos = $key; 
            }
            if ($product == 'ディナーフォーク') {
                $fPos = $key;
            }
        }
        $I->assertTrue(($pPos < $fPos));

        // ソート条件の選択リストを変更する 価格順->新着順
        $I->selectOption("#page_navi_top select[name = 'disp_number']", '30件');
        $I->selectOption("#page_navi_top select[name = 'orderby']", '新着順');

        // 変更されたソート条件に従い、商品がソートされる
        $products = $I->grabMultiple('#item_list .col-sm-3 .product_item a dl dt');
        $pPos = 0;
        $fPos = 0;
        foreach ($products as $key => $product) {
            if ($product == 'パーコレーター') {
                $pPos = $key; 
            }
            if ($product == 'ディナーフォーク') {
                $fPos = $key;
            }
        }
        // ToDo
        // まだバグ修正前 https://github.com/EC-CAUBE/ec-cube/issues/1118
        // 修正されたら以下を追加
        //$I->assertTrue(($pPos > $fPos));
    }
    
    public function product_商品一覧表示件数(AcceptanceTester $I)
    {
        $I->wantTo('EF0201-UC04-T01 商品一覧ページ 表示件数');
        $I->amOnPage('/');
        
        // TOPページ>商品一覧（ヘッダーのいずれかのカテゴリを選択）へ遷移
        $I->moveMouseOver(['css' => '#category .category-nav li:nth-child(2)']);
        $I->click('#header #category ul li:nth-child(2) a');

        // 各商品のサムネイルが表示される
        $config = Fixtures::get('test_config');
        $productNum = $config['fixture_product_num'] + $config['fixture_customer_num'] + 2;
        $itemNum = ($productNum >= 15) ? 15 : $productNum;
        $products = $I->grabMultiple('#item_list .product_item');
        $I->assertTrue((count($products) == $itemNum));
        
        // 表示件数の選択リストを変更する
        $I->selectOption("#page_navi_top select[name = 'disp_number']", '30件');

        // 変更された表示件数分が1画面に表示される
        $itemNum = ($productNum >= 30) ? 30 : $productNum;
        $products = $I->grabMultiple('#item_list .product_item');
        $I->assertTrue((count($products) == $itemNum));
    }
    
    public function product_商品一覧ページング(AcceptanceTester $I)
    {
        $I->wantTo('EF0201-UC04-T02 商品一覧ページ ページング');
        $I->amOnPage('/');
        
        // TOPページ>商品一覧（ヘッダーのいずれかのカテゴリを選択）へ遷移
        $I->moveMouseOver(['css' => '#category .category-nav li:nth-child(2)']);
        $I->click('#header #category ul li:nth-child(2) a');

        // 絞込検索条件では、検索数が多い場合、「次へ」「前へ」「ページ番号」が表示される
        $I->see('1', '#main .pagination ul .active a');
        $I->see('2', '#main .pagination ul li a');
        $I->see('次へ', '#main .pagination ul li a');
        
        // 選択されたリンクに応じてページングされる
        $I->click('#main .pagination ul li:nth-child(2) a'); // '2'をクリック
        $I->see('2', '#main .pagination ul .active a');
        $I->click('#main .pagination ul li:nth-child(1) a'); // '前へ'をクリック
        $I->see('1', '#main .pagination ul .active a');
        $I->click('#main .pagination ul li:nth-child(3) a'); // '次へ'をクリック
        $I->see('2', '#main .pagination ul .active a');
    }
    
    public function product_商品詳細初期表示(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC01-T01 商品詳細 初期表示');
        $I->setStock(2, 0);
        $I->amOnPage('/products/detail/2');
        
        // 「カートに入れる」ボタンが、非活性となり「ただいま品切れ中です」と表示される。
        $I->see('ただいま品切れ中です','#form1 button');
    }
    
    public function product_商品詳細カテゴリリンク(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC01-T02 商品詳細 カテゴリリンク');
        $I->amOnPage('/products/detail/2');

        // 商品詳細の関連カテゴリに表示されている、カテゴリリンクを押下する
        $I->moveMouseOver(['css' => '#category .category-nav li:nth-child(2)']);
        $I->click('#header #category ul li:nth-child(2) ul li:nth-child(1) a');

        // 登録商品がカテゴリごとに一覧表示される
        $I->see('調理器具', '#topicpath ol');

        // 一覧ページで商品がサムネイル表示される
        $I->see('パーコレーター', '#item_list');
    }
    
    public function product_商品詳細サムネイル(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC01-T03 商品詳細 サムネイル');
        $I->amOnPage('/products/detail/2');
	$config = Fixtures::get('test_config');

        // デフォルトサムネイル表示確認
        $img = $I->grabAttributeFrom('#item_photo_area .slick-active img', 'src');
        $I->assertTrue(('http://'.$config['hostname'].'/upload/save_image/cafe-1.jpg' == $img));
    
        // 2個目のサムネイルクリック
        $I->click('#item_photo_area .slick-dots li:nth-child(2) button');
        $img = $I->grabAttributeFrom('#item_photo_area .slick-active img', 'src');
        $I->assertTrue(('http://'.$config['hostname'].'/upload/save_image/cafe-2.jpg' == $img));
    }
    
    public function product_商品詳細カート1(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC02-T01 商品詳細 カート 注文数＜販売制限数＜在庫数の注文');
        $I->setStock(2, 10);
        $I->amOnPage('/products/detail/2');

        // 「カートに入れる」ボタンを押下する
        $I->buyThis(4);

        // 入力された個数分が、カート画面の対象商品に追加されている。
        $I->see('パーコレーター', '.cart_item .item_box .item_detail');
        $I->see('4', '.cart_item .item_box .item_quantity');

        // カートを空に
        $I->makeEmptyCart();
    }

    public function product_商品詳細カート2(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC02-T02 商品詳細 カート 販売制限数＜注文数＜在庫数の注文');
        $I->setStock(2, 10);
        $I->amOnPage('/products/detail/2');
        
        // 「カートに入れる」ボタンを押下する
        $I->buyThis(6);

        // 入力された個数分が、カート画面の対象商品に追加されている。
        $I->see('パーコレーター', '.cart_item .item_box .item_detail');
        $I->see('5', '.cart_item .item_box .item_quantity');

        // カートの数量に販売制限数が設定され、注文数が販売制限数を上回っている旨のメッセージを表示する。
        $I->see('選択された商品(パーコレーター)は販売制限しております。', '#main .message .errormsg');

        // カートを空に
        $I->makeEmptyCart();
    }

    public function product_商品詳細カート3(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC02-T03 商品詳細 カート 販売制限数＜在庫数＜注文数の注文');
        $I->setStock(2, 10);
        $I->amOnPage('/products/detail/2');
        
        // 「カートに入れる」ボタンを押下する
        $I->buyThis(12);

        // 入力された個数分が、カート画面の対象商品に追加されている。
        $I->see('パーコレーター', '.cart_item .item_box .item_detail');
        $I->see('5', '.cart_item .item_box .item_quantity');

        // カートの数量に販売制限数が設定され、注文数が販売制限数を上回っている旨のメッセージを表示する。
        $I->see('選択された商品(パーコレーター)は販売制限しております。', '#main .message .errormsg');

        // カートを空に
        $I->makeEmptyCart();
    }
    
    public function product_商品詳細カート4(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC02-T04 商品詳細(規格あり) カート 注文数＜販売制限数＜在庫数の注文');
        $I->setStock(1, array(10, 10, 10, 10, 10, 10, 10, 10, 10));
        $I->amOnPage('/products/detail/1');

        // 「カートに入れる」ボタンを押下する
        $I->selectOption("#form1 #classcategory_id1", 'プラチナ');
        $I->selectOption("#form1 #classcategory_id2", '150cm');
        $I->buyThis(1);

        // 入力された個数分が、カート画面の対象商品に追加されている。
        $I->see('ディナーフォーク', '.cart_item .item_box .item_detail');
        $I->see('1', '.cart_item .item_box .item_quantity');

        // カートを空に
        $I->makeEmptyCart();
    }
    
    public function product_商品詳細カート5(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC02-T05 商品詳細(規格あり) カート 販売制限数＜注文数＜在庫数の注文');
        $I->setStock(1, array(10, 10, 10, 10, 10, 10, 10, 10, 10));
        $I->amOnPage('/products/detail/1');
        
        // 「カートに入れる」ボタンを押下する
        $I->selectOption("#form1 #classcategory_id1", 'プラチナ');
        $I->selectOption("#form1 #classcategory_id2", '150cm');
        $I->buyThis(3);

        // 入力された個数分が、カート画面の対象商品に追加されている。
        $I->see('ディナーフォーク', '.cart_item .item_box .item_detail');
        $I->see('2', '.cart_item .item_box .item_quantity');

        // カートの数量に販売制限数が設定され、注文数が販売制限数を上回っている旨のメッセージを表示する。
        $I->see('選択された商品(ディナーフォーク - プラチナ - 150cm)は販売制限しております。', '#main .message .errormsg');

        // カートを空に
        $I->makeEmptyCart();
    }

    public function product_商品詳細カート6(AcceptanceTester $I)
    {
        $I->wantTo('EF0202-UC02-T06 商品詳細(規格あり) カート 販売制限数＜在庫数＜注文数の注文');
        $I->setStock(1, array(10, 10, 10, 10, 10, 10, 10, 10, 10));
        $I->amOnPage('/products/detail/1');
        
        // 「カートに入れる」ボタンを押下する
        $I->selectOption("#form1 #classcategory_id1", 'プラチナ');
        $I->selectOption("#form1 #classcategory_id2", '150cm');
        $I->buyThis(12);

        // 入力された個数分が、カート画面の対象商品に追加されている。
        $I->see('ディナーフォーク', '.cart_item .item_box .item_detail');
        $I->see('2', '.cart_item .item_box .item_quantity');

        // カートの数量に販売制限数が設定され、注文数が販売制限数を上回っている旨のメッセージを表示する。
        $I->see('選択された商品(ディナーフォーク - プラチナ - 150cm)は販売制限しております。', '#main .message .errormsg');

        // カートを空に
        $I->makeEmptyCart();
    }
}
