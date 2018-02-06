<?php


namespace Page\Admin;


class ShopSettingPage extends AbstractAdminPage
{
    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/setting/shop', '基本情報設定ショップマスター');
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
}