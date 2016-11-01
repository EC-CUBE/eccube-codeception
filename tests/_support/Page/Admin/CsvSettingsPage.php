<?php

namespace Page\Admin;


class CsvSettingsPage extends AbstractAdminPage
{
    public static $CSVタイプ = ['id' => 'csv-type'];

    public static $登録完了メッセージ = '#main .container-fluid .alert-success';

    protected $tester;

    /**
     * CsvSettingsPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new CsvSettingsPage($I);
        return $page->goPage('/setting/shop/csv', 'システム設定CSV出力項目設定');
    }

    public static function at($I)
    {
        $page = new CsvSettingsPage($I);
        $page->tester->see('システム設定CSV出力項目設定', '#main .page-header');
        return $page;
    }

    public function 入力_CSVタイプ($value) {
        $this->tester->selectOption(['id' => 'csv-type'], $value);
        return $this;
    }

    public function 選択_出力項目($value) {
        $this->tester->selectOption(['id' => 'csv-output'], $value);
        return $this;
    }

    public function 削除() {
        $this->tester->click('#remove');
        return $this;
    }

    public function 設定() {
        $this->tester->click('#common_button_box__confirm_button button');
        return $this;
    }

}