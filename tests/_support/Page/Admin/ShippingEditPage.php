<?php


namespace Page\Admin;


class ShippingEditPage extends AbstractAdminPageStyleGuide
{

    public static $姓_エラーメッセージ = '#shippingerInfo > div > div:nth-child(2) > div.col > span > ul > p';

    public static $登録完了メッセージ = '#page_admin_shipping_edit > div > div.c-contentsArea > div.alert.alert-success.alert-dismissible.fade.show.m-3 > span';

    /**
     * ShippingRegisterPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        $page->goPage('/shipping/new', '出荷登録出荷管理');
        return $page;
    }

    public static function at($I)
    {
        $page = new self($I);
        $page->atPage('出荷登録出荷管理');
        return $page;
    }

    public function 入力_出荷ステータス($value)
    {
        $this->tester->selectOption(['id' => 'shipping_ShippingStatus'], $value);
        return $this;
    }

    public function 入力_姓($value)
    {
        $this->tester->fillField(['id' => 'shipping_name_name01'], $value);
        return $this;
    }

    public function 入力_名($value)
    {
        $this->tester->fillField(['id' => 'shipping_name_name02'], $value);
        return $this;
    }

    public function 入力_セイ($value)
    {
        $this->tester->fillField(['id' => 'shipping_kana_kana01'], $value);
        return $this;
    }

    public function 入力_メイ($value)
    {
        $this->tester->fillField(['id' => 'shipping_kana_kana02'], $value);
        return $this;
    }

    public function 入力_郵便番号1($value)
    {
        $this->tester->fillField(['id' => 'shipping_zip_zip01'], $value);
        return $this;
    }

    public function 入力_郵便番号2($value)
    {
        $this->tester->fillField(['id' => 'shipping_zip_zip02'], $value);
        return $this;
    }

    public function 入力_都道府県($value)
    {
        $this->tester->selectOption(['id' => 'shipping_address_pref'], $value);
        return $this;
    }

    public function 入力_市区町村名($value)
    {
        $this->tester->fillField(['id' => 'shipping_address_addr01'], $value);
        return $this;
    }

    public function 入力_番地_ビル名($value)
    {
        $this->tester->fillField(['id' => 'shipping_address_addr02'], $value);
        return $this;
    }

    public function 入力_電話番号1($value)
    {
        $this->tester->fillField(['id' => 'shipping_tel_tel01'], $value);
        return $this;
    }

    public function 入力_電話番号2($value)
    {
        $this->tester->fillField(['id' => 'shipping_tel_tel02'], $value);
        return $this;
    }

    public function 入力_電話番号3($value)
    {
        $this->tester->fillField(['id' => 'shipping_tel_tel03'], $value);
        return $this;
    }

    public function 入力_Eメール($value)
    {
        $this->tester->fillField(['id' => 'shipping_email'], $value);
        return $this;
    }

    public function 入力_支払方法($value)
    {
        $this->tester->selectOption(['id' => 'shipping_Payment'], $value);
        return $this;
    }

    public function 商品検索($value = '')
    {
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

    public function 出荷情報登録()
    {
        $this->tester->click('#form1 > div.c-conversionArea > div > div > div:nth-child(2) > div > div > button');
        return $this;
    }
}
