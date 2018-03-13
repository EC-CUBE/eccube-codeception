<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2018 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Page\Admin;


class ProductClassEditPage extends AbstractAdminPageStyleGuide
{
    public static $登録完了メッセージ = ['css' => '#page_admin_product_product_class > div > div.c-contentsArea > div.alert.alert-success.alert-dismissible.fade.show.m-3'];

    public static $初期化ボタン = ['css' => '#form > div.c-contentsArea__cols > div > div > div:nth-child(1) > div.card-header > div > div.col-4.text-right > button'];

    /**
     * ProductReProductClassEditPagegisterPage constructor.
     */
    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);
    }

    public static function at($I)
    {
        $page = new ProductClassEditPage($I);
        $page->atPage('商品登録（規格設定）商品管理');
        return $page;
    }

    public function 規格設定()
    {
        $this->tester->click(['css' => '#form > div.c-contentsArea__cols > div > div > div > div.card-header > div > div.col-4.text-right > button']);
        return $this;
    }

    public function 入力_規格1($value)
    {
        $this->tester->selectOption(['id' => 'form_class_name1'], $value);
        return $this;
    }

    public function 選択($rowNum)
    {
        $rowNum -= 1;
        $this->tester->checkOption(['id' => "form_product_classes_${rowNum}_add"]);
        return $this;
    }

    public function 規格初期化()
    {
        $this->tester->click(self::$初期化ボタン);
        $this->tester->waitForElement(['css' => '#initializationConfirm > div > div > div.modal-footer > button.btn.btn-ec-delete']);
        $this->tester->wait(1);
        $this->tester->click(['css' => '#initializationConfirm > div > div > div.modal-footer > button.btn.btn-ec-delete']);
        return $this;
    }

    public function 登録()
    {
        $this->tester->click(['css' => '#form > div.c-conversionArea > div > div > div:nth-child(2) > div > div > button']);
        return $this;
    }
}