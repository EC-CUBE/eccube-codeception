<?php

namespace Page\Admin;

/**
 * 商品管理/商品マスター
 * @package Page\Admin
 */
class ProductManagePage extends AbstractAdminPage
{
    public static $URL = '/product';

    public static $検索条件_プロダクト = ['id' => 'admin_search_product_id'];
    public static $検索ボタン = '#search_form button';

    public static $検索結果_メッセージ = '#main .container-fluid .box .box-title';
    public static $検索結果_一覧 = "#main .container-fluid .box-body .item_list";

    public static $検索結果_CSVダウンロード = '#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > a';
    public static $検索結果_CSVダウンロード_CSVダウンロード = '#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > ul > li:nth-child(1) > a';
    public static $検索結果_CSVダウンロード_出力項目設定 = '#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(1) > div:nth-child(2) > ul > li:nth-child(2) > ul > li:nth-child(2) > a';

    protected $tester;

    /**
     * ProductListPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function go(\AcceptanceTester $I)
    {
        $page = new ProductManagePage($I);
        return $page->goPage(self::$URL, '商品管理商品マスター');
    }

    /**
     * 指定した商品名/ID/コードで検索する。
     * @param string $product 商品名/ID/コード
     * @return $this
     */
    public function 検索($product = '')
    {
        $this->tester->fillField(self::$検索条件_プロダクト, $product);
        $this->tester->click(self::$検索ボタン);
        $this->tester->see('商品管理商品マスター', '#main .page-header');
        return $this;
    }

    /**
     * 検索結果の指定した行の規格設定に遷移。
     * @param int $rowNum 検索結果の行番号(1から始まる)
     * @return $this
     */
    public function 検索結果_規格設定($rowNum)
    {
        $this->検索結果_オプション($rowNum);
        $this->tester->click("#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(${rowNum}) > div:nth-child(4) > div > ul > li:nth-child(1) > a");
    }

    /**
     * 検索結果の指定した行の複製。
     * @param int $rowNum 検索結果の行番号(1から始まる)
     * @return $this
     */
    public function 検索結果_複製($rowNum)
    {
        $this->検索結果_オプション($rowNum);
        $this->tester->click("#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(${rowNum}) > div:nth-child(4) > div > ul > li:nth-child(3) > a");
        return $this;
    }

    /**
     * 検索結果の指定した行を選択。
     * @param int $rowNum 検索結果の行番号(1から始まる)
     * @return $this
     */
    public function 検索結果_選択($rowNum)
    {
        $this->検索結果_オプション($rowNum);
        $this->tester->click("#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(${rowNum}) > div:nth-child(3) > a");
        return $this;
    }

    /**
     * 検索結果の指定した行を削除。
     * @param int $rowNum 検索結果の行番号(1から始まる)
     * @return $this
     */
    public function 検索結果_削除($rowNum)
    {
        $this->検索結果_オプション($rowNum);
        $this->tester->click("#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(${rowNum}) > div:nth-child(4) > div > ul > li:nth-child(4) > a");
        return $this;
    }

    /**
     * 検索結果の指定した行の「...」をクリックする。
     * @param int $rowNum 検索結果の行番号(1から始まる)
     * @return $this
     */
    private function 検索結果_オプション($rowNum)
    {
        $this->tester->click("#main > div > div > div > div > div > div:nth-child(2) > div:nth-child(2) > div > div:nth-child(${rowNum}) > div:nth-child(4) > div > a");
        return $this;
    }
}