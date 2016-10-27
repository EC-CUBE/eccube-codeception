<?php

use Codeception\Util\Fixtures;

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

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/content/news');
        $I->see('コンテンツ管理新着情報管理', '#main .page-header');

        /* 登録 */
        $I->click('#main > div > div > div > div.row > div > a');
        $I->see('新着情報登録・編集', '#aside_wrap > div.col-md-9 > div.box > div > h3');
        $I->executeJS("$('#admin_news_date').val('".date("Y-m-d")."').change();");
        $I->fillField('#admin_news_title', 'news_title1');
        $I->fillField('#admin_news_comment', 'newsnewsnewsnewsnews');
        $I->click('#aside_column > div > div > div > div > button');
        $I->see('新着情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        /* 編集 */
        $I->click('#form1 > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > a');
        $I->click('#form1 > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > ul > li:nth-child(1) > a');
        $I->see('コンテンツ管理新着情報管理', '#main .page-header');
        $I->fillField('#admin_news_title', 'news_title2');
        $I->click('#aside_column > div > div > div > div > button');
        $I->see('新着情報を保存しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->see('news_title2', '#form1 > div > div > table > tbody > tr:nth-child(1) > td:nth-child(3)');

        /* 上へ下へ */
        $I->click('#form1 > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > a');
        $I->click('#form1 > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > ul > li:nth-child(3) > a');
        $I->see('news_title2', '#form1 > div > div > table > tbody > tr:nth-child(2) > td:nth-child(3)');

        /* 削除 */
        $I->click('#form1 > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > a');
        $I->click('#form1 > div > div > table > tbody > tr:nth-child(1) > td.icon_edit > div > ul > li:nth-child(2) > a');
        $I->acceptPopup();
    }

    public function contentsmanagement_ファイル管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0602-UC01-T01(& UC01-T02/UC01-T03/UC01-T04/UC01-T05/UC01-T06/UC01-T07) ファイル管理');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/content/file_manager');
        $I->see('コンテンツ管理ファイル管理', '#main .page-header');

        /* ファイルのアップロード */
        /* ファイルダウンロード */
        /* ファイルの表示 */
        /* ファイルのファイルの削除 */

        // アップロード・ダウンロードが不可能なのでテスト不可

        /* フォルダ作成 */
        $I->fillField('#form_create_file', 'folder1');
        $I->click('#aside_wrap > div.col-md-9 > div > div.box-header.form-horizontal > div.form-group.form-inline > div > a');
        $I->see('folder1', '#aside_wrap > div.col-md-9 > div > div.box-body > div > div > table > tbody > tr:nth-child(1) > td:nth-child(1)');

        /* フォルダ表示 */
        $I->click('#aside_wrap > div.col-md-9 > div > div.box-body > div > div > table > tbody > tr:nth-child(1) > td:nth-child(4) > a');
        $I->see('folder1', '#bread > a:nth-child(3)');

        /* フォルダ削除 */
        $I->amOnPage('/'.$config['admin_route'].'/content/file_manager');
        $I->click('#aside_wrap > div.col-md-9 > div > div.box-body > div > div > table > tbody > tr:nth-child(1) > td:nth-child(6) > a');
        $I->acceptPopup();
    }

    public function contentsmanagement_ページ管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0603-UC01-T01(& UC01-T02/UC01-T03/UC01-T04/UC01-T05) ページ管理');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/content/page');
        $I->see('コンテンツ管理ページ管理', '#main .page-header');

        /* 作成 */
        $I->click('#main > div > div > div > div.row.btn_area2 > div > a');
        $I->see('ページ詳細編集', '#aside_wrap > div.col-md-9 > div:nth-child(1) > div.box-header > h3');
        $I->fillField('#main_edit_name', 'page1');
        $I->fillField('#main_edit_url', 'page1');
        $I->fillField('#main_edit_file_name', 'page1');
        $I->fillField('#main_edit_tpl_data', "page1");
        $I->click('#aside_column > div > div > div > div > button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->amOnPage('/user_data/page1');
        $I->see('page1', 'body');

        /* 編集 */
        $I->amOnPage('/'.$config['admin_route'].'/content/page');
        $I->click('#sortable_list_box__item--45 > div.icon_edit.td > div > a');
        $I->click('#sortable_list_box__item--45 > div.icon_edit.td > div > ul > li:nth-child(2) > a');
        $I->fillField('#main_edit_tpl_data', "{% extends 'default_frame.twig' %}");
        $I->click('#aside_column > div > div > div > div > button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->amOnPage('/user_data/page1');
        $I->see($config['shop_name'], '#header > div > div.header_logo_area > h1 > a');

        /* レイアウト編集 */
        $I->amOnPage('/'.$config['admin_route'].'/content/page');
        $I->click('#sortable_list_box__item--45 > div.icon_edit.td > div > a');
        $I->click('#sortable_list_box__item--45 > div.icon_edit.td > div > ul > li:nth-child(1) > a');
        //$I->dragAndDrop('#position_0 > div:nth-child(1)', '#position_5'); // ちゃんと動かない...ECCUBEが壊れる... ToDo
        $I->click('#aside_wrap > div.col-md-3 > div > div.box.no-header > div > div > div > button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');
        $I->amOnPage('/user_data/page1');
        $I->see($config['shop_name'], '#header > div > div.header_logo_area > h1 > a');

        /* 削除 */
        $I->amOnPage('/'.$config['admin_route'].'/content/page');
        $I->click('#sortable_list_box__item--45 > div.icon_edit.td > div > a');
        $I->click('#sortable_list_box__item--45 > div.icon_edit.td > div > ul > li:nth-child(3) > a');
        $I->acceptPopup();
    }

    public function contentsmanagement_ブロック管理(\AcceptanceTester $I)
    {
        $I->wantTo('EA0603-UC01-T01(& UC01-T02/UC01-T03) ブロック管理');

        $config = Fixtures::get('config');
        $I->amOnPage('/'.$config['admin_route'].'/content/block');
        $I->see('コンテンツ管理ブロック管理', '#main .page-header');

        /* 作成 */
        $I->click('#content_block_form > div > div > div.row.btn_area2 > div > a');
        $I->see('ブロック編集', '#aside_wrap > div.col-md-9 > div.box.form-horizontal > div.box-header > h3');
        $I->fillField('#block_name', 'block1');
        $I->fillField('#block_file_name', 'block1');
        $I->fillField('#block_block_html', "<div id='block1'>block1</div>");
        $I->click('#aside_column > div > div > div > div > button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        /* 編集 */
        $I->amOnPage('/'.$config['admin_route'].'/content/block');
        $I->click('#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(1) > div.icon_edit.td > div > a');
        $I->click('#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(1) > div.icon_edit.td > div > ul > li:nth-child(1) > a');
        $I->see('ブロック編集', '#aside_wrap > div.col-md-9 > div.box.form-horizontal > div.box-header > h3');
        $I->fillField('#block_block_html', "<div id='block1'>welcome</div>");
        $I->click('#aside_column > div > div > div > div > button');
        $I->see('登録が完了しました。', '#main .container-fluid div:nth-child(1) .alert-success');

        /* 削除 */
        $I->amOnPage('/'.$config['admin_route'].'/content/block');
        $I->click('#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(1) > div.icon_edit.td > div > a');
        $I->click('#content_block_form > div > div > div.col-md-12 > div > div.box-body.no-padding.no-border > div > div > div:nth-child(1) > div.icon_edit.td > div > ul > li:nth-child(2) > a');
        $I->acceptPopup();
    }
}
