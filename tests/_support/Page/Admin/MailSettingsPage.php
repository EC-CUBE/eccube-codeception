<?php


namespace Page\Admin;


class MailSettingsPage extends AbstractAdminPage
{
    public static $登録完了メッセージ = '#main .container-fluid .alert-success';

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/setting/shop/mail', 'ショップ設定メール管理');
    }

    public function 入力_テンプレート($value) {
        $this->tester->selectOption(['id' => 'mail_template'], $value);
        return $this;
    }

    public function 入力_件名($value) {
        $this->tester->fillField(['id' => 'mail_mail_subject'], $value);
        return $this;
    }

    public function 登録() {
        $this->tester->click('#form1 #aside_column button');
        return $this;
    }


}