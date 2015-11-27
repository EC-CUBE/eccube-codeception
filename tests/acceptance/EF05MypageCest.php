<?php
use AcceptanceTester;
use Codeception\Util\Fixtures;

/**
 * @group front
 * @group mypage
 * @group ef5
 */
class EF05MypageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function _mypage_初期表示(AcceptanceTester $I)
    {
        $I->wantTo('EF0501-UC01-T01 Mypage 初期表示');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);
        $I->loginAsMember($customer->getEmail(), 'password');

        // TOPページ>マイページ
        $I->amOnPage('/mypage');

        // マイページのヘッダーとして、ご注文履歴/会員情報編集/お届け先編集/退会手続きが表示される
        $I->see('ご注文履歴', '#main_middle .local_nav ul li:nth-child(1) a');
        $I->see('お気に入り一覧', '#main_middle .local_nav ul li:nth-child(2) a');
        $I->see('会員情報編集', '#main_middle .local_nav ul li:nth-child(3) a');
        $I->see('お届け先編集', '#main_middle .local_nav ul li:nth-child(4) a');
        $I->see('退会手続き', '#main_middle .local_nav ul li:nth-child(5) a');
    }

    public function _mypage_ご注文履歴(AcceptanceTester $I)
    {
        $I->wantTo('EF0502-UC01-T01 Mypage ご注文履歴');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>ご注文履歴
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(1) a');

        // 注文内容の状況/簡易情報が表示される、各注文履歴に「詳細を見る」ボタンが表示される
        $I->see('ご注文履歴', '#main_middle .page-heading');
        $I->see('ご注文番号', '#main_middle .historylist_column');
        $I->see('詳細を見る', '#main_middle .historylist_column p a');
    }

    public function _mypage_ご注文履歴詳細(AcceptanceTester $I)
    {
        $I->wantTo('EF0503-UC01-T01 Mypage ご注文履歴詳細');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>ご注文履歴>ご注文履歴詳細
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(1) a');
        $I->click('#main_middle .historylist_column p a');

        // 注文内容の状況/詳細情報/お客様情報/お支払い方法/メール配信履歴一覧/小計・手数料・送料合計・合計金額が表示されている
        $I->see('ご注文履歴詳細', '#main_middle .page-heading');
        $I->see('ご注文状況', '#main_middle .order_detail');
        $I->see('注文受付', '#main_middle .order_detail');
        $I->see('配送情報', '#main_middle #shopping_confirm #confirm_main');
        $I->see('お届け先', '#main_middle #shopping_confirm #confirm_main');
        $I->see('お支払方法', '#main_middle #shopping_confirm #confirm_main');
        $I->see('お問い合わせ', '#main_middle #shopping_confirm #confirm_main');
        $I->see('メール配信履歴一覧', '#main_middle #shopping_confirm #confirm_main');
        $I->see('小計', '#main_middle #shopping_confirm #confirm_side dl:nth-child(1)');
        $I->see('手数料', '#main_middle #shopping_confirm #confirm_side dl:nth-child(2)');
        $I->see('送料合計', '#main_middle #shopping_confirm #confirm_side dl:nth-child(3)');
        $I->see('合計', '#main_middle #shopping_confirm #confirm_side .total_amount .total_price');
    }

    public function mypage_お気に入り一覧(AcceptanceTester $I)
    {
        $I->wantTo('EF0508-UC01-T01 Mypage お気に入り一覧');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>ご注文履歴
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(2) a');

        // 最初はなにも登録されていない
        $I->see('お気に入り一覧', '#main_middle .page-heading');
        $I->see('お気に入りが登録されていません。', '#main_middle .container-fluid .intro');

        // パーコレータをお気に入り登録
        $product = $app['eccube.repository.product']->get(2);
        $app['eccube.repository.customer_favorite_product']->addFavorite($customer, $product);
        $I->click('#main_middle .local_nav ul li:nth-child(2) a');
        $I->see('パーコレーター', '#main_middle .container-fluid #item_list'); 

        // お気に入りを削除
        $I->click('#main_middle .container-fluid #item_list .btn_circle');
        /* ToDo: popup */
    }

    public function _mypage_会員情報編集(AcceptanceTester $I)
    {
        $I->wantTo('EF0504-UC01-T01 Mypage 会員情報編集');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(1);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>会員情報編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(3) a');

        // 会員情報フォームに既存の登録情報が表示される
        $I->seeInField('#main_middle form #entry_name_name01', $customer->getName01());

        // 会員情報フォームに会員情報を入力する
        $I->submitForm("#main_middle form",[
            'entry[name][name01]' => '姓05',
            'entry[name][name02]' => '名05',
            'entry[kana][kana01]' => 'セイ',
            'entry[kana][kana02]' => 'メイ',
            'entry[zip][zip01]' => '530',
            'entry[zip][zip02]' => '0001',
            'entry[address][pref]' => 27,
            'entry[address][addr01]' => '大阪市北区',
            'entry[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'entry[tel][tel01]' => '111',
            'entry[tel][tel02]' => '111',
            'entry[tel][tel03]' => '111',
            'entry[email][first]' => 'acctest05@ec-cube.net',
            'entry[email][second]' => 'acctest05@ec-cube.net',
            'entry[password][first]' => 'password',
            'entry[password][second]' => 'password',
        ]);

        // 会員情報編集（完了）画面が表示される
        $I->see('会員情報編集(完了)', '#main_middle .page-heading');

        // 「トップページへ」ボタンを押下する
        $I->click('#main_middle #deliveradd_input .btn_group p a');

        // TOPページヘ遷移する
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }

    public function _mypage_お届け先編集表示(AcceptanceTester $I)
    {
        $I->wantTo('EF0506-UC01-T01 Mypage お届け先編集表示');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>お届け先編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(4) a');

        $I->see('お届け先編集', '#main_middle .page-heading');
    }

    public function _mypage_お届け先編集作成(AcceptanceTester $I)
    {
        $I->wantTo('EF0506-UC01-T02 Mypage お届け先編集作成');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>お届け先編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(4) a');
        
        // 追加フォーム
        $I->click('#main_middle #deliveradd_select div p a');

        // 入力 & submit
        $I->submitForm("#main_middle form",[
            'customer_address[name][name01]' => '姓05',
            'customer_address[name][name02]' => '名05',
            'customer_address[kana][kana01]' => 'セイ',
            'customer_address[kana][kana02]' => 'メイ',
            'customer_address[zip][zip01]' => '530',
            'customer_address[zip][zip02]' => '0001',
            'customer_address[address][pref]' => 27,
            'customer_address[address][addr01]' => '大阪市北区',
            'customer_address[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'customer_address[tel][tel01]' => '111',
            'customer_address[tel][tel02]' => '111',
            'customer_address[tel][tel03]' => '111',
        ]);

        // お届け先編集ページ
        $I->see('お届け先編集', '#main_middle .page-heading');

        // 一覧に追加されている
        $I->see('大阪市北区', '#main_middle #deliveradd_select .address_table .addr_box');
    }

    public function _mypage_お届け先編集変更(AcceptanceTester $I)
    {
        $I->wantTo('EF0506-UC02-T01 Mypage お届け先編集変更');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>お届け先編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(4) a');
        
        // 変更フォーム
        $I->click('#main_middle #deliveradd_select .address_table .addr_box .btn_edit a');

        // 入力 & submit
        $I->submitForm("#main_middle form",[
            'customer_address[name][name01]' => '姓05',
            'customer_address[name][name02]' => '名05',
            'customer_address[kana][kana01]' => 'セイ',
            'customer_address[kana][kana02]' => 'メイ',
            'customer_address[zip][zip01]' => '530',
            'customer_address[zip][zip02]' => '0001',
            'customer_address[address][pref]' => 27,
            'customer_address[address][addr01]' => '大阪市南区',
            'customer_address[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'customer_address[tel][tel01]' => '111',
            'customer_address[tel][tel02]' => '111',
            'customer_address[tel][tel03]' => '111',
        ]);

        // お届け先編集ページ
        $I->see('お届け先編集', '#main_middle .page-heading');

        // 一覧に追加されている
        $I->see('大阪市南区', '#main_middle #deliveradd_select .address_table .addr_box');
    }

    public function _mypage_お届け先編集削除(AcceptanceTester $I)
    {
        $I->wantTo('EF0503-UC01-T01 Mypage お届け先編集削除');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>お届け先編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(4) a');
        
        // 追加フォーム お届け先１つの登録だと削除できないので2個目を追加
        $I->click('#main_middle #deliveradd_select div p a');

        // 入力 & submit
        $I->submitForm("#main_middle form",[
            'customer_address[name][name01]' => '姓0501',
            'customer_address[name][name02]' => '名0501',
            'customer_address[kana][kana01]' => 'セイ',
            'customer_address[kana][kana02]' => 'メイ',
            'customer_address[zip][zip01]' => '530',
            'customer_address[zip][zip02]' => '0001',
            'customer_address[address][pref]' => 27,
            'customer_address[address][addr01]' => '大阪市西区',
            'customer_address[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'customer_address[tel][tel01]' => '111',
            'customer_address[tel][tel02]' => '111',
            'customer_address[tel][tel03]' => '111',
        ]);
        $I->see('大阪市西区', '#main_middle #deliveradd_select .address_table .addr_box');

        // ×マークをクリック
        $I->click('#main_middle #deliveradd_select .address_table:nth-child(2) .addr_box .icon_edit a');

        /*
            ToDo: popup
            PhantomJSではpopupをハンドリングできない。。よって現状はテスト不可
        // 確認alertでOK
        $I->acceptPopup();

        // 確認
        $I->see('大阪市南区', '#main_middle #deliveradd_select .address_table .addr_box');
        $I->dontSee('大阪市西区', '#main_middle #deliveradd_select .address_table .addr_box');
        */
    }

    public function _mypage_退会手続き未実施(AcceptanceTester $I)
    {
        $I->wantTo('EF0507-UC03-T01 Mypage 退会手続き 未実施'); 
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>退会手続き
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(5) a');
        $I->see('退会手続き', '#main_middle .page-heading');

        // 会員退会手続きへ
        $I->click('#main_middle .unsubscribe_box form .btn_group p button');

        // 未実施
        $I->click('#main_middle .unsubscribe_box form .btn_group p:nth-child(1) a');
        $I->see('ご注文履歴', '#main_middle .page-heading');
    }

    public function _mypage_退会手続き(AcceptanceTester $I)
    {
        $I->wantTo('EF0507-UC03-T02 Mypage 退会手続き');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(2);
        $I->loginAsMember($customer->getEmail(), 'password');
    
        // TOPページ>マイページ>お届け先編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(5) a');
        $I->see('退会手続き', '#main_middle .page-heading');

        // 会員退会手続きへ
        $I->click('#main_middle .unsubscribe_box form .btn_group p button');

        // 未実施
        $I->click('#main_middle .unsubscribe_box form .btn_group p:nth-child(2) button');
        $I->see('退会手続き', '#main_middle .page-heading');
        $I->see('退会が完了いたしました', '#main_middle .unsubscribe_box');
        $I->click('#main_middle .unsubscribe_box .btn_group p a');
        
        // TOPページヘ遷移する
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }
}
