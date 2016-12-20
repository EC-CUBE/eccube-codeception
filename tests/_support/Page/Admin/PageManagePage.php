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

    public function レイアウト編集($pageName)
    {
        $this->tester->click(['xpath' => "//*[@id='sortable_list_box__list']//div[@class='item_box tr']/div[@class='item_pattern td'][contains(text(),'${pageName}')]/parent::node()/div[@class='icon_edit td']/div/a"]);
        $this->tester->click(['xpath' => "//*[@id='sortable_list_box__list']//div[@class='item_box tr']/div[@class='item_pattern td'][contains(text(),'${pageName}')]/parent::node()/div[@class='icon_edit td']/div/ul/li[1]/a"]);
    }

    public function ページ編集($pageName)
    {
        $this->tester->click(['xpath' => "//*[@id='sortable_list_box__list']//div[@class='item_box tr']/div[@class='item_pattern td'][contains(text(),'${pageName}')]/parent::node()/div[@class='icon_edit td']/div/a"]);
        $this->tester->click(['xpath' => "//*[@id='sortable_list_box__list']//div[@class='item_box tr']/div[@class='item_pattern td'][contains(text(),'${pageName}')]/parent::node()/div[@class='icon_edit td']/div/ul/li[2]/a"]);
    }

    public function 削除($pageName)
    {
        $this->tester->click(['xpath' => "//*[@id='sortable_list_box__list']//div[@class='item_box tr']/div[@class='item_pattern td'][contains(text(),'${pageName}')]/parent::node()/div[@class='icon_edit td']/div/a"]);
        $this->tester->click(['xpath' => "//*[@id='sortable_list_box__list']//div[@class='item_box tr']/div[@class='item_pattern td'][contains(text(),'${pageName}')]/parent::node()/div[@class='icon_edit td']/div/ul/li[3]/a"]);
    }
}