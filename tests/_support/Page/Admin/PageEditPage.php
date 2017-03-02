<?php


namespace Page\Admin;


class PageEditPage extends AbstractAdminPage
{

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    /**
     * PageNewPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new self($I);
        $page->atPage('コンテンツ管理ページ管理');
        $page->tester->see('ページ詳細編集', '#aside_wrap > form > div.col-md-9 > div:nth-child(1) > div.box-header > h3');
        return $page;
    }

    public function 入力_名称($value)
    {
        $this->tester->fillField(['id' => 'main_edit_name'], $value);
        return $this;
    }

    public function 入力_URL($value)
    {
        $this->tester->fillField(['id' => 'main_edit_url'], $value);
        return $this;
    }

    public function 入力_ファイル名($value)
    {
        $this->tester->fillField(['id' => 'main_edit_file_name'], $value);
        return $this;
    }

    public function 入力_内容($value)
    {
        $this->tester->fillField(['id' => 'main_edit_tpl_data'], $value);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#aside_column > div > div > div > div > div > button');
    }
}