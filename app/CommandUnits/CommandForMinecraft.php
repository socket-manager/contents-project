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
        CommandQueueEnumForMinecraft::EXECUTE_COMMAND->value       // コマンド実行のキュー
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
                // 以降の分岐はリザーブ用
                else
                if($w_ret['type'] === 'forced-close')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'FORCED-CLOSE']);
                }
                else
                if($w_ret['type'] === 'entrance')
                {
                    $p_param->logWriter('debug', ['MINECRAFT RESPONSE:START' => 'ENTRANCE']);
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

            // ディスパッチャー強制
            $p_param->setForcedDispatcher(true);

            // 弓タイプの設定
            $p_param->setTempBuff(['bow_type' => $rcv['data']['body']['item']['aux']]);

            // いなずまの弓以外
            if($rcv['data']['body']['item']['aux'] !== 401)
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
            $cmd_data = $p_param->getCommandDataForThunderBow($x, 0, $z);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

            return CommandStatusEnumForMinecraft::ARROW->value;
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

            // コマンド送信
            $cmd_data = $p_param->getCommandDataForSweepRod($rcv['data']['body']['player']['name']);
            $data =
            [
                'data' => $cmd_data
            ];
            $p_param->setSendStack($data);

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
}
