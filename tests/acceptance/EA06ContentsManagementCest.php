<?php

use Codeception\Util\Fixtures;
use Page\Admin\BlockEditPage;
use Page\Admin\BlockManagePage;
use Page\Admin\FileManagePage;
use Page\Admin\LayoutEditPage;
use Page\Admin\NewsManagePage;
use Page\Admin\NewsEditPage;
use Page\Admin\PageManagePage;
use Page\Admin\PageEditPage;

/**
 * @group admin
 * @group admin02
 * @group contentsmanagement
 * @group ea6
 */
class EA06ContentsManagementCest
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

    public function contentsmanagement_新着情報管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0601-UC01-T01(& UC02-T01/UC02-T02/UC03-T01) 新着情報管理（作成・編集・削除）');

        NewsManagePage::go($I)->新規登録();

        NewsEditPage::of($I)
            ->入力_日付(date('Y-m-d'))
            ->入力_タイトル('news_title1')
            ->入力_本文('newsnewsnewsnewsnews')
            ->登録();

        $NewsListPage = NewsManagePage::at($I);
        $I->see('新着情報を保存しました。', NewsManagePage::$登録完了メッセージ);

        $NewsListPage->一覧_編集(1);

        NewsEditPage::of($I)
            ->入力_タイトル('news_title2')
            ->登録();

        $NewsListPage = NewsManagePage::at($I);
        $I->see('新着情報を保存しました。', NewsManagePage::$登録完了メッセージ);
        $I->see('news_title2', $NewsListPage->一覧_タイトル(1));

        $NewsListPage->一覧_下へ(1);
        $I->see('news_title2', $NewsListPage->一覧_タイトル(2));

        // TODO [漏れ] 上に

        $NewsListPage->一覧_削除(1);
        $I->acceptPopup();
    }

    /**
     * @env firefox
     * @env chrome
     */
    public function contentsmanagement_ファイル管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0602-UC01-T01(& UC01-T02/UC01-T03/UC01-T04/UC01-T05/UC01-T06/UC01-T07) ファイル管理');

        /** @var FileManagePage $FileManagePage */
        $FileManagePage = FileManagePage::go($I)
            ->入力_ファイル('upload.txt')
            ->アップロード();

        $I->see('upload.txt', $FileManagePage->ファイル名(1));

        $FileManagePage->一覧_ダウンロード(1);
        $UploadedFile = $I->getLastDownloadFile('/^upload\.txt$/');
        $I->assertEquals('This is uploaded file.', file_get_contents($UploadedFile));

        $FileManagePage->一覧_表示(1);
        $I->switchToNewWindow();
        $I->see('This is uploaded file.');

        FileManagePage::go($I)
            ->一覧_削除(1);
        $I->acceptPopup();
        $I->dontSee('upload.txt', $FileManagePage->ファイル名(1));

        $FileManagePage = FileManagePage::go($I)
            ->入力_フォルダ名('folder1')
            ->フォルダ作成();

        $I->see('folder1', $FileManagePage->ファイル名(1));

        $FileManagePage->一覧_表示(1);
        $I->see('folder1', $FileManagePage->パンくず(1));

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/content/file_manager');
        $I->see('コンテンツ管理ファイル管理', '#main .page-header');

        FileManagePage::go($I)
            ->一覧_削除(1);
        $I->acceptPopup();
    }

    public function contentsmanagement_ページ管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0603-UC01-T01(& UC01-T02/UC01-T03/UC01-T04/UC01-T05) ページ管理');

        PageManagePage::go($I)->新規入力();

        /* 作成 */
        PageEditPage::at($I)
            ->入力_名称('page1')
            ->入力_ファイル名('page1')
            ->入力_URL('page1')
            ->入力_内容('page1')
            ->登録();
        $I->see('登録が完了しました。', PageEditPage::$登録完了メッセージ);

        $I->amOnPage('/user_data/page1');
        $I->see('page1', 'body');

        /* 編集 */
        PageManagePage::go($I)->ページ編集(43);
        PageEditPage::at($I)
            ->入力_内容("{% extends 'default_frame.twig' %}")
            ->登録();
        $I->see('登録が完了しました。', PageEditPage::$登録完了メッセージ);

        $I->amOnPage('/user_data/page1');
        $config = Fixtures::get('config');
        $I->see($config['shop_name'], '#header > div > div.header_logo_area > h1 > a');

        /* レイアウト編集 */
        PageManagePage::go($I)->レイアウト編集(43);
        //$I->dragAndDrop('#position_0 > div:nth-child(1)', '#position_5'); // TODO [other] ちゃんと動かない...ECCUBEが壊れる...
        LayoutEditPage::at($I)->登録();

        // TODO [new window] EA0603-UC01-T05	レイアウトプレビュー

        $I->see('登録が完了しました。', LayoutEditPage::$登録完了メッセージ);
        $I->amOnPage('/user_data/page1');
        $I->see($config['shop_name'], '#header > div > div.header_logo_area > h1 > a');

        /* 削除 */
        PageManagePage::go($I)->削除(43);
        $I->acceptPopup();
    }

    public function contentsmanagement_ブロック管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0603-UC01-T01(& UC01-T02/UC01-T03) ブロック管理');

        /* 作成 */
        BlockManagePage::go($I)->新規入力();
        BlockEditPage::at($I)
            ->入力_ブロック名('block1')
            ->入力_ファイル名('block1')
            ->入力_データ("<div id='block1'>block1</div>")
            ->登録();
        $I->see('登録が完了しました。', BlockEditPage::$登録完了メッセージ);

        /* 編集 */
        BlockManagePage::go($I)->編集(1);
        BlockEditPage::at($I)
            ->入力_データ("<div id='block1'>welcome</div>")
            ->登録();
        $I->see('登録が完了しました。', BlockEditPage::$登録完了メッセージ);

        /* 削除 */
        BlockManagePage::go($I)->削除(1);
        $I->acceptPopup();

        // TODO [漏れ] レイアウト管理でブロックが表示/変更/非表示になるかどうか
    }
}
