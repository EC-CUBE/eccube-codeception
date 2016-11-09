<?php

namespace Page\Admin;


class CustomerManagePage extends AbstractAdminPage
{
    public static $検索結果メッセージ = '#search_form > div.row > div > div > div.box-header.with-arrow > h3';
    public static $検索条件_仮会員 = ['id' => 'admin_search_customer_customer_status_0'];
    public static $検索条件_本会員 = ['id' => 'admin_search_customer_customer_status_1'];

    /**
     * CustomerListPage constructor.
     * @param $I
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go(\AcceptanceTester $I)
    {
        $page = new self($I);
        return $page->goPage('/customer', '会員管理会員マスター');
    }

    public function 検索($value = '')
    {
        $this->tester->fillField(['id' => 'admin_search_customer_multi'], $value);
        $this->tester->click('#search_form > div.search-box > div.row.btn_area > div > button');
        return $this;
    }

    public function 一覧_編集($rowNum)
    {
        $this->一覧_メニュー($rowNum);
        $this->tester->click("#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr:nth-child(${rowNum}) > td.icon_edit > div > ul > li:nth-child(1) > a");
        return $this;
    }

    public function 一覧_削除($rowNum)
    {
        $this->一覧_メニュー($rowNum);
        $this->tester->click("#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr:nth-child(${rowNum}) > td.icon_edit > div > ul > li:nth-child(2) > a");
        return $this;
    }

    public function 一覧_仮会員メール再送($rowNum)
    {
        $this->一覧_メニュー($rowNum);
        $this->tester->click("#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr:nth-child(${rowNum}) > td.icon_edit > div > ul > li:nth-child(3) > a");
        return $this;
    }

    private function 一覧_メニュー($rowNum)
    {
        $this->tester->click("#search_form > div.row > div > div > div.box-body > div.table_list > div > table > tbody > tr:nth-child(${rowNum}) > td.icon_edit > div > a");
        return $this;
    }

    public function CSVダウンロード()
    {
        $this->CSVダウンロードメニュー();
        $this->tester->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li.dropdown.open > ul > li:nth-child(1) > a');
        return $this;
    }

    public function CSV出力項目設定()
    {
        $this->CSVダウンロードメニュー();
        $this->tester->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li.dropdown.open > ul > li:nth-child(2) > a');
    }

    private function CSVダウンロードメニュー()
    {
        $this->tester->click('#search_form > div.row > div > div > div.box-body > div.row > div > ul > li:nth-child(2) > a');
    }
}