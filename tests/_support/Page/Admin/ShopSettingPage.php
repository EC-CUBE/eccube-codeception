<?php


namespace Page\Admin;


class ShopSettingPage extends AbstractAdminPage
{
    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';
    public static $multiShippingFlg = 'form input[name=shop_master\[option_multiple_shipping\]]';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/setting/shop', '基本情報設定SHOPマスター');
    }

    public function 入力_会社名($value)
    {
        $this->tester->fillField(['id' => 'shop_master_company_name'], $value);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#point_form #aside_column button');
        return $this;
    }

    /**
     * Change multi shipping radio
     * value = 無効/有効
     *
     * @param string $value
     * @return $this
     */
    public function changeMultiShipping($value = '有効')
    {
        $this->tester->selectOption(self::$multiShippingFlg, $value);

        return $this;
    }
}
