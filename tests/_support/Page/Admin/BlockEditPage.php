<?php


namespace Page\Admin;


class BlockEditPage extends AbstractAdminPage
{
    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

    /**
     * BlockEditPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new self($I);
        $page->atPage('コンテンツ管理ブロック管理');
        $page->tester->see('ブロック編集', '#aside_wrap > form > div.col-md-9 > div.box.form-horizontal > div.box-header > h3');
        return $page;
    }

    public function 入力_ブロック名($value)
    {
        $this->tester->fillField(['id' => 'block_name'], $value);
        return $this;
    }

    public function 入力_ファイル名($value)
    {
        $this->tester->fillField(['id' => 'block_file_name'], $value);
        return $this;
    }

    public function 入力_データ($value)
    {
        $this->tester->fillField(['id' => 'block_block_html'], $value);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click('#aside_column > div > div > div > div > div > button');
        return $this;
    }
}