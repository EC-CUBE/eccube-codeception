<?php


namespace Page\Admin;


class PageManagePage extends AbstractAdminPage
{

    /**
     * PageManagePage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/content/page', 'コンテンツ管理ページ管理');
    }

    public function 新規入力()
    {
        $this->tester->click('#main > div > div > div > div.row.btn_area2 > div > a');
    }

    public function レイアウト編集($rowNum)
    {
        $this->tester->click("div.item_box.tr:nth-child(${rowNum}) > div.icon_edit.td > div > a");
        $this->tester->click("div.item_box.tr:nth-child(${rowNum}) > div.icon_edit.td > div > ul > li:nth-child(1) > a");
    }

    public function ページ編集($rowNum)
    {
        $this->tester->click("div.item_box.tr:nth-child(${rowNum}) > div.icon_edit.td > div > a");
        $this->tester->click("div.item_box.tr:nth-child(${rowNum}) > div.icon_edit.td > div > ul > li:nth-child(2) > a");
    }

    public function 削除($rowNum)
    {
        $this->tester->click("div.item_box.tr:nth-child(${rowNum}) > div.icon_edit.td > div > a");
        $this->tester->click("div.item_box.tr:nth-child(${rowNum}) > div.icon_edit.td > div > ul > li:nth-child(3) > a");
    }
}