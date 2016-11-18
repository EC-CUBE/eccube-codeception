<?php


namespace Page\Admin;


class OrderEditPage extends AbstractAdminPage
{

    public static $姓_エラーメッセージ = '#aside_wrap > form > div > div:nth-child(2) > div.box-body.accpanel > div > div:nth-child(2) > div > span > ul > p';

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    /**
     * OrderRegisterPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        $page->goPage('/order/new', '受注管理受注登録・編集');
        return $page;
    }

    public static function at($I)
    {
        $page = new self($I);
        $page->atPage('受注管理受注登録・編集');
        return $page;
    }

    public function 入力_受注ステータス($value)
    {
        $this->tester->selectOption(['id' => 'order_OrderStatus'], $value);
        return $this;
    }

    public function 入力_姓($value)
    {
        $this->tester->fillField(['id' => 'order_name_name01'], $value);
        return $this;
    }

    public function 入力_名($value)
    {
        $this->tester->fillField(['id' => 'order_name_name02'], $value);
        return $this;
    }

    public function 入力_セイ($value)
    {
        $this->tester->fillField(['id' => 'order_kana_kana01'], $value);
        return $this;
    }

    public function 入力_メイ($value)
    {
        $this->tester->fillField(['id' => 'order_kana_kana02'], $value);
        return $this;
    }

    public function 入力_郵便番号1($value)
    {
        $this->tester->fillField(['id' => 'order_zip_zip01'], $value);
        return $this;
    }

    public function 入力_郵便番号2($value)
    {
        $this->tester->fillField(['id' => 'order_zip_zip02'], $value);
        return $this;
    }

    public function 入力_都道府県($value)
    {
        $this->tester->selectOption(['id' => 'order_address_pref'], $value);
        return $this;
    }

    public function 入力_市区町村名($value)
    {
        $this->tester->fillField(['id' => 'order_address_addr01'], $value);
        return $this;
    }

    public function 入力_番地_ビル名($value)
    {
        $this->tester->fillField(['id' => 'order_address_addr02'], $value);
        return $this;
    }

    public function 入力_電話番号1($value)
    {
        $this->tester->fillField(['id' => 'order_tel_tel01'], $value);
        return $this;
    }

    public function 入力_電話番号2($value)
    {
        $this->tester->fillField(['id' => 'order_tel_tel02'], $value);
        return $this;
    }

    public function 入力_電話番号3($value)
    {
        $this->tester->fillField(['id' => 'order_tel_tel03'], $value);
        return $this;
    }

    public function 入力_Eメール($value)
    {
        $this->tester->fillField(['id' => 'order_email'], $value);
        return $this;
    }

    public function 入力_支払方法($value)
    {
        $this->tester->selectOption(['id' => 'order_Payment'], $value);
        return $this;
    }

    public function 注文者情報をコピー()
    {
        $this->tester->executeJS('window.scrollTo(0, 1700);');
        $this->tester->click('#main #detail_wrap a.copyCustomerToShippingButton');
        return $this;
    }

    public function 入力_配送業者($value)
    {
        $this->tester->selectOption(['id' => 'order_Shippings_0_Delivery'], $value);
        return $this;
    }

    public function 商品検索($value = '')
    {
        $this->tester->executeJS('window.scrollTo(0, 900);');
        $this->tester->click('#aside_wrap > form > div > div:nth-child(3) > div.box-body.accpanel > div > div.btn_area > ul > li:nth-child(1) > a');
        $this->tester->waitForElement(['id' => 'search_product_modal_box']);
        $this->tester->fillField(['id' => 'admin_search_product_id'], $value);
        $this->tester->click('#searchProductModalButton');
        return $this;
    }

    public function 商品検索結果_選択($rowNum)
    {
        $rowNum = $rowNum * 2;
        $this->tester->click("#searchProductModalList > div > table > tbody > tr:nth-child(${rowNum}) > td.text-right > button");
        return $this;
    }

    public function 受注情報登録()
    {
        $this->tester->click('#aside_wrap > form > div > div.row.btn_area > p > button');
        return $this;
    }
}
