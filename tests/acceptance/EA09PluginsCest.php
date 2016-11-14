<?php

use Codeception\Util\Fixtures;
use Page\Admin\OwnersPluginPage;

/**
 * @group admin
 * @group admin04
 * @group plugins
 * @group ea9
 */
class EA09PluginsCest
{
    const ページタイトル = '#main .page-header';

    protected $plugins = [];

    public function _before(\AcceptanceTester $I)
    {
        $this->plugins = [
            [
                'code' => 'Coupon',
                'file' => 'Coupon_v1.0.3.tar.gz',
                'url' => 'https://github.com/EC-CUBE/coupon-plugin/releases/download/1.0.3/Coupon_v1.0.3.tar.gz'
            ],
            [
                'code' => 'Maker',
                'file' => 'Maker_v0.0.2.tar.gz',
                'url' => 'https://github.com/EC-CUBE/maker-plugin/releases/download/0.0.2/Maker_v0.0.2.tar.gz'
            ],
            [
                'code' => 'Recommend',
                'file' => 'Recommend_v1.0.3.tar.gz',
                'url' => 'https://github.com/EC-CUBE/Recommend-plugin/releases/download/1.0.3/Recommend_v1.0.3.tar.gz'
            ],
            // [
            //     'code' => 'SalesReport',
            //     'file' => 'SalesReport_v0.0.2.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/sales-report-plugin/releases/download/0.0.2/SalesReport_v0.0.2.tar.gz'

            // ],
            // [
            //     'code' => 'RelatedProduct',
            //     'file' => 'RelatedProduct_v0.0.2.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/related-product-plugin/releases/download/0.0.2/RelatedProduct_v0.0.2.tar.gz'
            // ],
            // [
            //     'code' => 'MailMaga',
            //     'file' => 'MailMaga_v0.0.3.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/mail-magazine-plugin/releases/download/0.0.3/MailMaga_v0.0.3.tar.gz'
            // ],
            // [
            //     'code' => 'EccubeApi',
            //     'file' => 'EccubeApi-1.0.2.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/eccube-api/releases/download/v0-beta-1.0.2/EccubeApi-1.0.2.tar.gz'
            // ],
            // [
            //     'code' => 'Point',
            //     'file' => 'Point_v1.0.0.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/point-plugin/releases/download/1.0.0/Point_v1.0.0.tar.gz'
            // ],
            // [
            //     'code' => 'OrderPdf',
            //     'file' => 'OrderPdf_v0.0.2.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/order-pdf-plugin/releases/download/0.0.2/OrderPdf_v0.0.2.tar.gz'
            // ],
            // [
            //     'code' => 'ListingAdCsv',
            //     'file' => 'ListingAdCsv_20160204.tar.gz',
            //     'url' => 'https://github.com/EC-CUBE/listing-ad-plugin/releases/download/v.1.0.1/ListingAdCsv_20160204.tar.gz'
            // ]
        ];

        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    /**
     * @group plugin_installer
     */
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
            $archive = file_get_contents($plugin['url']);
            file_put_contents($datadir.'/'.$plugin['file'], $archive);

            $I->attachFile(['id' => 'plugin_local_install_plugin_archive'],  $plugin['file']);
            $I->click('#aside_column button');
            $I->see('プラグインをインストールしました。', '#main .container-fluid div:nth-child(1) .alert-success');

            // プラグイン有効化
            $I->click(['xpath' => '/html/body/div/div/div/div/div/div[2]/div[2]/div/div/table/tbody/tr['.$num.']/td[1]/a[1]']);
            $I->see('プラグインを有効にしました。', '#main .container-fluid div:nth-child(1) .alert-success');
        }
    }

    /**
     * @group plugin_uninstaller
     */
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
