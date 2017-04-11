<?php


namespace Page\Admin;


class DeliveryEditPage extends AbstractAdminPage
{
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    /**
     * Go to delivery page function
     *
     * @param \AcceptanceTester $I
     * @param null              $id
     * @return $this
     */
    public static function go($I, $id = null)
    {
        $page = new DeliveryEditPage($I);
        if (is_numeric($id)) {
            return $page->goPage("/setting/shop/delivery/{$id}/edit", 'ショップ設定配送先管理');
        }

        return $page->goPage('/setting/shop/delivery/new', 'ショップ設定配送先管理');
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

    /**
     * select delivery type
     * Type = value/text
     *
     * @param string $value
     * @param string $type
     * @return $this
     */
    public function selectDeliveryType($value, $type = 'value')
    {
        $this->tester->selectOption(['id' => 'delivery_product_type'], array($type => $value));

        return $this;
    }

    /**
     * Create delivery time
     * $deliveryTime = array(0 => time, 1 => time, ...)
     *
     * @param array $deliveryTime
     * @return $this
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->tester->click('form #delivery_times_box #delivery_times_box__toggle h3');
        foreach ($deliveryTime as $id => $value) {
            $this->tester->fillField(['id' => "delivery_delivery_times_${id}_delivery_time"], $value);
        }

        return $this;
    }
}
