<?php

namespace Page\Admin;

/**
 * 商品管理規格編集
 * @package Page\Admin
 */
class ProductClassPage extends AbstractAdminPage
{

    public static $登録完了メッセージ = '#main .container-fluid div:nth-child(1) .alert-success';
    public static $規格名 = ['id' => 'admin_class_name_name'];

    /**
     * ProductClassPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new ProductClassPage($I);
        return $page->goPage('/product/class_name', '商品管理規格編集');
    }

    public function 入力_規格名($value)
    {
        $this->tester->fillField(self::$規格名, $value);
        return $this;
    }

    public function 規格作成()
    {
        $this->tester->click('#form1 > div > div > button');
        return $this;
    }

    public function 一覧_名称($rowNum)
    {
        return "#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.item_pattern > a";
    }

    public function 一覧_分類登録($rowNum)
    {
        $this->一覧_オプション($rowNum);
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(1) a");
        return $this;
    }

    public function 一覧_編集($rowNum)
    {
        $this->一覧_オプション($rowNum);
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(2) a");
        return $this;
    }

    public function 一覧_削除($rowNum)
    {
        $this->一覧_オプション($rowNum);
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown ul li:nth-child(3) a");
        return $this;
    }

    private function 一覧_オプション($rowNum)
    {
        $this->tester->click("#main .container-fluid .box .box-body .item_box:nth-child(${rowNum}) .icon_edit .dropdown a");
    }

    public function 一覧_上に($rowNum)
    {
        $dragTo = $rowNum - 1;
        $this->tester->dragAndDrop(
            "#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.icon_sortable",
            "#main .container-fluid .box .box-body .item_box:nth-child($dragTo) div.icon_sortable");
        return $this;
    }

    public function 一覧_下に($rowNum)
    {
        $dragTo = $rowNum + 1;
        $this->tester->dragAndDrop(
            "#main .container-fluid .box .box-body .item_box:nth-child($rowNum) div.item_pattern > a",
            "#main .container-fluid .box .box-body .item_box:nth-child($dragTo) div.item_pattern > a");
        return $this;
    }
}