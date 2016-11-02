<?php

use Codeception\Util\Fixtures;

/**
 * @group front
 * @group order
 * @group ef3
 */
class EF03OrderCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function order_カート買い物を続ける(\AcceptanceTester $I)
    {
        $I->wantTo('EF0301-UC01-T01 カート 買い物を続ける');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);
        $I->loginAsMember($customer->getEmail(), 'password');

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(1);

        // 買い物を続ける
        $I->click('#main_middle #form_cart .total_box .btn_group p:nth-child(2) a');

        // トップページ
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }

    public function _order_カート削除(\AcceptanceTester $I)
    {
        $I->wantTo('EF0301-UC01-T02 カート 削除');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);
        $I->loginAsMember($customer->getEmail(), 'password');

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(1);

        // 削除
        $I->makeEmptyCart();
        /* ToDo: popup */
        // 空にした後の状況を確認すること(popupの処理はmakeEmptyCart()内で)
        // 「現在カート内に商品はございません。」など
    }

    public function _order_カート数量増やす(\AcceptanceTester $I)
    {
        $I->wantTo('EF0301-UC01-T03 カート 数量増やす');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);
        $I->loginAsMember($customer->getEmail(), 'password');

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(1);

        // 増加
        $I->click('#main_middle .cart_item .item_box:nth-child(1) .item_quantity ul li:nth-child(2) a');

        // 確認
        $I->see('2', '#main_middle .cart_item .item_box:nth-child(1) .item_quantity');

    }

    public function _order_カート数量減らす(\AcceptanceTester $I)
    {
        $I->wantTo('EF0301-UC01-T04 カート 数量減らす');
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);
        $I->loginAsMember($customer->getEmail(), 'password');

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(2);

        // 減らす
        $I->click('#main_middle .cart_item .item_box:nth-child(1) .item_quantity ul li:nth-child(1) a');

        // 確認
        $I->see('1', '#main_middle .cart_item .item_box:nth-child(1) .item_quantity');
    }

    public function _order_ログインユーザ購入(\AcceptanceTester $I)
    {
        $I->wantTo('EF0302-UC01-T01 ログインユーザ購入');
        $I->logoutAsMember();
        $app = Fixtures::get('app');
        $customer = $app['orm.em']->getRepository('Eccube\Entity\Customer')->find(3);

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(1);

        // レジへ
        $I->click('#main_middle .total_box .btn_group p a');
        $I->see('ログイン', '#main_middle .page-heading');

        // ログイン
        $I->submitForm('#main_middle form', [
            'login_email' => $customer->getEmail(),
            'login_pass' => 'password'
        ]);

        // 確認
        $I->see('ご注文内容のご確認', '#main_middle .page-heading');
        $I->see('お客様情報', '#main_middle #shopping-form #confirm_main');
        $I->see('配送情報', '#main_middle #shopping-form #confirm_main');
        $I->see('お届け先', '#main_middle #shopping-form #confirm_main');
        $I->see('お支払方法', '#main_middle #shopping-form #confirm_main');
        $I->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
        $I->see('小計', '#main_middle #shopping-form #confirm_side');
        $I->see('手数料', '#main_middle #shopping-form #confirm_side');
        $I->see('送料', '#main_middle #shopping-form #confirm_side');
        $I->see('合計', '#main_middle #shopping-form #confirm_side');

        // 注文
        $I->click('#main_middle #shopping-form #confirm_side .total_amount p:nth-child(2) button');

        // 確認
        $I->see('ご注文完了', '#main_middle .page-heading');

        // topへ
        $I->click('#main_middle #deliveradd_input .btn_group p a');
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }

    public function _order_ゲスト購入(\AcceptanceTester $I)
    {
        $I->wantTo('EF0302-UC02-T01 ゲスト購入');
        $I->logoutAsMember();

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(1);

        // レジへ
        $I->click('#main_middle .total_box .btn_group p a');
        $I->see('ログイン', '#main_middle .page-heading');

        // ゲスト購入
        $I->click('#main_middle #login_box div:nth-child(2) .btn_area a');
        $I->see('お客様情報の入力', '#main_middle .page-heading');

        $I->submitForm("#main_middle form",[
            'nonmember[name][name01]' => '姓03',
            'nonmember[name][name02]' => '名03',
            'nonmember[kana][kana01]' => 'セイ',
            'nonmember[kana][kana02]' => 'メイ',
            'nonmember[zip][zip01]' => '530',
            'nonmember[zip][zip02]' => '0001',
            'nonmember[address][pref]' => 27,
            'nonmember[address][addr01]' => '大阪市北区',
            'nonmember[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'nonmember[tel][tel01]' => '111',
            'nonmember[tel][tel02]' => '111',
            'nonmember[tel][tel03]' => '111',
            'nonmember[email][first]' => 'acctest03@ec-cube.net',
            'nonmember[email][second]' => 'acctest03@ec-cube.net',
        ]);

        // 確認
        $I->see('ご注文内容のご確認', '#main_middle .page-heading');
        $I->see('お客様情報', '#main_middle #shopping-form #confirm_main');
        $I->see('配送情報', '#main_middle #shopping-form #confirm_main');
        $I->see('お届け先', '#main_middle #shopping-form #confirm_main');
        $I->see('お支払方法', '#main_middle #shopping-form #confirm_main');
        $I->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
        $I->see('小計', '#main_middle #shopping-form #confirm_side');
        $I->see('手数料', '#main_middle #shopping-form #confirm_side');
        $I->see('送料', '#main_middle #shopping-form #confirm_side');
        $I->see('合計', '#main_middle #shopping-form #confirm_side');

        // 注文
        $I->click('#main_middle #shopping-form #confirm_side .total_amount p:nth-child(2) button');

        // 確認
        $I->see('ご注文完了', '#main_middle .page-heading');

        // topへ
        $I->click('#main_middle #deliveradd_input .btn_group p a');
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }

    public function _order_ゲスト購入情報変更(\AcceptanceTester $I)
    {
        $I->wantTo('EF0305-UC02-T01 ゲスト購入 情報変更'); // EF0305-UC04-T01も一緒にテスト
        $I->logoutAsMember();

        // 商品詳細パーコレータ カートへ
        $I->amOnPage('products/detail/2');
        $I->buyThis(1);

        // レジへ
        $I->click('#main_middle .total_box .btn_group p a');
        $I->see('ログイン', '#main_middle .page-heading');

        // ゲスト購入
        $I->click('#main_middle #login_box div:nth-child(2) .btn_area a');
        $I->see('お客様情報の入力', '#main_middle .page-heading');

        $I->submitForm("#main_middle form",[
            'nonmember[name][name01]' => '姓03',
            'nonmember[name][name02]' => '名03',
            'nonmember[kana][kana01]' => 'セイ',
            'nonmember[kana][kana02]' => 'メイ',
            'nonmember[zip][zip01]' => '530',
            'nonmember[zip][zip02]' => '0001',
            'nonmember[address][pref]' => 27,
            'nonmember[address][addr01]' => '大阪市北区',
            'nonmember[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'nonmember[tel][tel01]' => '111',
            'nonmember[tel][tel02]' => '111',
            'nonmember[tel][tel03]' => '111',
            'nonmember[email][first]' => 'acctest03@ec-cube.net',
            'nonmember[email][second]' => 'acctest03@ec-cube.net',
        ]);

        // 確認
        $I->see('ご注文内容のご確認', '#main_middle .page-heading');
        $I->see('お客様情報', '#main_middle #shopping-form #confirm_main');
        $I->see('配送情報', '#main_middle #shopping-form #confirm_main');
        $I->see('お届け先', '#main_middle #shopping-form #confirm_main');
        $I->see('お支払方法', '#main_middle #shopping-form #confirm_main');
        $I->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
        $I->see('小計', '#main_middle #shopping-form #confirm_side');
        $I->see('手数料', '#main_middle #shopping-form #confirm_side');
        $I->see('送料', '#main_middle #shopping-form #confirm_side');
        $I->see('合計', '#main_middle #shopping-form #confirm_side');

        // お客様情報変更
        $I->click('#main_middle #shopping-form #confirm_main #customer');
        $I->fillField(['id' => 'edit0'], '姓0301');
        $I->click('#main_middle #shopping-form #confirm_main #customer-ok button');
        $I->see('姓0301', '#main_middle #shopping-form #confirm_main .address');

        // 配送情報
        $I->click('#main_middle #shopping-form #confirm_main .btn-shipping-edit');
        $I->see('お届け先の追加', '#main_middle .page-heading');
        $I->fillField(['id' => 'shopping_shipping_name_name01'], '姓0302');
        $I->click('#main_middle form .btn_group p:nth-child(1) button');
        $I->see('姓0302', '#main_middle #shopping-form #confirm_main .address');

        // 注文
        $I->click('#main_middle #shopping-form #confirm_side .total_amount p:nth-child(2) button');

        // 確認
        $I->see('ご注文完了', '#main_middle .page-heading');

        // topへ
        $I->click('#main_middle #deliveradd_input .btn_group p a');
        $I->see('新着情報', '#contents_bottom #news_area h2');
    }
}
