<?php

/**
 * 商品管理/商品登録
 */
namespace Page\Admin;

class ProductEditPage extends AbstractAdminPage
{
    public static $登録結果メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';
    public static $商品種別 = ['id' => 'admin_product_class_product_type'];
    public static $通常価格 = ['id' => 'admin_product_class_price01'];
    public static $販売価格 = ['id' => 'admin_product_class_price02'];
    public static $在庫数 = ['id' => 'admin_product_class_stock'];
    public static $商品コード = ['id' => 'admin_product_class_code'];
    public static $販売制限数 = ['id' => 'admin_product_class_sale_limit'];
    public static $お届可能日 = ['id' => 'admin_product_class_delivery_date'];
    public static $productType = ['id' => 'admin_product_class_product_type'];

    /**
     * ProductRegisterPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I, $id = null)
    {
        $page = new ProductEditPage($I);
        if (is_numeric($id)) {
            return $page->goPage("/product/product/{$id}/edit", '商品管理商品登録');
        }

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
        $this->tester->fillField(self::$販売価格, $value);
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

    /**
     * Change product type
     * type = text/value
     *
     * @param string $value
     * @param string $type
     * @return $this
     */
    public function changeProductType($value, $type = 'value')
    {
        $this->tester->selectOption(self::$productType, array($type => $value));

        return $this;
    }
}
