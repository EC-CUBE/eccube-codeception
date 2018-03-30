<?php

/**
 * 商品管理/商品登録
 */
namespace Page\Admin;

class ProductEditPage extends AbstractAdminPageStyleGuide
{
    public static $登録結果メッセージ = '#page_admin_product_product_edit > div.c-container > div.c-contentsArea > div.alert.alert-success.alert-dismissible.fade.show.m-3';
    public static $販売種別 = ['id' => 'admin_product_class_sale_type'];
    public static $通常価格 = ['id' => 'admin_product_class_price01'];
    public static $販売価格 = ['id' => 'admin_product_class_price02'];
    public static $在庫数 = ['id' => 'admin_product_class_stock'];
    public static $商品コード = ['id' => 'admin_product_class_code'];
    public static $販売制限数 = ['id' => 'admin_product_class_sale_limit'];
    public static $お届可能日 = ['id' => 'admin_product_class_delivery_duration'];

    /**
     * ProductRegisterPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new ProductEditPage($I);
        return $page->goPage('/product/product/new', '商品管理商品登録');
    }

    public static function at($I)
    {
        $page = new ProductEditPage($I);
        $page->tester->see('商品管理商品登録', '.c-pageTitle .c-pageTitle__titles');
        return $page;
    }

    public function 入力_商品名($value)
    {
        $this->tester->fillField(['id' => 'admin_product_name'], $value);
        return $this;
    }

    public function 入力_販売価格($value)
    {
        $this->tester->fillField(self::$販売価格, $value);
        return $this;
    }

    public function 入力_公開()
    {
        $this->tester->selectOption('#admin_product_Status', '公開');
        return $this;
    }

    public function clickShownTags(){
        $this->tester->click(['css' => 'div[href="#allTags"] > a']);
        return $this;
    }

    public function clickSelectTags(){
        $this->tester->click(['css' => '#allTags > div:nth-child(2) button']);
        $this->tester->click(['css' => '#allTags > div:nth-child(3) button']);
        $this->tester->click(['css' => '#allTags > div:nth-child(4) button']);
        return $this;
    }

    public function 規格管理()
    {
        $this->tester->click(['css' => '#standardConfig > div > div.d-block.text-center.text-center > button']);
        $this->tester->waitForElement(['css' => '#confirmModal > div > div > div.modal-footer > a']);
        $this->tester->wait(1);
        $this->tester->click(['css' => '#confirmModal > div > div > div.modal-footer > a']);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#form1 > div.c-conversionArea button.btn-ec-conversion');
        return $this;
    }

}