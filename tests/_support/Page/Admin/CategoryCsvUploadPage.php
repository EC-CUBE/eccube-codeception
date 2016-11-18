<?php


namespace Page\Admin;

class CategoryCsvUploadPage extends AbstractAdminPage
{

    public static $完了メッセージ = '#main > div > div:nth-child(1) > div.alert-success';

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

    public function 入力_CSVファイル($fileName)
    {
        $this->tester->attachFile(['id' => 'admin_csv_import_import_file'], $fileName);
        return $this;
    }

    public function CSVアップロード()
    {
        $this->tester->click(['id' => 'upload-button']);
        return $this;
    }

    public function 雛形ダウンロード()
    {
        $this->tester->click('#download-button');
        return $this;
    }
}