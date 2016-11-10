<?php


namespace Page\Admin;


class CustomerEditPage extends AbstractAdminPage
{

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    /**
     * CustomerRegisterPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/customer/new', '会員管理会員登録・編集');
    }

    public static function at($I)
    {
        $page = new self($I);
        return $page->atPage('会員管理会員登録・編集');
    }

    public function 入力_姓($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_name_name01'], $value);
        return $this;
    }

    public function 入力_名($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_name_name02'], $value);
        return $this;
    }

    public function 入力_セイ($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_kana_kana01'], $value);
        return $this;
    }

    public function 入力_メイ($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_kana_kana02'], $value);
        return $this;
    }

    public function 入力_郵便番号1($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_zip_zip01'], $value);
        return $this;
    }

    public function 入力_郵便番号2($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_zip_zip02'], $value);
        return $this;
    }

    public function 入力_都道府県($value)
    {
        $this->tester->selectOption(['id' => 'admin_customer_address_pref'], $value);
        return $this;
    }

    public function 入力_市区町村名($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_address_addr01'], $value);
        return $this;
    }

    public function 入力_番地_ビル名($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_address_addr02'], $value);
        return $this;
    }

    public function 入力_Eメール($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_email'], $value);
        return $this;
    }

    public function 入力_電話番号1($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_tel_tel01'], $value);
        return $this;
    }

    public function 入力_電話番号2($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_tel_tel02'], $value);
        return $this;
    }

    public function 入力_電話番号3($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_tel_tel03'], $value);
        return $this;
    }

    public function 入力_パスワード($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_password_first'], $value);
        return $this;
    }

    public function 入力_パスワード確認($value)
    {
        $this->tester->fillField(['id' => 'admin_customer_password_second'], $value);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#button_box__insert_button > div > button');
        return $this;
    }

}