<?php

use Codeception\Util\Fixtures;

/**
 * @group front
 * @group toppage
 * @group ef1
 */
class EF01TopCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function topページ_初期表示(\AcceptanceTester $I)
    {
        $I->wantTo('EF0101-UC01-T01 TOPページ 初期表示');
        $I->amOnPage('/');

        // カテゴリ選択ボックス（キーワード検索用）、キーワード検索入力欄、虫眼鏡ボタンが表示されている
        $I->see('全ての商品', '#search #category_id');
        $I->see('', '#search #name');
        $I->see('', '#search .bt_search');

        // カテゴリ名（カテゴリ検索用）が表示されている
        $categories = Fixtures::get('categories');
        foreach ($categories as $category) {
            $I->see($category->getName(), '#search #category_id option');
        }

        //管理側のコンテンツ管理（新着情報管理）に設定されている情報が、順位順に表示されている
        $today = new DateTime();
        $minus1 = $today->sub(new DateInterval('P1D'));
        $minus2 = $today->sub(new DateInterval('P2D'));

        $I->haveInDatabase('dtb_news', array('news_id' => rand(999, 9999), 'news_date' => $minus1->format('Y-m-d 00:00:00'), 'news_title' => 'タイトル1', 'news_comment' => 'コメント1', 'creator_id' => 1, 'rank' => 2, 'create_date' => $today->format('Y-m-d 00:00:00'), 'update_date' => $today->format('Y-m-d 00:00:00')));
        $I->haveInDatabase('dtb_news', array('news_id' => rand(999, 9999), 'news_date' => $minus2->format('Y-m-d 00:00:00'), 'news_title' => 'タイトル2', 'news_comment' => 'コメント2', 'creator_id' => 1, 'rank' => 3, 'create_date' => $today->format('Y-m-d 00:00:00'), 'update_date' => $today->format('Y-m-d 00:00:00')));
        $I->reloadPage();
        $news = Fixtures::get('news');
        $newsset = array();
        $newsset[] = array (
            'date' => $news[0]->getDate(),
            'title' => $news[0]->getTitle(),
            'comment' => $news[0]->getComment(),
        );
        $newsset[] = array (
            'date' => $minus1->format('Y-m-d 00:00:00'),
            'title' => 'タイトル1',
            'comment' => 'コメント1',
        );
        $newsset[] = array (
            'date' => $minus2->format('Y-m-d 00:00:00'),
            'title' => 'タイトル2',
            'comment' => 'コメント2',
        );
        foreach ($newsset as $key => $news) {
            $I->see($news['title'], '#news_area .newslist dl:nth-child('.(count($newsset) - $key).') .news_title');
        }
    }

    public function topページ_新着情報(\AcceptanceTester $I)
    {
        $I->wantTo('EF0101-UC01-T02 TOPページ 新着情報');
        $I->amOnPage('/');

        // 各新着情報の箇所を押下する
        // Knowhow: javascriptでclick eventハンドリングしている場合はclick('表示文字列')では探せない
        $I->click('#news_area .newslist dt');

        // 押下された新着情報のセクションが広がり、詳細情報、リンクが表示される
        $I->see('一人暮らしからオフィスなどさまざまなシーンで あなたの生活をサポートするグッズをご家庭へお届けします！', '#news_area .newslist dd');

        // 「詳しくはこちら」リンクを押下する
        $today = new DateTime();
        $I->haveInDatabase('dtb_news', array('news_id' => rand(999, 9999), 'news_date' => $today->format('Y-m-d 00:00:00'), 'news_title' => 'タイトル1', 'news_comment' => 'コメント1', 'creator_id' => 1, 'news_url' => 'http://www.ec-cube.net', 'rank' => 2, 'create_date' => $today->format('Y-m-d 00:00:00'), 'update_date' => $today->format('Y-m-d 00:00:00')));
        $I->reloadPage();
        $I->click('#news_area .newslist dt');
        $I->see('詳しくはこちら', '#news_area .newslist dd');
        $I->click('#news_area .newslist dd a');
        $I->seeInTitle('ECサイト構築・リニューアルは「ECオープンプラットフォームEC-CUBE」');
    }

    public function topページ_カテゴリ検索(\AcceptanceTester $I)
    {
        $I->wantTo('EF0101-UC02-T01 TOPページ カテゴリ検索');
        $I->amOnPage('/');

        // カテゴリを選択、そのまま続けて子カテゴリを選択する
        $I->moveMouseOver(['css' => '#category .category-nav li:nth-child(2)']);
        $I->click('#header #category ul li:nth-child(2) ul li:nth-child(1) a');

        // 商品一覧の上部に、選択されたカテゴリとその親カテゴリのリンクが表示される
        $I->see('調理器具', '#topicpath ol');
        $I->see('パーコレーター', '#item_list');
    }

    public function topページ_全件検索(\AcceptanceTester $I)
    {
        $I->wantTo('EF0101-UC03-T01 TOPページ 全件検索');
        $I->amOnPage('/');

        // カテゴリを選択する
        $I->click('#searchform #category_id');

        // 虫眼鏡ボタンを押下する
        $I->click('#searchform .bt_search');

        // 商品一覧の上部に、選択されたカテゴリとその親カテゴリのリンクが表示される
        $I->see('全商品', '#topicpath ol');

        // カテゴリに分類されている商品のみ表示される
        $products = $I->grabMultiple('#item_list .product_item');
        $I->assertTrue((count($products) >= 2));
    }

    public function topページ_カテゴリ絞込検索(\AcceptanceTester $I)
    {
        $I->wantTo('EF0101-UC03-T02 TOPページ カテゴリ絞込検索');
        $I->amOnPage('/');

        // カテゴリを選択する
        $I->selectOption(['id' => 'category_id'], '調理器具');

        // 虫眼鏡ボタンを押下する
        $I->click('#searchform .bt_search');

        // 商品一覧の上部に、選択されたカテゴリとその親カテゴリのリンクが表示される
        $I->see('調理器具', '#topicpath ol');

        // カテゴリに分類されている商品のみ表示される
        $I->see('パーコレーター', '#item_list');
        $I->dontSee('ディナーフォーク', '#item_list');
    }

    public function topページ_キーワード絞込検索(\AcceptanceTester $I)
    {
        $I->wantTo('EF0101-UC03-T02 TOPページ キーワード絞込検索');
        $I->amOnPage('/');

        // キーワードを入力する
        $I->fillField(['id' => 'name'], 'フォーク');

        // 虫眼鏡ボタンを押下する
        $I->click('#searchform .bt_search');

        // 商品一覧の上部に、選択されたカテゴリとその親カテゴリのリンクが表示される
        $I->see('フォーク', '#topicpath ol');

        // カテゴリに分類されている商品のみ表示される
        $I->dontSee('パーコレーター', '#item_list');
        $I->see('ディナーフォーク', '#item_list');
    }
}
