<?php


namespace Page\Admin;


class PaymentEditPage extends AbstractAdminPage
{
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new self($I);
        return $page->atPage('ショップ設定支払方法管理');
    }

    public function 入力_支払方法($value) {
        $this->tester->fillField(['id' => 'payment_register_method'], $value);
        return $this;
    }

    public function 入力_手数料($value) {
        $this->tester->fillField(['id' => 'payment_register_charge'], $value);
        return $this;
    }

    public function 入力_利用条件下限($value) {
        $this->tester->fillField(['id' => 'payment_register_rule_min'], $value);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#form1 #aside_column button');
    }

}