<?php

use Codeception\Util\Fixtures;

/**
 * @group admin
 * @group admin01
 * @group toppage
 * @group ea1
 */
class EA01TopCest
{
    public function _before(\AcceptanceTester $I)
    {
        // すべてのテストケース実施前にログインしておく
        // ログイン後は管理アプリのトップページに遷移している
        $I->loginAsAdmin();
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function topページ_初期表示(\AcceptanceTester $I)
    {
        $I->wantTo('EA0101-UC01-T01 TOPページ 初期表示');

        // TOP画面に現在の受注状況、お知らせ、売り上げ状況、ショップ状況が表示されている
        $I->see('受注状況', '.container-fluid #order_info');
        $I->see('お知らせ', '.container-fluid #cube_news');
        $I->see('売り上げ状況', '.container-fluid #sale_info');
        $I->see('ショップ状況', '.container-fluid #shop_info');

        // 新規受付をクリックすると受注管理画面に遷移することを確認
        $I->click('#order_info .link_list .tableish a:nth-child(1)');
        $I->see('受注マスター', '#main .page-header');
        $I->goToAdminPage();

        // 購入された商品が受注管理画面のページにて反映されていることを確認
        $config = Fixtures::get('test_config');
        $I->see($config['fixture_customer_num'], '.container-fluid #order_info .link_list .tableish a:nth-child(1) .item_number');

        // FIXME ソート順が指定されていないのでテストが失敗する
        // https://github.com/EC-CUBE/ec-cube/issues/1908
        // // 入金待ちをクリックすると「受注管理＞入金待ち」のページに遷移することを確認
        // $I->click('#order_info .link_list .tableish a:nth-child(2)');
        // $I->see('受注マスター', '#main .page-header');
        // $I->seeInField(['id' => 'admin_search_order_status'], '2'/*入金待ち*/);
        // $I->goToAdminPage();

        // // 入金済みをクリックすると「受注管理＞入金済み」のページに遷移することを確認
        // $I->click('#order_info .link_list .tableish a:nth-child(3)');
        // $I->see('受注マスター', '#main .page-header');
        // $I->seeInField(['id' => 'admin_search_order_status'], '6'/*入金済み*/);
        // $I->goToAdminPage();

        // // 取り寄せ中をクリックすると「受注管理＞取り寄せ」のページに遷移することを確認
        // $I->click('#order_info .link_list .tableish a:nth-child(4)');
        // $I->see('受注マスター', '#main .page-header');
        // $I->seeInField(['id' => 'admin_search_order_status'], '4'/*取り寄せ中*/);
        // $I->goToAdminPage();

        // お知らせの記事をクリックすると設定されたURLに遷移することを確認
        /*
        該当のiframe要素にname属性がないのでアクセスできない...
        eccube側のtemplateを修正の上で実行
        $I->switchToIFrame(".link_list_wrap");
        $I->click('#newsarea .link_list .tableish a:nth-child(3)');
        $I->switchToIFrame();
        */

        // ショップ情報の在庫切れ商品をクリックすると商品管理ページに遷移することを確認
        $I->click('#shop_info .link_list .tableish a:nth-child(1)');
        $I->see('商品マスター', '#main .page-header');
        $I->goToAdminPage();

        // ショップ情報の会員数をクリックすると会員管理に遷移することを確認
        $I->click('#shop_info .link_list .tableish a:nth-child(2)');
        $I->see('会員マスター', '#main .page-header');
        $I->dontSeeCheckboxIsChecked(['id' => 'admin_search_customer_customer_status_0']);
        $I->seeCheckboxIsChecked(['id' => 'admin_search_customer_customer_status_1']);
    }
}
