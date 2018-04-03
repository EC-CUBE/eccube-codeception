<?php

namespace Page\Admin;


class ShippingManagePage extends AbstractAdminPageStyleGuide
{
    public static $検索条件_受注ステータス = ['id' => 'admin_search_shipping_status'];
    public static $検索結果_メッセージ = '#search_form > div.c-outsideBlock__contents.mb-3 > span';

    /**
     * ShippingListPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go(\AcceptanceTester $I)
    {
        $page = new self($I);
        return $page->goPage('/shipping', '出荷マスター出荷管理');
    }

    public static function at(\AcceptanceTester $I)
    {
        $page = new self($I);
        return $page->atPage('出荷管理出荷マスター');
    }

    public function 検索($value = '')
    {
        $this->tester->fillField(['id' => 'admin_search_shipping_multi'], $value);
        $this->tester->click('#search_form > div.c-outsideBlock__contents.mb-3 > button');
        return $this;
    }

    public function 出荷CSVダウンロード実行()
    {
        $this->tester->click('#form_bulk #btn_csv_download');
        return $this;
    }

    public function 出荷出荷CSV出力項目設定()
    {
        $this->tester->click('#form_bulk #btn_csv_setting');
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
