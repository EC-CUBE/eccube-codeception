<?php


namespace Page\Admin;


class PaymentManagePage extends AbstractAdminPage
{

    public static $一覧_タイトル = '.c-contentsArea__cols > .c-contentsArea__primaryCol .c-primaryCol .card-header';
    public static $登録完了メッセージ = '.c-container .c-contentsArea div.alert-success';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/setting/shop/payment', '基本情報設定支払方法管理');
    }

    public static function at($I)
    {
        $page = new self($I);
        return $page->atPage('基本情報設定支払方法管理');
    }

    public function 一覧_支払方法($rowNum)
    {
        return "#main .container-fluid .table_list table tbody tr td:nth-child(${rowNum})";
    }

    public function 一覧_下に($rowNum)
    {
        $rowNum = $rowNum + 1;
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) td:nth-child(4) a");
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) td:nth-child(4) ul li:nth-child(5) a");
        return $this;
    }

    public function 一覧_編集($rowNum)
    {
        $rowNum = $rowNum + 1;
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) td:nth-child(4) a");
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) .icon_edit ul li:nth-child(1) a");
    }

    public function 一覧_削除($rowNum)
    {
        $rowNum = $rowNum + 1;
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) td:nth-child(4) a");
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) .icon_edit ul li:nth-child(3) a");
    }

    public function 新規入力()
    {
        $this->tester->click('#main .container-fluid div:nth-child(2) .btn_area a');
    }
}