<?php


namespace Page\Admin;


class TaxManagePage extends AbstractAdminPage
{
    public static $登録完了メッセージ = '#main .container-fluid .alert-success';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/setting/shop/tax', '基本情報設定税率設定');
    }

    public function 入力_消費税率($value) {
        $this->tester->fillField(['id' => 'tax_rule_tax_rate'], $value);
        return $this;
    }

    public function 入力_適用日時($value) {
        $this->tester->fillField(['id' => 'tax_rule_apply_date'], $value);
        return $this;
    }

    public function 入力_個別税率設定($value) {
        $this->tester->selectOption(['id' => 'tax_rule_option_product_tax_rule_0'], $value);
        return $this;
    }

    public function 個別税率設定_登録()
    {
        $this->tester->click('#form1 div div div:nth-child(2) button');
        return $this;
    }

    public function 一覧_編集($rowNum)
    {
        $this->tester->click("#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(${rowNum}) .icon_edit .dropdown a");
        $this->tester->click("#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(1) a");
        return $this;
    }

    public function 一覧_削除($rowNum)
    {
        $this->tester->click("#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(${rowNum}) .icon_edit .dropdown a");
        $this->tester->click("#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(2) a");
        return $this;
    }

    public function 一覧_税率($rowNum)
    {
        return "#form1 div div div:nth-child(5) .box-body div table tbody tr:nth-child(${rowNum}) td:nth-child(2)";
    }

    public function 共通税率設定_登録()
    {
        $this->tester->click('#form1 div div div:nth-child(4) button');
        return;
    }
}