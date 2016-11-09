<?php

/**
 * 商品管理/商品登録
 */
namespace Page\Admin;

class ProductEditPage extends AbstractAdminPage
{

    protected $tester;

    public static $登録結果メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

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
        $page->tester->see('商品管理商品登録', '#main .page-header');
        return $page;
    }

    public function 入力_商品名($value)
    {
        $this->tester->fillField(['id' => 'admin_product_name'], $value);
        return $this;
    }

    public function 入力_販売価格($value)
    {
        $this->tester->fillField(['id' => 'admin_product_class_price02'], $value);
        return $this;
    }

    public function 入力_公開()
    {
        $this->tester->selectOption(['id' => 'admin_product_Status_1'], '公開');
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#form1 #aside_column button:nth-child(1)');
        return $this;
    }
}