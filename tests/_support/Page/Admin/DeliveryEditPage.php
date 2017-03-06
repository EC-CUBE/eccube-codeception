<?php


namespace Page\Admin;


class DeliveryEditPage extends AbstractAdminPage
{
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new self($I);
        return $page->atPage('ショップ設定配送先管理');
    }

    public function 入力_配送業者名($value) {
        $this->tester->fillField(['id' => 'delivery_name'], $value);
        return $this;
    }

    public function 入力_名称($value) {
        $this->tester->fillField(['id' => 'delivery_service_name'], $value);
        return $this;
    }

    public function 入力_支払方法選択($array) {
        foreach ($array as $id)
        {
            $this->tester->checkOption(['id' => "delivery_payments_${id}"]);
        }
        return $this;
    }

    public function 入力_全国一律送料($value) {
        $this->tester->fillField(['id' => 'delivery_free_all'], $value);
        $this->tester->click('#form1 #set_fee_all');
        return $this;
    }

    public function 登録()
    {
        $this->tester->click(['xpath' => '//form[@id="form1"]//button[text()="登録"]']);
        return $this;
    }

}