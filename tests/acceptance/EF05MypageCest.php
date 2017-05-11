<?php

use Codeception\Util\Fixtures;
use Faker\Factory as Faker;

/**
 * @group front
 * @group mypage
 * @group ef5
 */
class EF05MypageCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function mypage_初期表示(\AcceptanceTester $I)
    {
        $I->wantTo('EF0501-UC01-T01 Mypage 初期表示');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
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

    public function mypage_ご注文履歴(\AcceptanceTester $I)
    {
        $I->wantTo('EF0502-UC01-T01 Mypage ご注文履歴');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $createOrders = Fixtures::get('createOrders');
        $Orders = $createOrders($customer);

        $I->loginAsMember($customer->getEmail(), 'password');

        // TOPページ>マイページ>ご注文履歴
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(1) a');

        // 注文内容の状況/簡易情報が表示される、各注文履歴に「詳細を見る」ボタンが表示される
        $I->see('ご注文履歴', '#main_middle .page-heading');
        $I->see('ご注文番号', '#main_middle .historylist_column');
        $I->see('詳細を見る', '#main_middle .historylist_column p a');
    }

    public function mypage_ご注文履歴詳細(\AcceptanceTester $I)
    {
        $I->wantTo('EF0503-UC01-T01 Mypage ご注文履歴詳細');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $createOrders = Fixtures::get('createOrders');
        $Orders = $createOrders($customer);

        $I->loginAsMember($customer->getEmail(), 'password');

        // TOPページ>マイページ>ご注文履歴>ご注文履歴詳細
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(1) a');
        $I->click('#main_middle .historylist_column p a');

        // 注文内容の状況/詳細情報/お客様情報/お支払い方法/メール配信履歴一覧/小計・手数料・送料合計・合計金額が表示されている
        $I->see('ご注文履歴詳細', '#main_middle .page-heading');
        $I->see('ご注文状況', '#main_middle .order_detail');
        // $I->see('注文受付', '#main_middle .order_detail'); TODO 受注ステータスが可変するためテストが通らない場合がある
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

    public function mypage_お気に入り一覧(\AcceptanceTester $I)
    {
        $I->wantTo('EF0508-UC01-T01 Mypage お気に入り一覧');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $I->loginAsMember($customer->getEmail(), 'password');

        // TOPページ>マイページ>ご注文履歴
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(2) a');

        // 最初はなにも登録されていない
        $I->see('お気に入り一覧', '#main_middle .page-heading');
        $I->see('お気に入りが登録されていません。', '#main_middle .container-fluid .intro');

        // お気に入り登録
        $I->amOnPage('/products/detail/2');
        $I->click('#favorite');

        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(2) a');
        $I->see('パーコレーター', '#main_middle .container-fluid #item_list');

        // お気に入りを削除
        $I->click('#main_middle .container-fluid #item_list .btn_circle');
        $I->acceptPopup();
    }

    public function mypage_会員情報編集(\AcceptanceTester $I)
    {
        $I->wantTo('EF0504-UC01-T01 Mypage 会員情報編集');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $I->loginAsMember($customer->getEmail(), 'password');
        $faker = Fixtures::get('faker');
        $new_email = microtime(true).'.'.$faker->safeEmail;

        // TOPページ>マイページ>会員情報編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(3) a');

        // 会員情報フォームに既存の登録情報が表示される
        $I->seeInField(['id' => 'entry_name_name01'], $customer->getName01());

        $form = [
            'entry[name][name01]' => '姓05',
            'entry[name][name02]' => '名05',
            'entry[kana][kana01]' => 'セイ',
            'entry[kana][kana02]' => 'メイ',
            'entry[zip][zip01]' => '530',
            'entry[zip][zip02]' => '0001',
            'entry[address][pref]' => ['value' => '27'],
            'entry[address][addr01]' => '大阪市北区',
            'entry[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'entry[tel][tel01]' => '111',
            'entry[tel][tel02]' => '111',
            'entry[tel][tel03]' => '111',
            'entry[email][first]' => $new_email,
            'entry[email][second]' => $new_email,
            'entry[password][first]' => 'password',
            'entry[password][second]' => 'password',
        ];

        $findPluginByCode = Fixtures::get('findPluginByCode');
        $Plugin = $findPluginByCode('MailMagazine');
        if ($Plugin) {
            $I->amGoingTo('メルマガプラグインを発見したため、メルマガを購読します');
            // 必須入力が効いてない https://github.com/EC-CUBE/mail-magazine-plugin/issues/29
            $form['entry[mailmaga_flg]'] = '1';
        }
        // 会員情報フォームに会員情報を入力する
        $I->submitForm("#main_middle form", $form);


        // 会員情報編集（完了）画面が表示される
        $I->see('会員情報編集(完了)', '#main_middle .page-heading');

        // 「トップページへ」ボタンを押下する
        $I->click('#main_middle #deliveradd_input .btn_group p a');

        // TOPページヘ遷移する
        $I->see('新着情報', '.ec-news__title');
    }

    public function mypage_お届け先編集表示(\AcceptanceTester $I)
    {
        $I->wantTo('EF0506-UC01-T01 Mypage お届け先編集表示');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
        $I->loginAsMember($customer->getEmail(), 'password');

        // TOPページ>マイページ>お届け先編集
        $I->amOnPage('/mypage');
        $I->click('#main_middle .local_nav ul li:nth-child(4) a');

        $I->see('お届け先編集', '#main_middle .page-heading');
    }

    public function mypage_お届け先編集作成(\AcceptanceTester $I)
    {
        $I->wantTo('EF0506-UC01-T02 Mypage お届け先編集作成');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
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
            'customer_address[address][pref]' => ['value' => '27'],
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

    public function mypage_お届け先編集変更(\AcceptanceTester $I)
    {
        $I->wantTo('EF0506-UC02-T01 Mypage お届け先編集変更');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
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
            'customer_address[address][pref]' => ['value' => '27'],
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

    public function mypage_お届け先編集削除(\AcceptanceTester $I)
    {
        $I->wantTo('EF0503-UC01-T01 Mypage お届け先編集削除');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
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
            'customer_address[address][pref]' => ['value' => '27'],
            'customer_address[address][addr01]' => '大阪市西区',
            'customer_address[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'customer_address[tel][tel01]' => '111',
            'customer_address[tel][tel02]' => '111',
            'customer_address[tel][tel03]' => '111',
        ]);
        $I->see('大阪市西区', '#main_middle #deliveradd_select .address_table .addr_box');

        // ×マークをクリック
        $I->click('#main_middle #deliveradd_select .address_table:nth-child(2) .addr_box .icon_edit a');
        $I->acceptPopup();

        // 確認
        $I->see('大阪市西区', '#main_middle #deliveradd_select .address_table .addr_box');
        $I->dontSee($customer->getAddr01(), '#main_middle #deliveradd_select .address_table .addr_box');
    }

    public function mypage_退会手続き未実施(\AcceptanceTester $I)
    {
        $I->wantTo('EF0507-UC03-T01 Mypage 退会手続き 未実施');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
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

    public function mypage_退会手続き(\AcceptanceTester $I)
    {
        $I->wantTo('EF0507-UC03-T02 Mypage 退会手続き');
        $createCustomer = Fixtures::get('createCustomer');
        $customer = $createCustomer();
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
        $I->see('新着情報', '.ec-news__title');
    }
}
