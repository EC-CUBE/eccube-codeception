<?php

use Codeception\Util\Fixtures;

/**
 * @group front
 * @group order
 * @group ef3
 */
class EF03OrderMultiShippingCest
{
    private $customer;

    private $baseInfo;

    /**
     * @param AcceptanceTester $I
     */
    public function _before(\AcceptanceTester $I)
    {
        $I->logoutAsMember();
        $createCustomer = Fixtures::get('createCustomer');
        $this->customer = $createCustomer();
        $I->loginAsMember($this->customer->getEmail(), 'password');

        $this->baseInfo = Fixtures::get('baseinfo');

        // admin
        $I->loginAsAdmin();
        $shopPage = \Page\Admin\ShopSettingPage::go($I);
        $shopPage->changeMultiShipping('有効');
        $shopPage->登録();

        $app = Fixtures::get('app');

        $pc = $app['orm.em']->getRepository('Eccube\Entity\ProductClass')->findOneBy(array('Product' => 2));
        $type = $app['eccube.repository.master.product_type']->find(2);
        $pc->setProductType($type);

        $app['orm.em']->persist($pc);
        $app['orm.em']->flush($pc);
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    /**
     * Test two different types of products in an order with multiple shipping.
     *
     * @param AcceptanceTester $i
     */
    public function order_MultiShipping_TwoType_OneAddress(\AcceptanceTester $i)
    {
        $i->wantTo('EF0305-UC05-T04 Multi shipping with other type');

        // 商品詳細パーコレータ カートへ
        $i->amOnPage('products/detail/2');
        $i->buyThis(1);

        $i->amOnPage('/products/detail/1');

        // 「カートに入れる」ボタンを押下する
        $i->selectOption(['id' => 'classcategory_id1'], 'プラチナ');
        $i->selectOption(['id' => 'classcategory_id2'], '150cm');
        $i->buyThis(3);

        // go to cart page
        $i->click('#main_middle .total_box .btn_group p a');

        $test = $i->grabTextFrom('#main_middle #confirm_flow_box');

        codecept_debug($test);

        $i->assertEquals("配送方法が異なる商品が含まれているため、お届け先は複数となります。", $test);

        // 確認
        $i->see('配送方法が異なる商品が含まれているため、お届け先は複数となります。', '#main_middle #confirm_flow_box #confirm_flow_box__message--1 p');
        $i->see('ご注文内容のご確認', '#main_middle .page-heading');
        $i->see('お客様情報', '#main_middle #shopping-form #confirm_main');
        $i->see('配送情報', '#main_middle #shopping-form #confirm_main');
        $i->see('お届け先', '#main_middle #shopping-form #confirm_main');
        $i->see('お支払方法', '#main_middle #shopping-form #confirm_main');
        $i->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
        $i->see('小計', '#main_middle #shopping-form #confirm_side');
        $i->see('手数料', '#main_middle #shopping-form #confirm_side');
        $i->see('送料', '#main_middle #shopping-form #confirm_side');
        $i->see('合計', '#main_middle #shopping-form #confirm_side');

        $i->resetEmails();

        // Check shipping
        // Two shipping
        $i->see('お届け先(1)', '#main_middle #shipping_confirm_box--0 h3');
        $i->see('お届け先(2)', '#main_middle #shipping_confirm_box--1 h3');

        // Go to multi shipping page
        $i->click('#main_middle #shopping_confirm #confirm_main a#shopping_confirm_box__button_edit_multiple');

        // Go to shopping confirm page
        $i->click('#main_middle #multiple_list__confirm_button #button__confirm');

        // Two shipping
        $i->see('お届け先(1)', '#main_middle #shipping_confirm_box--0 h3');
        $i->see('お届け先(2)', '#main_middle #shipping_confirm_box--1 h3');

        // 注文
        $i->click('#main_middle #shopping-form #confirm_side #order-button');
        $i->wait(1);

        // 確認
        $i->see('ご注文完了', '#main_middle h1.page-heading');
        // メール確認
        $i->seeEmailCount(2);
        foreach (array($this->customer->getEmail(), $this->baseInfo->getEmail01()) as $email) {
            $i->seeInLastEmailSubjectTo($email, 'ご注文ありがとうございます');
            $i->seeInLastEmailTo($email, $this->customer->getName01().' '.$this->customer->getName02().' 様');
            $i->seeInLastEmailTo($email, 'お名前　：'.$this->customer->getName01().' '.$this->customer->getName02().' 様');
            $i->seeInLastEmailTo($email, 'フリガナ：'.$this->customer->getKana01().' '.$this->customer->getKana02().' 様');
            $i->seeInLastEmailTo($email, '郵便番号：〒'.$this->customer->getZip01().'-'.$this->customer->getZip02());
            $i->seeInLastEmailTo($email, '住所　　：'.$this->customer->getPref()->getName().$this->customer->getAddr01().$this->customer->getAddr02());
            $i->seeInLastEmailTo($email, '電話番号：'.$this->customer->getTel01().'-'.$this->customer->getTel02().'-'.$this->customer->getTel03());
            $i->seeInLastEmailTo($email, 'メールアドレス：'.$this->customer->getEmail());
        }
        // topへ
        $i->click('#main_middle #deliveradd_input .btn_group p a');
        $i->see('新着情報', '#contents_bottom #news_area h2');
    }
    /**
     * Test two different types of products in an order with multiple shipping.
     *
     * @param AcceptanceTester $i
     * @group vaddy
     */
    public function order_MultiShipping_TwoType_TwoAddress(\AcceptanceTester $i)
    {
        $i->wantTo('EF0305-UC05-T05 Multi shipping add');

        // 商品詳細パーコレータ カートへ
        $i->amOnPage('products/detail/2');
        $i->buyThis(1);

        $i->amOnPage('/products/detail/1');
        // 「カートに入れる」ボタンを押下する
        $i->selectOption(['id' => 'classcategory_id1'], 'プラチナ');
        $i->selectOption(['id' => 'classcategory_id2'], '150cm');
        $i->buyThis(3);

        // go to cart page
        $i->click('#main_middle .total_box .btn_group p a');
        $i->wait(1);

        // 確認
        $i->see('配送方法が異なる商品が含まれているため、お届け先は複数となります', '#main_middle #confirm_flow_box #confirm_flow_box__message--1 p');
        $i->see('ご注文内容のご確認', '#main_middle .page-heading');
        $i->see('お客様情報', '#main_middle #shopping-form #confirm_main');
        $i->see('配送情報', '#main_middle #shopping-form #confirm_main');
        $i->see('お届け先', '#main_middle #shopping-form #confirm_main');
        $i->see('お支払方法', '#main_middle #shopping-form #confirm_main');
        $i->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
        $i->see('小計', '#main_middle #shopping-form #confirm_side');
        $i->see('手数料', '#main_middle #shopping-form #confirm_side');
        $i->see('送料', '#main_middle #shopping-form #confirm_side');
        $i->see('合計', '#main_middle #shopping-form #confirm_side');

        $i->resetEmails();
        // two shipping
        $i->see('お届け先(1)', '#shipping_confirm_box--0 h3');
        $i->see('お届け先(2)', '#shipping_confirm_box--1 h3');

        // Go to shipping change page
        $i->click('#main_middle #shipping_confirm_box--0 #shopping_confirm_box__edit_button--0 a');
        $i->see('お届け先の指定', '#main_middle .page-heading');
        $i->see('新規お届け先を追加する', '#main_middle #deliver_wrap #list_box__add_button a');

        // Go to add new shipping address
        $i->click('#main_middle #deliver_wrap #list_box__add_button a');
        $i->see('お届け先の追加', '#main_middle .page-heading');
        // new shipping address
        $i->submitForm('#main_middle #detail_box form', [
            'shopping_shipping[name][name01]' => '姓02',
            'shopping_shipping[name][name02]' => '名02',
            'shopping_shipping[kana][kana01]' => 'フ',
            'shopping_shipping[kana][kana02]' => 'フ',
            'shopping_shipping[company_name]' => 'company name',
            'shopping_shipping[zip][zip01]' => '530',
            'shopping_shipping[zip][zip02]' => '0001',
            'shopping_shipping[address][pref]' => 27,
            'shopping_shipping[address][addr01]' => '大阪市北区',
            'shopping_shipping[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
            'shopping_shipping[tel][tel01]' => '111',
            'shopping_shipping[tel][tel02]' => '112',
            'shopping_shipping[tel][tel03]' => '1133',
            'shopping_shipping[tel][fax01]' => '111',
            'shopping_shipping[tel][fax02]' => '112',
            'shopping_shipping[tel][fax03]' => '1133',
        ]);
        $i->wait(2);

        // Auto go back confirm page
        $i->see('ご注文内容のご確認', '#main_middle .page-heading');

        // Go to multi shipping page
        $i->click('#main_middle #shopping_confirm #confirm_main a#shopping_confirm_box__button_edit_multiple');
        $i->wait(1);

        // Change new shipping address
        $i->selectOption(['name' => 'form[shipping_multiple][1][shipping][0][customer_address]'], array('value' => 2));

        // Go to shopping confirm page
        $i->click('#main_middle #multiple_wrap #button__confirm');

        // Two shipping
        $i->see('お届け先(1)', '#shipping_confirm_box--0 h3');
        $i->see('お届け先(2)', '#shipping_confirm_box--1 h3');

        // 注文
        $i->click('#main_middle #shopping-form #order-button');
        $i->wait(1);

        // 確認
        $i->see('ご注文完了', '#main_middle .page-heading');
        // メール確認
        $i->seeEmailCount(2);
        foreach (array($this->customer->getEmail(), $this->baseInfo->getEmail01()) as $email) {
            $i->seeInLastEmailSubjectTo($email, 'ご注文ありがとうございます');
            $i->seeInLastEmailTo($email, $this->customer->getName01().' '.$this->customer->getName02().' 様');
            $i->seeInLastEmailTo($email, 'お名前　：'.$this->customer->getName01().' '.$this->customer->getName02().' 様');
            $i->seeInLastEmailTo($email, 'フリガナ：'.$this->customer->getKana01().' '.$this->customer->getKana02().' 様');
            $i->seeInLastEmailTo($email, '郵便番号：〒'.$this->customer->getZip01().'-'.$this->customer->getZip02());
            $i->seeInLastEmailTo($email, '住所　　：'.$this->customer->getPref()->getName().$this->customer->getAddr01().$this->customer->getAddr02());
            $i->seeInLastEmailTo($email, '電話番号：'.$this->customer->getTel01().'-'.$this->customer->getTel02().'-'.$this->customer->getTel03());
            $i->seeInLastEmailTo($email, 'メールアドレス：'.$this->customer->getEmail());
        }
        // topへ
        $i->click('#main_middle #deliveradd_input .btn_group p a');
        $i->see('新着情報', '#contents_bottom #news_area h2');
    }
    /**
     * Test two different types of products in an order with multiple shipping.
     *
     * @param AcceptanceTester $i
     */
    public function order_MultiShipping_Delivery_BackToConfirm(\AcceptanceTester $i)
    {
        $i->wantTo('EF0305-UC05-T06 Multi shipping - select delivery - back to confirm');

        // Create delivery
        $deliveryPage = \Page\Admin\DeliveryEditPage::go($i);
        $deliveryName = 'Delivery test type A';
        $deliveryPage->入力_配送業者名($deliveryName);
        $deliveryPage->入力_名称($deliveryName);
        $deliveryPage->入力_支払方法選択(array(1, 2, 3, 4));
        $deliveryPage->入力_全国一律送料(500);
        $deliveryPage->selectDeliveryType(1);
        $arrDeliveryTime = array(
            0 => 'AM',
            1 => 'PM',
        );
        $deliveryPage->setDeliveryTime($arrDeliveryTime);
        $deliveryPage->登録();

        $i->amOnPage('/products/detail/1');

        // 「カートに入れる」ボタンを押下する
        $i->selectOption(['id' => 'classcategory_id1'], 'プラチナ');
        $i->selectOption(['id' => 'classcategory_id2'], '150cm');
        $i->buyThis(1);
        // 商品詳細パーコレータ カートへ
        $i->amOnPage('products/detail/2');
        $i->buyThis(1);
        $i->wait(1);

        // go to confirm page
        $i->click('#main_middle .total_box .btn_group p a');
        $i->wait(1);

        // 確認
        $i->see('ご注文内容のご確認', '#main_middle .page-heading');
        $i->see('お客様情報', '#main_middle #shopping-form #confirm_main');
        $i->see('配送情報', '#main_middle #shopping-form #confirm_main');
        $i->see('お届け先', '#main_middle #shopping-form #confirm_main');
        $i->see('お支払方法', '#main_middle #shopping-form #confirm_main');
        $i->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
        $i->see('小計', '#main_middle #shopping-form #confirm_side');
        $i->see('手数料', '#main_middle #shopping-form #confirm_side');
        $i->see('送料', '#main_middle #shopping-form #confirm_side');
        $i->see('合計', '#main_middle #shopping-form #confirm_side');

        $i->resetEmails();

        // two shipping
        $i->see('お届け先(1)', '#shipping_confirm_box--0 h3');
        $i->see('お届け先(2)', '#shipping_confirm_box--1 h3');

        // select delivery method
//        $option = $i->grabTextFrom('#main_middle #confirm_main #shopping_confirm_box__shipping_delivery--0 select#shopping_shippings_0_delivery option:nth-child(2)');
        $i->selectOption('form select[id=shopping_shippings_0_delivery]', $deliveryName);
        $i->wait(1);
        // check delivery method
        $i->seeOptionIsSelected('form select[id=shopping_shippings_0_delivery]', $deliveryName);

        // Select delivery time
        $i->selectOption('form select[id=shopping_shippings_0_deliveryTime]', 'AM');

        // Go to multi shipping page
        $i->click('#main_middle #shopping_confirm #confirm_main a#shopping_confirm_box__button_edit_multiple');
        $i->wait(1);

        // Go back shopping confirm page
        $i->click('#main_middle #multiple_wrap #multiple_list__back_button a');
        $i->wait(1);

        // Two shipping
        $i->see('お届け先(1)', '#shipping_confirm_box--0 h3');
        $i->see('お届け先(2)', '#shipping_confirm_box--1 h3');
        // Check old delivery
        $i->seeOptionIsSelected('form select[id=shopping_shippings_0_delivery]', $deliveryName);
        // Check old delivery time
        $time = $arrDeliveryTime[0];
        $i->seeOptionIsSelected('form select[id=shopping_shippings_0_deliveryTime]', $time);
        // 注文
        $i->click('#main_middle #shopping-form #order-button');
        $i->wait(1);
        // 確認
        $i->see('ご注文完了', '#main_middle .page-heading');
        // メール確認
        $i->seeEmailCount(2);
        foreach (array($this->customer->getEmail(), $this->baseInfo->getEmail01()) as $email) {
            $i->seeInLastEmailSubjectTo($email, 'ご注文ありがとうございます');
            $i->seeInLastEmailTo($email, $this->customer->getName01().' '.$this->customer->getName02().' 様');
            $i->seeInLastEmailTo($email, 'お名前　：'.$this->customer->getName01().' '.$this->customer->getName02().' 様');
            $i->seeInLastEmailTo($email, 'フリガナ：'.$this->customer->getKana01().' '.$this->customer->getKana02().' 様');
            $i->seeInLastEmailTo($email, '郵便番号：〒'.$this->customer->getZip01().'-'.$this->customer->getZip02());
            $i->seeInLastEmailTo($email, '住所　　：'.$this->customer->getPref()->getName().$this->customer->getAddr01().$this->customer->getAddr02());
            $i->seeInLastEmailTo($email, '電話番号：'.$this->customer->getTel01().'-'.$this->customer->getTel02().'-'.$this->customer->getTel03());
            $i->seeInLastEmailTo($email, 'メールアドレス：'.$this->customer->getEmail());
        }
        // topへ
        $i->click('#main_middle #deliveradd_input .btn_group p a');
        $i->see('新着情報', '#contents_bottom #news_area h2');
    }

    /**
     * Test two different types of products in an order with multiple shipping.
     *
     * @param AcceptanceTester $i
     */
//    public function order_MultiShipping_TwoType_ThreeShipping(\AcceptanceTester $i)
//    {
//        $i->wantTo('EF0305-UC05-T07 Multi shipping add new a shipping');
//        // Create delivery
//        $deliveryPage = \Page\Admin\DeliveryEditPage::go($i);
//        $deliveryName = 'Delivery test type A';
//        $deliveryPage->入力_配送業者名($deliveryName);
//        $deliveryPage->入力_名称($deliveryName);
//        $deliveryPage->入力_支払方法選択(array(1, 2, 3, 4));
//        $deliveryPage->入力_全国一律送料(500);
//        $deliveryPage->selectDeliveryType(1);
//        $arrDeliveryTime = array(
//            0 => 'AM',
//            1 => 'PM',
//        );
//        $deliveryPage->setDeliveryTime($arrDeliveryTime);
//        $deliveryPage->登録();
//
//        $i->amOnPage('/products/detail/1');
//
//        // 「カートに入れる」ボタンを押下する
//        $i->selectOption(['id' => "classcategory_id1"], 'プラチナ');
//        $i->selectOption(['id' => "classcategory_id2"], '150cm');
//        $i->buyThis(3);
//
//        // 商品詳細パーコレータ カートへ
//        $i->amOnPage('products/detail/2');
//        $i->buyThis(1);
//        $i->wait(1);
//
//        // go to confirm page
//        $i->click('#main_middle .total_box .btn_group p a');
//        $i->wait(1);
//        // 確認
//        $i->see('ご注文内容のご確認', '#main_middle .page-heading');
//        $i->see('お客様情報', '#main_middle #shopping-form #confirm_main');
//        $i->see('配送情報', '#main_middle #shopping-form #confirm_main');
//        $i->see('お届け先', '#main_middle #shopping-form #confirm_main');
//        $i->see('お支払方法', '#main_middle #shopping-form #confirm_main');
//        $i->see('お問い合わせ欄', '#main_middle #shopping-form #confirm_main');
//        $i->see('小計', '#main_middle #shopping-form #confirm_side');
//        $i->see('手数料', '#main_middle #shopping-form #confirm_side');
//        $i->see('送料', '#main_middle #shopping-form #confirm_side');
//        $i->see('合計', '#main_middle #shopping-form #confirm_side');
//
//        $i->resetEmails();
//
//        // two shipping
//        $i->see('お届け先(1)', '#shipping_confirm_box--0 h3');
//        $i->see('お届け先(2)', '#shipping_confirm_box--1 h3');
//
//        // Go to shipping change page
//        $i->click('#main_middle #shipping_confirm_box--0 #shopping_confirm_box__edit_button--0 a');
//        $i->see('お届け先の指定', '#main_middle .page-heading');
//        $i->see('新規お届け先を追加する', '#main_middle #deliver_wrap #list_box__add_button a');
//
//        // Go to add new shipping address
//        $i->click('#main_middle #deliver_wrap #list_box__add_button a');
//        $i->see('お届け先の追加', '#main_middle .page-heading');
//        // new shipping address
//        $i->submitForm('#main_middle form', [
//            'shopping_shipping[name][name01]' => '姓02',
//            'shopping_shipping[name][name02]' => '名02',
//            'shopping_shipping[kana][kana01]' => 'セイ',
//            'shopping_shipping[kana][kana02]' => 'メイ',
//            'shopping_shipping[company_name]' => 'company name',
//            'shopping_shipping[zip][zip01]' => '530',
//            'shopping_shipping[zip][zip02]' => '0001',
//            'shopping_shipping[address][pref]' => 27,
//            'shopping_shipping[address][addr01]' => '大阪市北区',
//            'shopping_shipping[address][addr02]' => '梅田2-4-9 ブリーゼタワー13F',
//            'shopping_shipping[tel][tel01]' => '111',
//            'shopping_shipping[tel][tel02]' => '112',
//            'shopping_shipping[tel][tel03]' => '113',
//            'shopping_shipping[tel][fax01]' => '111',
//            'shopping_shipping[tel][fax02]' => '112',
//            'shopping_shipping[tel][fax03]' => '113',
//        ]);
//        $i->wait(1);
//
//        // select delivery method
//        $option = $i->grabTextFrom('#main_middle #confirm_main #shopping_confirm_box__shipping_delivery--0 select#shopping_shippings_0_delivery option:nth-child(2)');
//        $i->selectOption('form select[id=shopping_shippings_0_delivery]', $option);
//        $i->wait(1);
//        // check delivery method
//        $i->seeOptionIsSelected('form select[id=shopping_shippings_0_delivery]', $deliveryName);
//
//        // Select delivery time
//        $i->selectOption('form select[id=shopping_shippings_0_deliveryTime]', 'AM');
//
//        // Go to multi shipping page
//        $i->click('#main_middle #shopping_confirm #confirm_main a#shopping_confirm_box__button_edit_multiple');
//        $i->wait(1);
//        $i->see('お届け先の複数指定', '#main_middle .page-heading');
//        $i->see('選択したお届け先に送る', '#main_middle #multiple_list__footer #multiple_list__confirm_button #button__confirm');
//
//        /**
//         * Todo: Current, we has error in the source
//         * @link: https://github.com/EC-CUBE/ec-cube/pull/2067
//         */
//        $i->see('お届け先追加', '#main_middle #multiple_wrap #multiple_list__add_button button#button__add');
//        $button = $i->grabMultiple('#main_middle #multiple_wrap #multiple_list__add_button button#button__add')[0];
//        $i->click($button);
//        $i->fillField(['name' => 'form[shipping_multiple][0][shipping][0][quantity]'], 1);
//
//        // Incorrect in here
//        $i->selectOption(['name' => 'form[shipping_multiple][0][shipping][1][customer_address]'], array('value' => 2));
//
//        $i->fillField(['name' => 'form[shipping_multiple][0][shipping][1][quantity]'], 2);
//
//        // Go to shopping confirm page
//        $i->click('#main_middle #multiple_wrap #button__confirm');
//        $i->wait(1);
//
//        // shopping
//        $i->see('ご注文内容のご確認', '#main_middle .page-heading');
//
//        // Two shipping
//        $i->see('お届け先(1)', '#shipping_confirm_box--0 h3');
//        $i->see('お届け先(2)', '#shipping_confirm_box--1 h3');
//
//        // 注文
//        $i->click('#main_middle #shopping-form #order-button');
//        $i->wait(1);
//
//        // 確認
//        $i->see('ご注文完了', '#main_middle .page-heading');
//
//        // メール確認
//        $i->seeEmailCount(2);
//        foreach (array($this->customer->getEmail(), $this->baseInfo->getEmail01()) as $email) {
//            $i->seeInLastEmailSubjectTo($email, 'ご注文ありがとうございます');
//            $i->seeInLastEmailTo($email, $this->customer->getName01().' '.$this->customer->getName02().' 様');
//            $i->seeInLastEmailTo($email, 'お名前　：'.$this->customer->getName01().' '.$this->customer->getName02().' 様');
//            $i->seeInLastEmailTo($email, 'フリガナ：'.$this->customer->getKana01().' '.$this->customer->getKana02().' 様');
//            $i->seeInLastEmailTo($email, '郵便番号：〒'.$this->customer->getZip01().'-'.$this->customer->getZip02());
//            $i->seeInLastEmailTo($email, '住所　　：'.$this->customer->getPref()->getName().$this->customer->getAddr01().$this->customer->getAddr02());
//            $i->seeInLastEmailTo($email, '電話番号：'.$this->customer->getTel01().'-'.$this->customer->getTel02().'-'.$this->customer->getTel03());
//            $i->seeInLastEmailTo($email, 'メールアドレス：'.$this->customer->getEmail());
//        }
//
//        // topへ
//        $i->click('#main_middle #deliveradd_input .btn_group p a');
//        $i->see('新着情報', '#contents_bottom #news_area h2');
//    }
}
