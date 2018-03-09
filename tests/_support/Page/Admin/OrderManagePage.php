<?php

namespace Page\Admin;


class OrderManagePage extends AbstractAdminPageStyleGuide
{
    public static $検索条件_受注ステータス = ['id' => 'admin_search_order_status'];
    public static $検索結果_メッセージ = '#search_form #search_total_count';

    /**
     * OrderListPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go(\AcceptanceTester $I)
    {
        $page = new self($I);
        return $page->goPage('/order', '受注マスター受注管理');
    }

    public static function at(\AcceptanceTester $I)
    {
        $page = new self($I);
        return $page->atPage('受注管理受注マスター');
    }

    public function 検索($value = '')
    {
        $this->tester->fillField(['id' => 'admin_search_order_multi'], $value);
        $this->tester->click('#search_form #search_submit');
        return $this;
    }

    public function 受注CSVダウンロード実行()
    {
        $this->tester->click('#form_bulk #btn_csv_download');
        return $this;
    }

    public function 配送CSVダウンロード実行()
    {
        $this->CSVダウンロードメニュー();
        $this->tester->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li.dropdown.open > ul > li:nth-child(2) > a');
        return $this->CSVダウンロードメニュー(); // プルダウンを戻しておく
    }

    public function 受注CSV出力項目設定()
    {
        $this->tester->click('#form_bulk #btn_csv_setting');
        return $this;
    }

    public function 配送CSV出力項目設定()
    {
        $this->CSVダウンロードメニュー();
        $this->tester->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li.dropdown.open > ul > li:nth-child(4) > a');
    }

    /**
     * // TODO: Should remove this function due to new design does not have csv dropdown menu
     */
    private function CSVダウンロードメニュー()
    {
        $this->tester->click('#main > div > div.row > div > div > div.box-body > div > div > ul > li:nth-child(2) > a');
        return $this;
    }

    /**
     * TODO: Should remove this function due to new design does not have csv dropdown menu
     */
    private function 一覧_メニュー($rowNum)
    {
        $this->tester->click("#dropdown-form > div > div > table > tbody > tr:nth-child(${rowNum}) > td.icon_edit > div > a");
        return $this;
    }

    public function 一覧_編集($rowNum)
    {
        $this->tester->click("#search_result > tbody > tr:nth-child(${rowNum}) a.action-edit");
    }

    public function 一覧_削除($rowNum)
    {
        $this->tester->click("#search_result > tbody > tr:nth-child(${rowNum}) a.action-delete");
        return $this;
    }

    public function 一覧_メール通知($rowNum)
    {
        $this->tester->click("#search_result > tbody > tr:nth-child(${rowNum}) a.action-mail");
        return $this;
    }

    public function 一覧_全選択()
    {
        $this->tester->checkOption(['id' => 'check-all']);
        return $this;
    }

    /**
     * TODO: Should remove this function due to new design does not have other dropdown menu
     */
    private function その他メニュー()
    {
        $this->tester->click('#dropmenu > a');
    }

    public function メール一括通知()
    {
        $this->tester->click('#form_bulk #btn_bulk_mail');
    }

    public function 一覧_注文番号($rowNum)
    {
        return $this->tester->grabTextFrom("#search_result > tbody > tr:nth-child($rowNum) a.action-edit");
    }
}