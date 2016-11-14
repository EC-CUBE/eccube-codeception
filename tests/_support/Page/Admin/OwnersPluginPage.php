<?php
namespace Page\Admin;


class OwnersPluginPage extends AbstractAdminPage
{

    /**
     * OwnersPluginPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go($I)
    {
        $page = new self($I);
        return $page->goPage('/store/plugin', 'オーナーズストアプラグイン一覧');
    }

    public static function goInstall($I)
    {
        $page = new self($I);
        return $page->goPage('/store/plugin/install', 'オーナーズストアプラグインのアップロード');
    }
}
