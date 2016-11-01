<?php


namespace Page\Admin;


class BlockManagePage extends AbstractAdminPage
{

    /**
     * BlockManagePage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/content/block', 'コンテンツ管理ブロック管理');
    }

    public function 新規入力()
    {
        $this->tester->click('#content_block_form > div > div > div.row.btn_area2 > div > a');
    }

    public function 編集($rowNum)
    {
        $this->tester->click("#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(${rowNum}) > div.icon_edit.td > div > a");
        $this->tester->click("#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(${rowNum}) > div.icon_edit.td > div > ul > li:nth-child(1) > a");
    }

    public function 削除($rowNum)
    {
        $this->tester->click("#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(${rowNum}) > div.icon_edit.td > div > a");
        $this->tester->click("#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(${rowNum}) > div.icon_edit.td > div > ul > li:nth-child(2) > a");
    }

}