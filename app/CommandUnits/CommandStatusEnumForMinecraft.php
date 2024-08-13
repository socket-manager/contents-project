<?php
/**
 * コマンドUNITステータス名のENUMファイル
 * 
 * マインクラフト用
 */

namespace App\CommandUnits;


/**
 * コマンドUNITステータス名定義
 * 
 * マインクラフト用
 */
enum CommandStatusEnumForMinecraft: string
{
    //--------------------------------------------------------------------------
    // 定数（共通）
    //--------------------------------------------------------------------------

    /**
     * @var 処理開始時のステータス名
     */
    case START = CommandStatusEnumForWebsocket::START->value;


    //--------------------------------------------------------------------------
    // 定数（CommandQueueEnumForMinecraft::USERSEARCH_RESULTキュー）
    //--------------------------------------------------------------------------

    /**
     * @var ユーザー検索結果送信中のステータス名
     */
    case SENDING = 'sending';

    //--------------------------------------------------------------------------
    // 定数（CommandQueueEnumForMinecraft::ITEM_USEDキュー）
    //--------------------------------------------------------------------------

    /**
     * @var 矢のアイテム使用イベント待ち受け
     */
    case ARROW = 'arrow';

    //--------------------------------------------------------------------------
    // 定数（CommandQueueEnumForMinecraft::CHAIRキュー）
    //--------------------------------------------------------------------------

    /**
     * @var ブロック検査レスポンス待ち受け
     */
    case CHAIR_RESPONSE = 'chair-response';

    //--------------------------------------------------------------------------
    // メソッド
    //--------------------------------------------------------------------------

}
