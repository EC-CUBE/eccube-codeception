<?php


namespace Page\Admin;


class LayoutEditPage extends AbstractAdminPage
{

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';

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
        return $page->atPage('コンテンツ管理レイアウト管理');
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
}