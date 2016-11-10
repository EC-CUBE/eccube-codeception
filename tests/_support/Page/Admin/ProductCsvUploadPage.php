<?php

namespace Page\Admin;

class ProductCsvUploadPage extends AbstractAdminPage
{

    protected $tester;

    /**
     * ProductCsvUploadPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new ProductCsvUploadPage($I);
        return $page->goPage('/product/product_csv_upload', '商品管理商品登録CSVアップロード');
    }

    public function 雛形ダウンロード()
    {
        $this->tester->click('#download-button');
        return $this;
    }
}