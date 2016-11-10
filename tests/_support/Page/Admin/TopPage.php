<?php
/**
 * Created by IntelliJ IDEA.
 * User: kiyotaka_oku
 * Date: 2016/11/01
 * Time: 13:47
 */

namespace Page\Admin;


class TopPage
{
    public static $受付状況 = '#order_info';
    public static $受付状況_新規受付 = '#order_info .link_list .tableish a:nth-child(1)';
    public static $受付状況_新規受付数 = '#order_info .link_list .tableish a:nth-child(1) .item_number';
    public static $受付状況_入金待ち = '#order_info .link_list .tableish a:nth-child(2)';
    public static $受付状況_入金済み = '#order_info .link_list .tableish a:nth-child(3)';
    public static $受付状況_取り寄せ中 = '#order_info .link_list .tableish a:nth-child(4)';

    public static $お知らせ = '#cube_news';
    public static $売上状況 = '#sale_info';
    public static $ショップ状況 = '#shop_info';
    public static $ショップ状況_在庫切れ商品 = '#shop_info .link_list .tableish a:nth-child(1)';
    public static $ショップ状況_会員数 = '#shop_info .link_list .tableish a:nth-child(2)';

}