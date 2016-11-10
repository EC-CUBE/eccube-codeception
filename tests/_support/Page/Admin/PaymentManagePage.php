<?php


namespace Page\Admin;


class PaymentManagePage extends AbstractAdminPage
{

    public static $一覧_タイトル = '#main .container-fluid .box-title';
    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/setting/shop/payment', 'ショップ設定支払方法管理');
    }

    public static function at($I)
    {
        $page = new self($I);
        return $page->atPage('ショップ設定支払方法管理');
    }

    public function 一覧_支払方法($rowNum)
    {
        return "#main .container-fluid .table_list table tbody tr td:nth-child(${rowNum})";
    }

    public function 一覧_下に($rowNum)
    {
        $rowNum = $rowNum + 1;
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) td:nth-child(4) a");
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) td:nth-child(4) ul li:nth-child(4) a");
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
        $this->tester->click("#main .container-fluid .table_list table tbody tr:nth-child(${rowNum}) .icon_edit ul li:nth-child(2) a");
    }

    public function 新規入力()
    {
        $this->tester->click('#main .container-fluid div:nth-child(2) .btn_area a');
    }
}