<?php


namespace Page\Admin;


class LayoutEditPage extends AbstractAdminPageStyleGuide
{

    public static $unusedBlockItem = ['css' => '#unused-block div.sort'];
    public static $登録完了メッセージ = '#page_admin_content_layout_edit > div.c-container > div.c-contentsArea > div.alert.alert-success.alert-dismissible.fade.show.m-3 > span';

    /**
     * LayoutEditPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new self($I);
        return $page->atPage('レイアウト管理コンテンツ管理');
    }

    public function 登録()
    {
        $this->tester->click('#aside_wrap > form > div.col-md-3 > div > div.box.no-header > div > div > div > button');
        return $this;
    }

    public function ブロックを移動($blockName, $dest)
    {
        $this->tester->dragAndDrop(['xpath' => "//div[contains(@id, 'detail_box__layout_item')]/a[text()='${blockName}']"], $dest);
        return $this;
    }

    public function プレビュー()
    {
        $this->tester->click("#preview_box__preview_button > button");
        return $this;
    }

    public function filterSearch($value)
    {
        $this->tester->fillField(['css' => '#unused-block div.first input'], $value);
        return $this;
    }
}