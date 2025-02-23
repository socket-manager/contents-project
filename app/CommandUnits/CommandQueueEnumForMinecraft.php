<?php
/**
 * コマンド部のキュー名のENUMファイル
 * 
 * マインクラフト用
 */

namespace App\CommandUnits;


/**
 * コマンド部のキュー名定義
 * 
 * マインクラフト用
 */
enum CommandQueueEnumForMinecraft: string
{
    //--------------------------------------------------------------------------
    // 定数
    //--------------------------------------------------------------------------

    /**
     * @var 入室時のキュー名
     */
    case ENTRANCE = CommandQueueEnumForWebsocket::ENTRANCE->value;

    /**
     * @var 入室待機時のキュー名
     */
    case ENTRANCE_WAITING = 'entrance-waiting';

    /**
     * @var チャットコメント時のキュー名
     */
    case MESSAGE = CommandQueueEnumForWebsocket::MESSAGE->value;

    /**
     * @var 退室時のキュー名
     */
    case EXIT = CommandQueueEnumForWebsocket::EXIT->value;

    /**
     * @var クライアント要求切断時のキュー名
     */
    case CLOSE = CommandQueueEnumForWebsocket::CLOSE->value;

    /**
     * @var プライベートコメント送信時のキュー名
     */
    case PRIVATE = CommandQueueEnumForWebsocket::PRIVATE->value;

    /**
     * @var プライベートコメント送信結果受信時のキュー名
     */
    case PRIVATE_RESULT = CommandQueueEnumForWebsocket::PRIVATE_RESULT->value;

    /**
     * @var ユーザー名重複チェック結果受信時のキュー名
     */
    case USERSEARCH_RESULT = CommandQueueEnumForWebsocket::USERSEARCH_RESULT->value;

    /**
     * @var マインクラフトからのレスポンス時のキュー名
     */
    case RESPONSE = 'response';

    /**
     * @var マインクラフトからのスタンド攻撃レスポンス時のキュー名
     */
    case RESPONSE_STAND_ATTACK = 'response_stand_attack';

    /**
     * @var マインクラフトからのItemUsedイベント発生時のキュー名
     */
    case ITEM_USED = 'item_used';

    /**
     * @var マインクラフトからのPlayerTravelledイベント発生時のキュー名
     */
    case PLAYER_TRAVELLED = 'player_travelled';

    /**
     * @var マインクラフトからのダッシュイベント発生時のキュー名
     */
    case PLAYER_DASH = 'player_dash';

    /**
     * @var Webブラウザからのコマンド実行のキュー名
     */
    case EXECUTE_COMMAND = 'execute-command';

    /**
     * @var マインクラフトからのスニークイベント発生時のキュー名
     */
    case CHAIR = 'chair';

    /**
     * @var マインクラフトから階段チェアからの起立イベント発生時のキュー名
     */
    case CHAIR_STANDUP = 'chair-standup';

    /**
     * @var SHOPブラウザからの入室時のキュー名
     */
    case SHOP_ENTRANCE = 'shop-entrance';

    /**
     * @var SHOPからのサバイバルモードチェンジ要求のキュー名
     */
    case SHOP_SURVIVAL_CHANGE = 'shop-survival-change';

    /**
     * @var SHOPへロック解除要求のキュー名
     */
    case SHOP_RELEASE_LOCK = 'shop-release-lock';

    /**
     * @var SHOPからの購入時のキュー名
     */
    case SHOP_BUY = 'shop-buy';

    /**
     * @var SHOPへ売却登録時のキュー名
     */
    case SHOP_SELL_ENTRY = 'shop-sell-entry';

    /**
     * @var SHOPからの返却時のキュー名
     */
    case SHOP_SELL_RELEASE = 'shop-sell-release';

    /**
     * @var SHOPからの売却時のキュー名
     */
    case SHOP_SELL = 'shop-sell';

    /**
     * @var 繰風弾（上昇）処理のキュー名
     */
    case WIND_CONTROL_UP = 'wind-control-up';

    /**
     * @var ホバー後処理のキュー名
     */
    case HOVER_FINISH = 'hover-finish';


    //--------------------------------------------------------------------------
    // メソッド
    //--------------------------------------------------------------------------

}
