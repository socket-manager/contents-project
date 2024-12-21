<?php
/**
 * ステータスUNIT登録クラスのファイル
 * 
 * SocketManagerのsetCommandUnitsメソッドへ引き渡されるクラスのファイル
 */

namespace App\CommandUnits;

use SocketManager\Library\ProtocolQueueEnum;

use App\UnitParameter\ParameterForMinecraft;
use App\UnitParameter\ParameterForWebsocket;
use App\UnitParameter\ShopStatusEnum;

/**
 * コマンドUNIT登録クラス
 * 
 * CommandForWebsocketクラスをオーバーライドしてマインクラフト版として利用
 */
class CommandForMinecraft extends CommandForWebsocket
{
    //--------------------------------------------------------------------------
    // 定数
    //--------------------------------------------------------------------------

    // キューリスト
    protected const QUEUE_LIST = [
        CommandQueueEnumForMinecraft::ENTRANCE->value,             // entranceコマンドを処理するキュー
        CommandQueueEnumForMinecraft::ENTRANCE_WAITING->value,     // entrance-waitingコマンドを処理するキュー
        CommandQueueEnumForMinecraft::MESSAGE->value,              // messageコマンドを処理するキュー
        CommandQueueEnumForMinecraft::EXIT->value,                 // exitコマンドを処理するキュー
        CommandQueueEnumForMinecraft::CLOSE->value,                // closeコマンドを処理するキュー
        CommandQueueEnumForMinecraft::PRIVATE->value,              // privateコマンドを処理するキュー
        CommandQueueEnumForMinecraft::PRIVATE_RESULT->value,       // private-resultコマンドを処理するキュー
        CommandQueueEnumForMinecraft::USERSEARCH_RESULT->value,    // usersearch-resultコマンドを処理するキュー
        CommandQueueEnumForMinecraft::RESPONSE->value,             // responseコマンドを処理するキュー
        CommandQueueEnumForMinecraft::RESPONSE_STAND_ATTACK->value,// response_stand_attackコマンドを処理するキュー
        CommandQueueEnumForMinecraft::ITEM_USED->value,            // ItemUsedイベント発生時のキュー
        CommandQueueEnumForMinecraft::PLAYER_TRAVELLED->value,     // PlayerTravelledイベント発生時のキュー
        CommandQueueEnumForMinecraft::PLAYER_DASH->value,          // ダッシュイベント発生時のキュー
        CommandQueueEnumForMinecraft::EXECUTE_COMMAND->value,      // コマンド実行のキュー
        CommandQueueEnumForMinecraft::CHAIR->value,                // 階段チェア着席実行のキュー
        CommandQueueEnumForMinecraft::CHAIR_STANDUP->value,        // 階段チェアからの起立実行のキュー
        CommandQueueEnumForMinecraft::SHOP_ENTRANCE->value,        // SHOPからの入室時のキュー
        CommandQueueEnumForMinecraft::SHOP_RELEASE_LOCK->value,    // SHOPへのロック解除要求時のキュー
        CommandQueueEnumForMinecraft::SHOP_SURVIVAL_CHANGE->value, // SHOPからのサバイバルモード変更時のキュー
        CommandQueueEnumForMinecraft::SHOP_BUY->value,             // SHOPからの購入時のキュー
        CommandQueueEnumForMinecraft::SHOP_SELL_ENTRY->value,      // SHOPへ売却登録時のキュー
        CommandQueueEnumForMinecraft::SHOP_SELL_RELEASE->value,    // SHOPからの返却時のキュー
        CommandQueueEnumForMinecraft::SHOP_SELL->value,            // SHOPからの売却時のキュー
        CommandQueueEnumForMinecraft::WIND_CONTROL_UP->value       // 繰風弾（上昇）処理のキュー
    ];


    //--------------------------------------------------------------------------
    // プロパティ
    //--------------------------------------------------------------------------

    // UNITパラメータ（キャスト用）
    private ParameterForMinecraft $param;


    //--------------------------------------------------------------------------
    // メソッド
    //--------------------------------------------------------------------------

