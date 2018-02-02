<?php


namespace Page\Admin;


class DeliveryManagePage extends AbstractAdminPage
{

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        $page->goPage('/setting/shop/delivery', '基本情報設定配送方法設定');
        $page->tester->see('配送方法一覧', '#main .container-fluid .box-title');
        return $page;
    }

    public static function at($I)
    {
        $page = new self($I);
        $page->atPage('基本情報設定配送方法設定');
        $page->tester->see('配送方法一覧', '#main .container-fluid .box-title');
        return $page;
    }

    public function 一覧_編集($rowNum)
    {
        $this->tester->click("#main .container-fluid .sortable_list .tableish .item_box:nth-child(${rowNum}) .icon_edit .dropdown a");
        $this->tester->click("#main .container-fluid .sortable_list .tableish .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(1) a");
        return $this;
    }

    public function 一覧_削除($rowNum)
    {
        $this->tester->click("#main .container-fluid .sortable_list .tableish .item_box:nth-child(${rowNum}) .icon_edit .dropdown a");
        $this->tester->click("#main .container-fluid .sortable_list .tableish .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(3) a");
        return $this;
    }

    public function 新規登録()
    {
        $this->tester->click('#delivery_list_footer__button_area > a');
    }

    public function 一覧_名称($rowNum)
    {
        return ['css' => "#main .container-fluid .sortable_list .tableish .item_box:nth-child($rowNum) div.item_pattern a"];
    }

    public function 一覧_上に($rowNum)
    {
        $this->tester->dragAndDropBy("#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.icon_sortable", 0, -55);
        return $this;
    }

    public function 一覧_下に($rowNum)
    {
        $this->tester->dragAndDropBy("#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.icon_sortable", 0, 55);
        return $this;
    }
}