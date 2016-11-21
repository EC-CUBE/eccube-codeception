<?php

use Codeception\Util\Fixtures;
use Page\Admin\OwnersPluginPage;

/**
 * @group plugin
 * @group plugin_uninstaller
 */
class EA09PluginUninstallerCest
{
    const ページタイトル = '#main .page-header';

    protected $plugins = [];

    public function _before(\AcceptanceTester $I)
    {
        $fixtures = __DIR__.'/../_data/plugin_fixtures.php';
        if (file_exists($fixtures)) {
            $this->plugins = require $fixtures;
        }
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function plugin_プラグインアンインストール(\AcceptanceTester $I)
    {
        $I->wantTo('プラグインアンインストール');

        foreach ($this->plugins as $num => $plugin) {
            OwnersPluginPage::go($I);

            // プラグイン無効化
            $I->click(['xpath' => '/html/body/div/div/div/div/div/div[2]/div[2]/div/div/table/tbody/tr['.$num.']/td[1]/a[1]']);
            $I->see('プラグインを無効にしました。', '#main .container-fluid div:nth-child(1) .alert-success');
        }

        foreach ($this->plugins as $num => $plugin) {
            OwnersPluginPage::go($I);

            // プラグイン削除
            $I->click(['xpath' => '/html/body/div/div/div/div/div/div[2]/div[2]/div/div/table/tbody/tr[1]/td[1]/a[2]']);
            $I->acceptPopup();
            $I->see(' プラグインを削除しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        }
    }
}