    /**
     * コンストラクタ
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ステータスUNITリストの取得
     * 
     * @param string $p_que キュー名
     * @return array キュー名に対応するUNITリスト
     */
    public function getUnitList(string $p_que): array
    {
        $ret = [];

        if($p_que === CommandQueueEnumForMinecraft::ENTRANCE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getEntranceStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::ENTRANCE_WAITING->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getEntranceWaitingStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::MESSAGE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getMessageStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::EXIT->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getExitStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::CLOSE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getCloseStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::PRIVATE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getPrivateStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::PRIVATE_RESULT->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getPrivateResultStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::USERSEARCH_RESULT->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getUserSearchResultStart()
            ];
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::SENDING->value,
                'unit' => $this->getUserSearchResultSending()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::RESPONSE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getResponseStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::RESPONSE_STAND_ATTACK->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getResponseStandAttackStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::ITEM_USED->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getItemUsedStart()
            ];
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::ARROW->value,
                'unit' => $this->getItemUsedArrow()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::PLAYER_TRAVELLED->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getMinecraftPlayerTravelledStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::PLAYER_DASH->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getMinecraftPlayerDashStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::EXECUTE_COMMAND->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getMinecraftExecuteCommandStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::CHAIR->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getMinecraftChairStart()
            ];
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::CHAIR_RESPONSE->value,
                'unit' => $this->getMinecraftChairResponse()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::CHAIR_STANDUP->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getMinecraftChairStandupStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_ENTRANCE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopEntranceStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_RELEASE_LOCK->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopReleaseLockStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_SURVIVAL_CHANGE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopSurvivalChangeStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_BUY->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopBuyStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_SELL_ENTRY->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopSellEntryStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_SELL_RELEASE->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopSellReleaseStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::SHOP_SELL->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getShopSellStart()
            ];
        }
        else
        if($p_que === CommandQueueEnumForMinecraft::WIND_CONTROL_UP->value)
        {
            $ret[] = [
                'status' => CommandStatusEnumForMinecraft::START->value,
                'unit' => $this->getWindControlUpStart()
            ];
        }

        return $ret;
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"ENTRANCE"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：入室処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getEntranceStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT ENTRANCE:START' => 'START']);

            // 受信データを取得
            $w_ret = $p_param->getRecvData();
            $msg = $w_ret['data'];

            // ユーザー名MAX長調整
            $msg['user'] = mb_substr($msg['user'], 0, ParameterForWebsocket::CHAT_USER_NAME_MAX_LENGTH);

            // 現在日時を設定
            $msg['datetime'] = date(ParameterForWebsocket::CHAT_DATETIME_FORMAT);
    
            // ユーザー名の存在をチェック
            $msg['user'] = $p_param->chopHiddenCharacters($msg['user']);
            if($msg['user'] == '')
            {
                // 自身を切断
                $p_param->closeNoUser();
                return null;
            }

            // HTML変換
            $msg['user'] = htmlspecialchars($msg['user']);

            // ユーザー名の重複チェック
            $w_ret = $p_param->checkUserName($msg['user']);
            if($w_ret === true)
            {
                // ローカルで見つからない場合はサーバー間通信を使う
                $search = $p_param->searchSendUserSearch($msg['user']);
                if($search === true)
                {
                    return null;
                }
                else
                {
                    // 現在のユーザー数を設定
                    $msg['count'] = $p_param->getClientCount();

                    // 入室コメントを設定
                    $msg['comment'] = ParameterForWebsocket::CHAT_ENTRANCE_COMMENT;
            
                    // ユーザー名をリストへ追加
                    $p_param->addUserName($msg['user']);

                    // ユーザーリストを設定
                    $msg['user_list'] = $p_param->getUserList();

                    // 全ブラウザへ配信
                    $data =
                    [
                        'data' => $msg
                    ];
                    $p_param->setSendStackAll($data, true, function(ParameterForMinecraft $p_param)
                    {
                        return !$p_param->isMinecraft();
                    }, $p_param);

                    // マインクラフト接続かどうか
                    $minecraft = $p_param->isMinecraft();

                    // 全マインクラフトへ配信
                    $cmd_data = $p_param->getCommandDataForPrivate('entrance', $msg['user'], null, $msg['comment']);
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStackAll($data, !$minecraft, function(ParameterForMinecraft $p_param)
                    {
                        return $p_param->isMinecraft();
                    }, $p_param);

                    // マインクラフトでない場合
                    if($minecraft !== true)
                    {
                        // オプションデータを設定
                        $msg['opts'] = $p_param->getOptions();
                        $data =
                        [
                            'data' => $msg
                        ];
                        // 自身へメッセージ配信
                        $p_param->setSendStack($data);
                    }

                    // チャットをログに残す
                    $p_param->chatLogWriter($msg['datetime'], $msg['user'], $msg['comment']);

                    return null;
                }
            }

            // 自身の切断シーケンス開始
            $p_param->closeUserDuplication();

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"ENTRANCE-WAITING"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：入室待ち処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getEntranceWaitingStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT ENTRANCE-WAITING:START' => 'START']);

            $sta = $p_param->getStatusName();
            $w_ret = $p_param->isExecutedQueue(ProtocolQueueEnum::ALIVE->value);
            $p_param->logWriter('debug', ['MINECRAFT ENTRANCE-WAITING:START' => "alive checking[{$w_ret}]"]);
            if($w_ret === true)
            {
                return $sta;
            }

            // サブスクライブのエントリ
            $p_param->sendSubscribesData();

            // 入室前のコマンド実行
            $p_param->execCommandBeforeEntrance();

            // ユーザー名取得
            $hdrs = $p_param->getHeaders();
            $usr_nam = str_replace('/', '', $hdrs['GET'][0]);

            /**
             * 入室コマンドの設定
             */

            $recv_data =
            [
                'data' =>
                [
                    'cmd' => CommandQueueEnumForMinecraft::ENTRANCE->value,
                    'user' => $usr_nam
                ]
            ];

            // 自身の受信スタックへ設定
            $p_param->setRecvStack($recv_data);

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"MESSAGE"キュー）
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"EXIT"キュー）
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"CLOSE"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：切断開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getCloseStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT CLOSE:START' => 'START']);

            $ret = null;

            // ショップの場合はマインクラフト側のショップ情報を削除してクローズ
            if($p_param->isShop() === true)
            {
                $shop_browser = $p_param->getTempBuff(['shop']);

                // 退店時のコマンド送信
                $cmd_datas = $p_param->getCommandDataForShopClose($shop_browser['shop']['cid']);
                foreach($cmd_datas as $cmd_data)
                {
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data, $shop_browser['shop']['cid']);
                }

                // マインクラフト側のショップ情報の削除
                $shop_browser['shop']['sta'] = ShopStatusEnum::CLOSE->value;
                $p_param->setTempBuff(['shop' => $shop_browser['shop']]);
                $p_param->setTempBuff(['shop' => null], $shop_browser['shop']['cid']);

                // 受信データを取得
                $w_ret = $p_param->getRecvData();

                // 自身の切断シーケンス開始
                $close_param =
                [
                    // 切断コード
                    'code' => $w_ret['close_code'],
                    // シリアライズ対象データ
                    'data' => $w_ret['data']
                ];
                $p_param->close($close_param);
            }
            else
            {
                $fnc = parent::getCloseStart();
                $ret = $fnc($p_param);
            }

            return $ret;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"PRIVATE"キュー）
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"PRIVATE_RESULT"キュー）
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"USERSEARCH_RESULT"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： SENDING
     * 
     * 処理名：ユーザー名重複時のエラーメッセージ送信中待機
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getUserSearchResultSending()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT USERSEARCH-RESULT:SENDING' => 'START']);

            // ステータス名取得
            $sta = $p_param->getStatusName();

            // 送信中の間継続
            $w_ret = $p_param->isSending();
            if($w_ret === true)
            {
                return $sta;
            }
    
            // 切断
            $p_param->emergencyShutdown();

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"RESPONSE"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトからのレスポンス受信時処理
     * 
     * ※自身の接続へのリクエストに対するレスポンスのみ受け付ける
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getResponseStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'START']);

            $rcv = $p_param->getRecvData();
            $w_ret = $p_param->getAwaitResponse();
            if($w_ret === null)
            {
                return null;
            }
            if($w_ret['requestId'] === $rcv['data']['header']['requestId'])
            {
                // ユーザー名重複時のレスポンス
                if($w_ret['type'] === 'user-duplication')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'USER-DUPLICATION']);

                    // 例外を投げて切断する
                    //-マインクラフトが切断フレームに対応するまでの仮実装
                    $p_param->emergencyShutdown();
                    // $close_param =
                    // [
                    //     // 切断コード
                    //     'code' => ParameterForMinecraft::CHAT_DUPLICATION_CLOSE_CODE,
                    //     // シリアライズ対象データ
                    //     'data' =>
                    //     [
                    //         'datetime' => date(ParameterForMinecraft::CHAT_DATETIME_FORMAT)
                    //     ]
                    // ];
                    // $p_param->close($close_param);
                }
                else
                // ユーザー名なし時のレスポンス
                if($w_ret['type'] === 'no-user')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'NO-USER']);

                    // 例外を投げて切断する
                    //-マインクラフトが切断フレームに対応するまでの仮実装
                    $p_param->emergencyShutdown();
                    // $close_param =
                    // [
                    //     // 切断コード
                    //     'code' => ParameterForMinecraft::CHAT_NO_USER_CLOSE_CODE,
                    //     // シリアライズ対象データ
                    //     'data' =>
                    //     [
                    //         'datetime' => date(ParameterForMinecraft::CHAT_DATETIME_FORMAT)
                    //     ]
                    // ];
                    // $p_param->close($close_param);
                }
                else
                if($w_ret['type'] === 'exit')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'EXIT']);

                    // 例外を投げて切断する
                    //-マインクラフトが切断フレームに対応するまでの仮実装
                    $p_param->emergencyShutdown();
                    // $close_param =
                    // [
                    //     // 切断コード
                    //     'code' => ParameterForMinecraft::CHAT_MINECRAFT_CLOSE_CODE,
                    //     // シリアライズ対象データ
                    //     'data' =>
                    //     [
                    //         // 切断時パラメータ（現在日時）
                    //         'datetime' => date(ParameterForMinecraft::CHAT_DATETIME_FORMAT)
                    //     ]
                    // ];
    
                    // // 自身を切断
                    // $p_param->close($close_param);
                }
                else
                if($w_ret['type'] === 'execute-command')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'EXECUTE-COMMAND']);

                    // レスポンス送信先接続IDの取得
                    $cid = $p_param->getTempBuff(['response-cid']);

                    // コマンド送信
                    $user = $p_param->getUserName();
                    $result = false;
                    $response = ParameterForMinecraft::CHAT_NO_RESPONSE_MESSAGE;
                    if(isset($rcv['data']['body']['statusMessage']))
                    {
                        $response = $rcv['data']['body']['statusMessage'];
                    }
                    if($rcv['data']['body']['statusCode'] === 0)
                    {
                        $result = true;
                    }
                    $data =
                    [
                        'data' =>
                        [
                            'cmd' => 'execute-command',
                            'user' => $user,
                            'response' => $response,
                            'result' => $result
                        ]
                    ];
                    $p_param->setSendStack($data, $cid['response-cid']);
                }
                else
                if($w_ret['type'] === 'thunder-sword-revised-for-sweep')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'THUNDER-SWORD-REVISED']);

                    // エフェクトアイコン表示解除のコマンド送信
                    $cmd_data = $p_param->getCommandDataForEffectIconReset('');
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }
                else
                if($w_ret['type'] === 'entrance')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'ENTRANCE']);

                    if($rcv['data']['body']['statusCode'] === 0)
                    {
                        $name = $rcv['data']['body']['recipient'][0];
                        $p_param->setTempBuff(['minecraft-name' => $name]);
                    }
                }
                else
                if($w_ret['type'] === 'hayabusa-sword-restore')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'HAYABUSA-SWORD-RESTORE']);

                    // 剣のリストア成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // コマンド送信
                    $cmd_datas = $p_param->getCommandDataForHayabusaTeleport('');
                    foreach($cmd_datas as $cmd_data)
                    {
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'hayabusa-sword-test')
                {
                    // 検査成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // コマンド送信
                    $cmd_datas = $p_param->getCommandDataForHayabusaAttackTagAdd();
                    foreach($cmd_datas as $cmd_data)
                    {
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'hayabusa-sword-tag-add')
                {
                    // タグ付与成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // コマンド送信
                    $cmd_datas = $p_param->getCommandDataForHayabusaAttackTargetTest();
                    foreach($cmd_datas as $cmd_data)
                    {
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'hayabusa-attack-target-test')
                {
                    $dec = json_decode($rcv['data']['body']['details']);

                    // コマンド送信
                    $cmd_datas = $p_param->getCommandDataForHayabusaAttack(
                        $dec[0]->position->x,
                        $dec[0]->position->y,
                        $dec[0]->position->z,
                        $dec[0]->yRot,
                        1
                    );
                    foreach($cmd_datas as $cmd_data)
                    {
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'get-gamemode')
                {
                    $p_param->logWriter('debug', ['MINECRAFT SHOP_ENTRANCE' => 'RESPONSE']);

                    // 取得成功か
                    if($rcv['data']['body']['statusCode'] === 0)
                    {
                        // 鍵を送信
                        $cmd_data = $p_param->getCommandDataForSendLock();
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);

                        return null;
                    }

                    // マインクラフト側のショップ情報を取得
                    $shop_minecraft = $p_param->getTempBuff(['shop']);

                    // オプションデータ
                    $opts = config('shop.opts');

                    // ゲームモードチェンジ要求
                    $data =
                    [
                        'data' =>
                        [
                            'cmd' => CommandQueueEnumForMinecraft::SHOP_ENTRANCE->value,
                            'result' => -3,
                            'opts' => $opts
                        ]
                    ];
                    $p_param->setSendStack($data, $shop_minecraft['shop']['cid']);
                }
                else
                if($w_ret['type'] === 'change-survival')
                {
                    $p_param->logWriter('debug', ['MINECRAFT SHOP_ENTRANCE' => 'CHANGE-SURVIVAL']);

                    // 取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // マインクラフト側のショップ情報を取得
                    $shop_minecraft = $p_param->getTempBuff(['shop']);

                    // 鍵を送信
                    $cmd_data = $p_param->getCommandDataForSendLock();
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }
                else
                if($w_ret['type'] === 'wallet-get')
                {
                    $p_param->logWriter('debug', ['MINECRAFT SHOP_RELEASE_LOCK' => 'RESPONSE']);

                    // ウォレット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // 入店前の送信
                    $cmd_datas = $p_param->getCommandDataForShopInit();
                    foreach($cmd_datas as $cmd_data)
                    {
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }

                    // ブラウザ側の接続IDを取得
                    $shop = $p_param->getTempBuff(['shop']);
                    $cid_browser = $shop['shop']['cid'];

                    // 所持金の取得
                    $wallet = strrchr($rcv['data']['body']['statusMessage'], ' ');
                    $wallet = substr($wallet, 1, strlen($wallet) - 2);

                    // オプションデータ
                    $opts = config('shop.opts');

                    // 購入可能リスト
                    $buy_list = config('shop.buy_list');

                    // ロック解除をブラウザへ送信
                    $data =
                    [
                        'data' =>
                        [
                            'cmd' => CommandQueueEnumForMinecraft::SHOP_ENTRANCE->value,
                            'result' => true,
                            'user' => $p_param->getUserName(),
                            'wallet' => $wallet,
                            'opts' => $opts,
                            'buy_list' => $buy_list
                        ]
                    ];
                    $p_param->setSendStack($data, $cid_browser);

                    $shop['shop']['sta'] = ShopStatusEnum::OPEN->value;

                    // マインクラフト側のショップステータスを設定
                    $p_param->setTempBuff(['shop' => $shop['shop']]);

                    // ブラウザ側のショップステータスを設定
                    $shop['shop']['cid'] = $p_param->getConnectionId();
                    $p_param->setTempBuff(['shop' => $shop['shop']], $cid_browser);
                }
                else
                if($w_ret['type'] === 'buy-pay')
                {
                    $p_param->logWriter('debug', ['MINECRAFT SHOP_BUY_PAY' => 'RESPONSE']);

                    // ウォレット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // ブラウザ側の接続IDを取得
                    $shop = $p_param->getTempBuff(['shop']);
                    $cid_browser = $shop['shop']['cid'];

                    // 所持金の取得
                    $wallet = strrchr($rcv['data']['body']['statusMessage'], ' ');
                    $wallet = substr($wallet, 1, strlen($wallet) - 2);

                    // 購入可能リスト
                    $buy_list = config('shop.buy_list');

                    // 購入完了をブラウザへ送信
                    $data =
                    [
                        'data' =>
                        [
                            'cmd' => CommandQueueEnumForMinecraft::SHOP_BUY->value,
                            'result' => true,
                            'wallet' => $wallet,
                            'buy_list' => $buy_list
                        ]
                    ];
                    $p_param->setSendStack($data, $cid_browser);

                    $shop['shop']['sta'] = ShopStatusEnum::OPEN->value;

                    // マインクラフト側のショップステータスを設定
                    $p_param->setTempBuff(['shop' => $shop['shop']]);

                    // ブラウザ側のショップステータスを設定
                    $shop['shop']['cid'] = $p_param->getConnectionId();
                    $p_param->setTempBuff(['shop' => $shop['shop']], $cid_browser);
                }
                else
                if($w_ret['type'] === 'shop-sell-release')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'SHOP-SELL-RELEASE']);

                    // 返却成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // 受信データを取得
                    $rcv = $rcv['data'];

                    // マインクラフト側のショップ情報を取得
                    $shop_minecraft = $p_param->getTempBuff(['shop']);

                    // ブラウザ側のショップ情報を取得
                    $shop_browser = $p_param->getTempBuff(['shop'], $shop_minecraft['shop']['cid']);

                    // ブラウザ側のショップ情報から返却されたアイテム情報を削除
                    $shop_browser['shop']['sell_list'][$shop_minecraft['shop']['release_item']]['count']--;
                    if($shop_browser['shop']['sell_list'][$shop_minecraft['shop']['release_item']]['count'] <= 0)
                    {
                        unset($shop_browser['shop']['sell_list'][$shop_minecraft['shop']['release_item']]);
                    }
                    $p_param->setTempBuff(['shop' => $shop_browser['shop']], $shop_minecraft['shop']['cid']);

                    // 登録がない場合はブラウザ側へコマンドを投げて抜ける
                    if(!isset($shop_browser['shop']['sell_list']) || count($shop_browser['shop']['sell_list']) <= 0)
                    {
                        $data =
                        [
                            'data' =>
                            [
                                'cmd' => CommandQueueEnumForMinecraft::SHOP_SELL_RELEASE->value,
                                'result' => true,
                                'sell_list' => []
                            ]
                        ];
                        $p_param->setSendStack($data, $shop_minecraft['shop']['cid']);
                        return null;
                    }

                    // アイテムIDを取得
                    $id = array_key_first($shop_browser['shop']['sell_list']);

                    // マインクラフトへコマンドを送信
                    $cmd_data = $p_param->getCommandDataForRelease($shop_browser['shop']['cid'], $shop_browser['shop']['sell_list'][$id]);
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data, $shop_browser['shop']['cid']);

                    // ショップ情報を設定
                    $shop_minecraft['shop']['release_item'] = $id;
                    $p_param->setTempBuff(['shop' => $shop_minecraft['shop']], $shop_browser['shop']['cid']);
                }
                else
                if($w_ret['type'] === 'sell-paid')
                {
                    $p_param->logWriter('debug', ['MINECRAFT SHOP_SELL' => 'RESPONSE']);

                    // ウォレット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    // マインクラフト側のショップ情報を取得
                    $shop_mainecraft = $p_param->getTempBuff(['shop']);

                    // ブラウザ側のショップ情報を取得
                    $shop_browser = $p_param->getTempBuff(['shop'], $shop_mainecraft['shop']['cid']);

                    // 所持金の取得
                    $wallet = strrchr($rcv['data']['body']['statusMessage'], ' ');
                    $wallet = substr($wallet, 1, strlen($wallet) - 2);

                    // 商品一覧を取得
                    $buy_list = config('shop.buy_list');

                    // 売却完了をブラウザへ送信
                    $data =
                    [
                        'data' =>
                        [
                            'cmd' => CommandQueueEnumForMinecraft::SHOP_SELL->value,
                            'result' => true,
                            'wallet' => $wallet,
                            'sell_list' => $shop_browser['shop']['sell_list'],
                            'buy_list' => $buy_list
                        ]
                    ];
                    $p_param->setSendStack($data, $shop_mainecraft['shop']['cid']);
                }
                else
                if($w_ret['type'] === 'wind-control-rod-summon')
                {
                    $p_param->logWriter('debug', ['MINECRAFT WIND-CONTROL-ROD:QUERYTARGET' => 'SUMMON']);

                    // ターゲット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    $cmd_datas = [];
                    $name = $p_param->getTempBuff(['minecraft-name']);

                    $cmd = "summon customize:wind_control_projectile ~ ~3 ~";
                    $cmd_datas[] = $p_param->getCommandData($cmd, null);

                    $cmd = "tag @e[type=customize:wind_control_projectile,tag=,c=1] add wind_control_rod_{$name['minecraft-name']}";
                    $cmd_datas[] = $p_param->getCommandData($cmd, null);

                    foreach($cmd_datas as $cmd_data)
                    {
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'wind-control-rod-variant0')
                {
                    $p_param->logWriter('debug', ['MINECRAFT WIND-CONTROL-ROD:QUERYTARGET' => 'VARIANT-0']);

                    // ターゲット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    $name = $p_param->getTempBuff(['minecraft-name']);
                    $yrot = $p_param->getTempBuff(['yrot']);
                    $details = json_decode($rcv['data']['body']['details'], true);

                    foreach($details as $detail)
                    {
                        // 座標退避
                        $w_x = $detail['position']['x'];
                        $w_y = $detail['position']['y'];
                        $w_z = $detail['position']['z'];
                        $w_yrot = $yrot['yrot'];

                        // ウインドチャージ座標の計算
                        $p_param->getRelativeCoordinates($w_x, $w_y, $w_z, $w_yrot, 1.0);

                        $w_x = $detail['position']['x'] + $w_x;
                        $w_z = $detail['position']['z'] + $w_z;
                        $cmd = "tp @e[tag=wind_control_rod_{$name['minecraft-name']},x={$detail['position']['x']},y={$detail['position']['y']},z={$detail['position']['z']},c=1] {$w_x} {$detail['position']['y']} {$w_z}";
                        $cmd_data = $p_param->getCommandData($cmd, null);
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'wind-control-rod-variant1')
                {
                    $p_param->logWriter('debug', ['MINECRAFT WIND-CONTROL-ROD:QUERYTARGET' => 'VARIANT-1']);

                    // ターゲット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    $name = $p_param->getTempBuff(['minecraft-name']);
                    $details = json_decode($rcv['data']['body']['details'], true);

                    foreach($details as $detail)
                    {
                        // 座標退避
                        $w_x = $detail['position']['x'];
                        $w_y = $detail['position']['y'] - 1;
                        $w_z = $detail['position']['z'];

                        $cmd = "tp @e[tag=wind_control_rod_{$name['minecraft-name']},x={$detail['position']['x']},y={$detail['position']['y']},z={$detail['position']['z']},c=1] {$w_x} {$w_y} {$w_z}";
                        $cmd_data = $p_param->getCommandData($cmd, null);
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                else
                if($w_ret['type'] === 'wind-control-rod-variant6')
                {
                    $p_param->logWriter('debug', ['MINECRAFT WIND-CONTROL-ROD:QUERYTARGET' => 'VARIANT-6']);

                    // ターゲット取得成功か
                    if($rcv['data']['body']['statusCode'] !== 0)
                    {
                        return null;
                    }

                    $name = $p_param->getTempBuff(['minecraft-name']);
                    $details = json_decode($rcv['data']['body']['details'], true);

                    foreach($details as $detail)
                    {
                        // 座標退避
                        $w_x = $detail['position']['x'];
                        $w_y = $detail['position']['y'] + 1;
                        $w_z = $detail['position']['z'];

                        $cmd = "tp @e[tag=wind_control_rod_{$name['minecraft-name']},x={$detail['position']['x']},y={$detail['position']['y']},z={$detail['position']['z']},c=1] {$w_x} {$w_y} {$w_z}";
                        $cmd_data = $p_param->getCommandData($cmd, null);
                        $data =
                        [
                            'data' => $cmd_data
                        ];
                        $p_param->setSendStack($data);
                    }
                }
                // 以降の分岐はリザーブ用
                else
                if($w_ret['type'] === 'forced-close')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'FORCED-CLOSE']);
                }
                else
                if($w_ret['type'] === 'message')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'MESSAGE']);
                }
                else
                if($w_ret['type'] === 'close')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'CLOSE']);
                }
                else
                if($w_ret['type'] === 'private')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'PRIVATE']);
                }
                else
                if($w_ret['type'] === 'private-result')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'PRIVATE-RESULT']);
                }
                else
                if($w_ret['type'] === 'private-reply')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'PRIVATE-REPLY']);
                }
                else
                if($w_ret['type'] === 'usersearch-result')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'USERSEARCH-RESULT']);
                }
                else
                if($w_ret['type'] === 'no-comment')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'NO-COMMENT']);
                }
            }

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"ITEM_USED"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトからのItemUsedイベント発生時処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getItemUsedStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT ITEM_USED:START' => 'START']);

            // 受信データの取得
            $rcv = $p_param->getRecvData();

            // 繰風弾の杖
            if($rcv['data']['body']['item']['id'] === 'wind_control_rod')
            {
                // コマンド送信
                $cmd_datas = $p_param->getCommandDataForWindControlRodItemUsed(
                    $rcv['data']['body']['player']['variant'],
                    $rcv['data']['body']['player']['yRot']
                );
                foreach($cmd_datas as $cmd_data)
                {
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }

                return null;
            }

            // はやぶさの剣
            if($rcv['data']['body']['item']['id'] === 'hayabusa_sword')
            {
                // コマンド送信
                $cmd_datas = $p_param->getCommandDataForHayabusaSwordUse(
                    $rcv['data']['body']['player']['position']['x'],
                    $rcv['data']['body']['player']['position']['y'],
                    $rcv['data']['body']['player']['position']['z'],
                    $rcv['data']['body']['player']['yRot']
                );
                foreach($cmd_datas as $cmd_data)
                {
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }

                return null;
            }

            // 不動の杖
            if($rcv['data']['body']['item']['id'] === 'immovable_rod')
            {
                // コマンド送信（immovableエンティティのkill）
                $cmd_data = $p_param->getCommandDataForKillImmovable($rcv['data']['body']['player']['name']);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                // コマンド送信（足止め発動）
                $cmd_data = $p_param->getCommandDataForImmovable($rcv['data']['body']['player']['name']);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                // コマンド送信（タグの付与）
                $cmd_data = $p_param->getCommandDataForTagImmovable($rcv['data']['body']['player']['name']);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                return null;
            }

            // 不動の魔石
            if($rcv['data']['body']['item']['id'] === 'immovable_stone')
            {
                // コマンド送信
                $cmd_datas = $p_param->getCommandDataForImmovableStone();
                foreach($cmd_datas as $cmd_data)
                {
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }

                // 階段チェア着席フラグの設定
                $p_param->setTempBuff(['chair_flag' => true]);

                return null;
            }

            // 浮遊の羽
            if($rcv['data']['body']['item']['id'] === 'floating_feather')
            {
                // コマンド送信
                $cmd_datas = $p_param->getCommandDataForFloatingFeather();
                foreach($cmd_datas as $cmd_data)
                {
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }

                return null;
            }

            // ディスパッチャー強制
            $p_param->setForcedDispatcher(true);

            // 弓タイプの設定
            $p_param->setTempBuff(['bow_type' => $rcv['data']['body']['item']['aux']]);

            // 「いなずまの弓」「いなずまの剣（改）」以外
            if(
                $rcv['data']['body']['item']['aux'] !== 401
            &&  $rcv['data']['body']['item']['id'] !== 'thunder_sword'
            &&  $rcv['data']['body']['item']['id'] !== 'thunder_sword_revised'
            )
            {
                return CommandStatusEnumForMinecraft::ARROW->value;
            }

            $x = (float)$rcv['data']['body']['player']['position']['x'];
            $y = (float)$rcv['data']['body']['player']['position']['y'];
            $x = (float)$rcv['data']['body']['player']['position']['z'];
            $y_rot = (float)$rcv['data']['body']['player']['yRot'];
            $y_rot_abs = abs($y_rot);

            // Z座標の計算
            $z = cos(deg2rad($y_rot_abs)) * 5;

            // X座標の計算
            $x = sin(deg2rad($y_rot_abs)) * 5;
            if($y_rot > 0)
            {
                $x = -$x;
            }

            // コマンド送信
            $cmd_data = $p_param->getCommandDataForSummonThunder($x, 0, $z, $rcv['data']['body']['item']['id']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            if($rcv['data']['body']['item']['aux'] === 401)
            {
                return CommandStatusEnumForMinecraft::ARROW->value;
            }
            return null;
        };
    }

    /**
     * ステータス名： ARROW
     * 
     * 処理名：マインクラフトからの矢の使用イベント発生時処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getItemUsedArrow()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT ITEM_USED:ARROW' => 'START']);

            $sta = $p_param->getStatusName();

            // 受信データの取得
            $rcv = $p_param->getRecvData();
            if($rcv === null)
            {
                // ディスパッチャー強制
                $p_param->setForcedDispatcher(true);
                return $sta;
            }

            // ItemUsed以外のイベントの判定
            if(!(isset($rcv['data']['header']['eventName']) && $rcv['data']['header']['eventName'] === 'ItemUsed'))
            {
                return $sta;
            }

            // 弓タイプの取得
            $bow_type = $p_param->getTempBuff(['bow_type']);

            // スタンドの召喚
            if($bow_type['bow_type'] === 461 && $rcv['data']['body']['item']['aux'] === 0)
            {
                // コマンド送信（スタンド召喚）
                $cmd_data = $p_param->getCommandDataForStandSummon($rcv['data']['body']['player']['name']);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);
                return null;
            }
            // スタンドによる攻撃
            if($rcv['data']['body']['item']['aux'] === 471)
            {
                // 相対座標の取得
                $x = $rcv['data']['body']['player']['position']['x'];
                $y = $rcv['data']['body']['player']['position']['y'];
                $z = $rcv['data']['body']['player']['position']['z'];
                $yrot = $rcv['data']['body']['player']['yRot'];
                $p_param->getRelativeCoordinates($x, $y, $z, $yrot, 5);

                // コマンド送信（座標計算の矢をスポーン）
                $cmd_data = $p_param->getCommandDataForStandArrowSpawn($rcv['data']['body']['player']['name'], $x, $y, $z);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                // コマンド送信（矢へのタグの付与）
                $cmd_data = $p_param->getCommandDataForStandArrowTag($rcv['data']['body']['player']['name'], $x, $y, $z);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                // コマンド送信（攻撃相手へのタグ付与）
                $cmd_data = $p_param->getCommandDataForStandAttackTag($rcv['data']['body']['player']['name']);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                // コマンド送信（スタンド攻撃）
                $p_param->sendCommandDataForStandAttack();
                return null;
            }
            else
            // 通常の矢の場合
            if($bow_type['bow_type'] !== 451 && $rcv['data']['body']['item']['aux'] === 0)
            {
                // コマンド送信
                $cmd_data = $p_param->getCommandDataForArrowTagNormal($rcv['data']['body']['player']['name']);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);
                return null;
            }

            // コマンド送信
            $cmd_data = $p_param->getCommandDataForArrowTagCheat($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 機雷の弓で放たれた矢の場合
            if($rcv['data']['body']['item']['aux'] === 0)
            {
                return null;
            }

            // コマンドデータの取得
            if($rcv['data']['body']['item']['aux'] === 411)
            {
                // いなずまの矢
                $cmd_data = $p_param->getCommandDataForThunderArrow($rcv['data']['body']['player']['name']);
            }
            else
            if($rcv['data']['body']['item']['aux'] === 421)
            {
                // はかいの矢
                $cmd_data = $p_param->getCommandDataForExplodeArrow($rcv['data']['body']['player']['name']);
            }

            // コマンド送信
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"RESPONSE_STAND_ATTACK"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトからのレスポンス受信時処理
     * 
     * ※自身の接続へのリクエストに対するレスポンスのみ受け付ける
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getResponseStandAttackStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT RESPONSE_STAND_ATTACK:START' => 'START']);

            $rcv = $p_param->getRecvData();
            $w_ret = $p_param->getAwaitResponseForCustomize('stand-attack');
            if($w_ret === null)
            {
                return null;
            }
            if($w_ret === $rcv['data']['header']['requestId'])
            {
                // ユーザー名重複時のレスポンス
                if($rcv['data']['body']['statusMessage'] === 'セレクターに合う対象がありません')
                {
                    $p_param->setAwaitResponseForCustomize('stand-attack', null);
                    return null;
                }
                else
                {
                    // コマンド送信（スタンド攻撃）
                    $p_param->sendCommandDataForStandAttack();
                }
            }

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"PLAYER_TRAVELLED"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトからのPlayerTravelledイベント発生時処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getMinecraftPlayerTravelledStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT PLAYER_TRAVELLED:START' => 'START']);

            // インターバルタイムの検査
            $now_time = microtime(true);
            $last_time = $p_param->getTempBuff(['double_jump_time']);
            if($last_time !== null)
            {
                $interval = config('minecraft.double_jump.interval');
                if(($now_time - $last_time['double_jump_time']) < $interval)
                {
                    return null;
                }
            }

            // 受信データの取得
            $rcv = $p_param->getRecvData();

            // コマンド送信
            $cmd_data = $p_param->getCommandDataForDoubleJump($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            $p_param->setTempBuff(['double_jump_time' => microtime(true)]);

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"PLAYER_DASH"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトからのダッシュイベント発生時処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getMinecraftPlayerDashStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT PLAYER_DASH:START' => 'START']);

            // 受信データの取得
            $rcv = $p_param->getRecvData();

            // 「スウィープロッド（範囲攻撃）」実行のコマンド送信
            $cmd_data = $p_param->getCommandDataForSweepRod($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 「いなずまの剣改」実行のコマンド送信
            $cmd_data = $p_param->getCommandDataForThunderSwordRevised($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 「不動の杖」実行のコマンド送信
            $cmd_data = $p_param->getCommandDataForFloatingByImmovableRod($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 「はやぶさの剣」コマンド送信（持ち物検査）
            $cmd_data = $p_param->getCommandDataForHayabusaSwordMainhandTest($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 繰風弾発現用実行のコマンド送信
            $cmd_datas = $p_param->getCommandDataForWindControlRodDashAndSneak();
            foreach($cmd_datas as $cmd_data)
            {
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);
            }

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"EXECUTE_COMMAND"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトのコマンド実行処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getMinecraftExecuteCommandStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT EXECUTE_COMMAND:START' => 'START']);

            // 受信データの取得
            $rcv = $p_param->getRecvData();

            $result = true;
            $comments = [];

            // 宛先ユーザー名のチェック
            $user = $p_param->chopHiddenCharacters($rcv['data']['user']);
            if($user == '')
            {
                $result = false;
                $comments[] = $p_param->getOption('no_user_comment');
            }

            // コマンド入力のチェック
            $command = $p_param->chopHiddenCharacters($rcv['data']['command']);
            if($command == '')
            {
                $result = false;
                $comments[] = ParameterForMinecraft::CHAT_NO_COMMAND;
            }

            if($result === false)
            {
                // エラーコメントの生成
                $comment_join = '';
                foreach($comments as $comment)
                {
                    if(strlen($comment_join) > 0)
                    {
                        $comment_join .= '<br />';
                    }
                    $comment_join .= $comment;
                }
                // 送信データ作成
                $data =
                [
                    'data' =>
                    [
                        'cmd' => 'execute-command',
                        'user' => $user,
                        'response' => $comment_join,
                        'result' => false
                    ]
                ];
                $p_param->setSendStack($data);
                return null;
            }

            // 相手先の接続ID取得
            $cid = $p_param->getConnectionIdByUserName($rcv['data']['user']);

            // 相手先が見つからない
            if($cid === null)
            {
                // 送信データ作成
                $comment = ParameterForWebsocket::CHAT_PRIVATE_NG;
                $comment = str_replace(':name', $rcv['data']['user'], $comment);
                $data =
                [
                    'data' =>
                    [
                        'cmd' => 'execute-command',
                        'user' => $user,
                        'response' => $comment,
                        'result' => false
                    ]
                ];
                $p_param->setSendStack($data);
                return null;
            }

            // コマンド送信
            $cmd_data = $p_param->getCommandData($rcv['data']['command'], 'execute-command', $cid);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data, $cid);

            // コマンド送信先の相手にレスポンス受信する接続IDを設定
            $cid_self = $p_param->getConnectionId();
            $p_param->setTempBuff(['response-cid' => $cid_self], $cid);

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"CHAIR"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトからのスニークイベント発生時処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getMinecraftChairStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT CHAIR:START' => 'START']);

            $rcv = $p_param->getRecvData();
            $p_param->setTempBuff(['chair_yrot' => $rcv['data']['body']['player']['yRot']]);

            // 階段ブロックIDのインデックスを取得
            $idx = $p_param->getTempBuff(['stairs_idx']);

            // 階段ブロックのリストを取得
            $ids = config('minecraft.stairs_ids');

            // コマンド送信
            $cmd_data = $p_param->getCommandDataForStairsTest($ids[$idx['stairs_idx']]);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // ディスパッチャー強制
            $p_param->setForcedDispatcher(true);

            return CommandStatusEnumForMinecraft::CHAIR_RESPONSE->value;
        };
    }

    /**
     * ステータス名： CHAIR_RESPONSE
     * 
     * 処理名：マインクラフトからのブロック検査レスポンス処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getMinecraftChairResponse()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT CHAIR:CHAIR_RESPONSE' => 'START']);

            // 現在のステータスを取得
            $sta = $p_param->getStatusName();

            // 受信データの取得
            $rcv = $p_param->getRecvData();
            if($rcv === null)
            {
                // ディスパッチャー強制
                $p_param->setForcedDispatcher(true);
                return $sta;
            }

            // ブロック検査の結果ではない場合
            if(!isset($rcv['data']['body']['matches']))
            {
                // ディスパッチャー強制
                $p_param->setForcedDispatcher(true);
                return $sta;
            }

            // 階段ブロックが不一致の場合
            if($rcv['data']['body']['matches'] === false)
            {
                // 階段ブロックIDのインデックスを取得
                $idx = $p_param->getTempBuff(['stairs_idx']);

                // 一致するブロックが見つからなかった
                if($idx['stairs_idx'] <= 0)
                {
                    return null;
                }
                $idx['stairs_idx']--;

                // 階段ブロックのリストを取得
                $ids = config('minecraft.stairs_ids');

                // コマンド送信
                $cmd_data = $p_param->getCommandDataForStairsTest($ids[$idx['stairs_idx']]);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);

                // 階段ブロックIDの次のインデックスを設定
                $p_param->setTempBuff(['stairs_idx' => $idx['stairs_idx']]);

                return $sta;
            }

            // 階段ブロックの座標を取得
            $x = $rcv['data']['body']['position']['x'];
            $y = $rcv['data']['body']['position']['y'];
            $z = $rcv['data']['body']['position']['z'];

            // コマンド送信（sitエンティティのデスポーン）
            $cmd_data = $p_param->getCommandDataForDespawnSit($x, $y, $z);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // コマンド送信（体力ゲージ非表示）
            $cmd_data = $p_param->getCommandDataForGaugeHide();
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // コマンド送信（sitエンティティの召喚）
            $yrot = $p_param->getTempBuff(['chair_yrot']);
            $cmd_data = $p_param->getCommandDataForSummonSit($x, $y, $z, $yrot['chair_yrot']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // コマンド送信（プレイヤーの搭乗）
            $cmd_data = $p_param->getCommandDataForRidePlayer($x, $y, $z);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 階段ブロックIDのインデックスクリア
            $p_param->setTempBuff(['stairs_idx' => null]);

            // 階段チェア着席フラグの設定
            $p_param->setTempBuff(['chair_flag' => true]);

            return null;
        };
    }


    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"CHAIR_STANDUP"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：マインクラフトから階段チェアからの起立イベント発生時処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getMinecraftChairStandupStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT CHAIR_STANDUP:START' => 'START']);

            // コマンド送信（体力ゲージ表示）
            $cmd_data = $p_param->getCommandDataForGaugeShow();
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            // 階段チェア着席フラグのクリア
            $p_param->setTempBuff(['chair_flag' => false]);

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_ENTRANCE"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOP入室処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopEntranceStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT SHOP_ENTRANCE:START' => 'START']);

            // 受信データを取得
            $rcv = $p_param->getRecvData();
            $res = $rcv['data'];

            // ユーザー名MAX長調整
            $res['user'] = mb_substr($res['user'], 0, ParameterForWebsocket::CHAT_USER_NAME_MAX_LENGTH);

            // ユーザー名の存在をチェック
            $res['user'] = $p_param->chopHiddenCharacters($res['user']);
            if($res['user'] == '')
            {
                // 自身を切断
                $p_param->closeNoUser();
                return null;
            }

            // HTML変換
            $res['user'] = htmlspecialchars($res['user']);

            // 指定ユーザーの接続IDを取得
            $cid_minecraft = $p_param->getConnectionIdByUserName($res['user']);

            // マインクラフトの接続IDとステータスを設定
            $p_param->setTempBuff(
                [
                    'shop' =>
                    [
                        'cid' => $cid_minecraft,
                        'sta' => ShopStatusEnum::ENTRANCE->value
                    ]
                ]
            );

            // 指定ユーザーの接続IDが見つからない
            if($cid_minecraft === null)
            {
                $opts = config('shop.opts');
                $data =
                [
                    'data' =>
                    [
                        'cmd' => CommandQueueEnumForMinecraft::SHOP_ENTRANCE->value,
                        'result' => -1,
                        'opts' => $opts
                    ]
                ];
                // 返信
                $p_param->setSendStack($data);
                return null;
            }

            // マインクラフト接続かどうか
            $minecraft = $p_param->isMinecraft($cid_minecraft);
            if($minecraft === false)
            {
                $opts = config('shop.opts');
                $data =
                [
                    'data' =>
                    [
                        'cmd' => CommandQueueEnumForMinecraft::SHOP_ENTRANCE->value,
                        'result' => -2,
                        'opts' => $opts
                    ]
                ];
                // 返信
                $p_param->setSendStack($data);
                return null;
            }

            // マインクラフト側の接続IDを設定
            $cid = $p_param->getConnectionId();
            $p_param->setTempBuff(
                [
                    'shop' =>
                    [
                        'cid' => $cid,
                        'sta' => ShopStatusEnum::ENTRANCE->value
                    ]
                ]
                , $cid_minecraft
            );

            // ゲームモード取得コマンドを送信
            $cmd_data = $p_param->getCommandDataForGetGamemode($cid_minecraft);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data, $cid_minecraft);

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_RELEASE_LOCK"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOPロック解除処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopReleaseLockStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            // 受信データを取得
            $rcv = $p_param->getRecvData();

            if($rcv['data']['body']['item']['id'] === 'shop_lock')
            {
                // ウォレット取得のコマンド実行
                $cmd_datas = $p_param->getCommandDataForGetWallet();
                foreach($cmd_datas as $cmd_data)
                {
                    $data =
                    [
                        'data' => $cmd_data
                    ];
                    $p_param->setSendStack($data);
                }
            }

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_SURVIVAL_CHANGE"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOPサバイバル変更要求処理
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopSurvivalChangeStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $shop = $p_param->getTempBuff(['shop']);

            // ウォレット取得のコマンド実行
            $cmd_data = $p_param->getCommandDataForChangeSurvival($shop['shop']['cid']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data, $shop['shop']['cid']);

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_BUY"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOP購入処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopBuyStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT SHOP_BUY:START' => 'START']);

            // 受信データを取得
            $rcv = $p_param->getRecvData();
            $rcv = $rcv['data'];

            // マインクラフトの接続IDを取得
            $shop = $p_param->getTempBuff(['shop']);

            // マインクラフトへコマンドを送信
            $cmd_datas = $p_param->getCommandDataForBuy($shop['shop']['cid'], $rcv['item']);
            foreach($cmd_datas as $cmd_data)
            {
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data, $shop['shop']['cid']);
            }

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_SELL_ENTRY"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOPへの売却登録処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopSellEntryStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT SHOP_SELL_ENTRY:START' => 'START']);

            // 受信データを取得
            $rcv = $p_param->getRecvData();
            $rcv = $rcv['data'];

            // 売却可能商品か？
            $item = $rcv['body']['item']['id'];
            $cnf_sell = config("shop.sell_list.{$item}");
            if($cnf_sell === null)
            {
                return null;
            }

            // マインクラフト側ショップ情報の取得
            $shop_minecraft = $p_param->getTempBuff(['shop']);

            // ブラウザ側ショップ情報の取得
            $shop_browser = $p_param->getTempBuff(['shop'], $shop_minecraft['shop']['cid']);

            // 売却リストへの登録
            if(isset($shop_browser['shop']['sell_list'][$cnf_sell['id']]))
            {
                $count = 1;
                $count += $shop_browser['shop']['sell_list'][$cnf_sell['id']]['count'];
                $shop_browser['shop']['sell_list'][$cnf_sell['id']]['count'] = $count;
                $shop_browser['shop']['sell_list'][$cnf_sell['id']]['price'] = $cnf_sell['price'] * $count;
            }
            else
            {
                $shop_browser['shop']['sell_list'][$cnf_sell['id']] = $cnf_sell;
                $shop_browser['shop']['sell_list'][$cnf_sell['id']]['count'] = 1;
            }

            // ブラウザへコマンド送信
            $data =
            [
                'data' =>
                [
                    'cmd' => 'shop-sell-entry',
                    'sell_list' => $shop_browser['shop']['sell_list']
                ]
            ];
            $p_param->setSendStack($data, $shop_minecraft['shop']['cid']);

            if($cnf_sell['id'] === 'wind_control_rod')
            {
                $cmd = 'clear @s[hasitem={item=customize:wind_control_rod,location=slot.weapon.mainhand}] customize:wind_control_rod 0 1';
                $cmd_data = $p_param->getCommandData($cmd);
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);
            }

            // ショップ情報の設定
            $p_param->setTempBuff(['shop' => $shop_browser['shop']], $shop_minecraft['shop']['cid']);

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_SELL_RELEASE"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOPからの返却処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopSellReleaseStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT SHOP_SELL_RELEASE:START' => 'START']);

            // 受信データを取得
            $rcv = $p_param->getRecvData();
            $rcv = $rcv['data'];

            // ショップ情報を取得
            $shop = $p_param->getTempBuff(['shop']);

            // 登録がない場合は抜ける
            if(!isset($shop['shop']['sell_list']) || count($shop['shop']['sell_list']) <= 0)
            {
                return null;
            }

            // アイテムIDを取得
            $id = array_key_first($shop['shop']['sell_list']);

            // マインクラフトへコマンドを送信
            $cmd_data = $p_param->getCommandDataForRelease($shop['shop']['cid'], $shop['shop']['sell_list'][$id]);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data, $shop['shop']['cid']);

            // ショップ情報をマインクラフト側へ設定
            $shop_minecraft = $p_param->getTempBuff(['shop'], $shop['shop']['cid']);
            $shop_minecraft['shop']['release_item'] = $id;
            $p_param->setTempBuff(['shop' => $shop_minecraft['shop']], $shop['shop']['cid']);

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"SHOP_SELL"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：SHOPからの売却処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getShopSellStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT SHOP_SELL:START' => 'START']);

            // 受信データを取得
            $rcv = $p_param->getRecvData();
            $rcv = $rcv['data'];

            // ショップ情報を取得
            $shop = $p_param->getTempBuff(['shop']);

            $p_param->logWriter('debug', ['MINECRAFT SHOP_SELL:SHOP INFO' => print_r($shop, true)]);

            // 登録がない場合は抜ける
            if(!isset($shop['shop']['sell_list']) || count($shop['shop']['sell_list']) <= 0)
            {
                return null;
            }

            $price = 0;
            foreach($shop['shop']['sell_list'] as $item)
            {
                $price += $item['price'];
            }

            // マインクラフトへコマンドを送信
            $cmd_data = $p_param->getCommandDataForSell($shop['shop']['cid'], $price);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data, $shop['shop']['cid']);

            // 売却一覧をクリア
            $shop['shop']['sell_list'] = [];
            $p_param->setTempBuff(['shop' => $shop['shop']]);

            return null;
        };
    }

    //--------------------------------------------------------------------------
    // 以降はステータスUNITの定義（"WIND_CONTROL"キュー）
    //--------------------------------------------------------------------------

    /**
     * ステータス名： START
     * 
     * 処理名：繰風弾（上昇）処理の処理開始
     * 
     * @param ParameterForMinecraft $p_param UNITパラメータ
     * @return ?string 遷移先のステータス名
     */
    protected function getWindControlUpStart()
    {
        return function(ParameterForMinecraft $p_param): ?string
        {
            $p_param->logWriter('debug', ['MINECRAFT WIND_CONTROL_UP:START' => 'START']);

            // 受信データを取得
            $rcv = $p_param->getRecvData();

            // コマンド送信
            $cmd_datas = $p_param->getCommandDataForWindControlRodItemUsed(
                $rcv['data']['body']['player']['variant'],
                null
            );
            foreach($cmd_datas as $cmd_data)
            {
                $data =
                [
                    'data' => $cmd_data
                ];
                $p_param->setSendStack($data);
            }

            return null;
        };
    }
}
