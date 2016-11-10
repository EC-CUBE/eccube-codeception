<?php


namespace Page\Admin;

class CategoryCsvUploadPage extends AbstractAdminPage
{

    /**
     * CategoryCsvUploadPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/product/category_csv_upload', 'カテゴリ登録CSVアップロード');
    }

    public function 雛形ダウンロード()
    {
        $this->tester->click('#download-button');
        return $this;
    }
}