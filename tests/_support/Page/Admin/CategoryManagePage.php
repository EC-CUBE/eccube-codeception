<?php

namespace Page\Admin;

class CategoryManagePage extends AbstractAdminPage
{

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';
    public static $パンくず_1階層 = '#main > div > div > div.col-md-9 > div > div.box-header > div > a:nth-child(3)';
    public static $パンくず_2階層 = '#main > div > div > div.col-md-9 > div > div.box-header > div > a:nth-child(5)';
    public static $パンくず_3階層 = '#main > div > div > div.col-md-9 > div > div.box-header > div > a:nth-child(7)';
    public static $パンくず_4階層 = '#main > div > div > div.col-md-9 > div > div.box-header > div > a:nth-child(9)';

    protected $tester;

    /**
     * CategoryPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/product/category', '商品管理カテゴリ編集');
    }

    public function 入力_カテゴリ名($value)
    {
        $this->tester->fillField(['id' => 'admin_category_name'], $value);
        return $this;
    }

    public function カテゴリ作成()
    {
        $this->tester->click('#form1 > div > div > button');
        return $this;
    }

    public function 一覧_選択($rowNum)
    {
        $this->tester->click("#main > div > div > div.col-md-9 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(${rowNum}) > div.item_pattern.td > a");
        return $this;
    }

    public function 一覧_編集($rowNum)
    {
        $this->一覧_メニュー($rowNum);
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(1) a");
        return $this;
    }

    public function 一覧_削除($rowNum)
    {
        $this->一覧_メニュー($rowNum);
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(2) a");
        return $this;
    }

    private function 一覧_メニュー($rowNum)
    {
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown a");
        return $this;
    }

    public function CSVダウンロードメニュー()
    {
        $this->tester->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > a');
        return $this;
    }

    public function CSVダウンロード実行()
    {
        $this->CSVダウンロードメニュー();
        $this->tester->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > ul > li:nth-child(1) a');
        return $this;
    }

    public function CSV出力項目設定()
    {
        $this->CSVダウンロードメニュー();
        $this->tester->click('#main > div > div > div.col-md-9 > div > div:nth-child(2) > div > div.dl_dropdown.col-md-3 > div > ul > li:nth-child(2) > a');
    }

    public function 一覧_上に($rowNum)
    {
        $dragTo = $rowNum - 1;
        $this->tester->dragAndDrop(
            "#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.icon_sortable",
            "#main .container-fluid .box .box-body .item_box:nth-child($dragTo) div.icon_sortable");
        return $this;
    }

    public function 一覧_下に($rowNum)
    {
        $dragTo = $rowNum + 1;
        $this->tester->dragAndDrop(
            "#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.item_pattern > a",
            "#main .container-fluid .box .box-body .item_box:nth-child($dragTo) div.item_pattern > a");
        return $this;
    }

    public function 一覧_名称($rowNum)
    {
        return "#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.item_pattern > a";
    }
}