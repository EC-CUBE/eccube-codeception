<?php

use Codeception\Util\Fixtures;
use Page\Admin\OwnersPluginPage;

/**
 * @group plugin
 * @group plugin_installer
 */
class AA00PluginInstallerCest
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

    public function plugin_プラグインインストール(\AcceptanceTester $I)
    {
        $I->wantTo('プラグインインストール');

        foreach ($this->plugins as $num => $plugin) {
            OwnersPluginPage::go($I)
                ->goInstall($I);
            $datadir = __DIR__.'/../_data';

            if (file_exists($datadir.'/'.$plugin['file'])) {
                unlink($datadir.'/'.$plugin['file']);
            }
            $I->wantTo($plugin['file'].' を '.$plugin['url'].' からダウンロードします.');
            $archive = file_get_contents($plugin['url']);
            $save_path = $datadir.'/'.$plugin['file'];
            file_put_contents($save_path, $archive);
            $I->wantTo($plugin['file'].' を '.$save_path.' に保存しました.');

            $I->attachFile(['id' => 'plugin_local_install_plugin_archive'],  $plugin['file']);
            $I->click('#aside_column button');
            $I->see('プラグインをインストールしました。', '#main .container-fluid div:nth-child(1) .alert-success');

            // プラグイン有効化
            $I->click(['xpath' => '/html/body/div/div/div/div/div/div[2]/div[2]/div/div/table/tbody/tr['.$num.']/td[1]/a[1]']);
            $I->see('プラグインを有効にしました。', '#main .container-fluid div:nth-child(1) .alert-success');
        }
    }
}
