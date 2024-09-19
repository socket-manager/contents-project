<?php
/**
 * ショップステータスのENUMファイル
 * 
 * マインクラフト用
 */

namespace App\UnitParameter;


/**
 * ショップステータス定義
 * 
 * マインクラフト用
 */
enum ShopStatusEnum: int
{
    //--------------------------------------------------------------------------
    // 定数
    //--------------------------------------------------------------------------

    /**
     * @var 入店時
     */
    case ENTRANCE = 1;

    /**
     * @var 営業中
     */
    case OPEN = 11;

    /**
     * @var クローズ中
     */
    case CLOSE = 21;


    //--------------------------------------------------------------------------
    // メソッド
    //--------------------------------------------------------------------------

}