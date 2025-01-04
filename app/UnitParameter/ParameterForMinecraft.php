<?php
/**
 * UNITパラメータクラスのファイル
 * 
 * マインクラフト版
 */

namespace App\UnitParameter;


use App\CommandUnits\CommandQueueEnumForMinecraft;
use App\CommandUnits\CommandStatusEnumForMinecraft;

/**
 * UNITパラメータクラス
 * 
 * ParameterForWebsocketクラスをオーバーライドしてマインクラフト版として利用
 */
class ParameterForMinecraft extends ParameterForWebsocket
{
    //--------------------------------------------------------------------------
    // 定数（first byte）
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // 定数（second byte）
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // 定数（切断コード）
    //--------------------------------------------------------------------------

    /**
     * マインクラフトの切断コード
     */
    public const CHAT_MINECRAFT_CLOSE_CODE = 3020;


    //--------------------------------------------------------------------------
    // 定数（その他）
    //--------------------------------------------------------------------------

    /**
     * バリアント（variant）マスク - ステータス
     * 
     * @var int しゃがむ
     */
    public const MASK_VARIANT_SQUAT = 0x01;

    /**
     * バリアント（variant）マスク - スニーク
     * 
     * @var int スニーク
     */
    public const MASK_VARIANT_SNEAKING = 0x02;

    /**
     * バリアント（variant）マスク - アイテム
     * 
     * @var int 繰風弾の杖
     */
    public const MASK_VARIANT_WIND_CONTROL_ROD = 0x04;

    /**
     * バリアント（variant）マスク - アイテム
     * 
     * @var int ファンネルユニット
     */
    public const MASK_VARIANT_FUNNEL_UNIT = 0x08;

    /**
     * バリアント（variant）マスク - アイテム
     * 
     * @var int 風のつえ改
     */
    public const MASK_VARIANT_WIND_ROD_REVISED = 0x10;

    /**
     * 運営サイドのユーザー名
     */
    public const CHAT_ADMIN_USER = '運営チーム';

    /**
     * コマンド入力なし
     */
    public const CHAT_NO_COMMAND = 'コマンドを入力してください';

    /**
     * レスポンスメッセージなし
     */
    public const CHAT_NO_RESPONSE_MESSAGE = 'メッセージはありません';


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
     * @param bool $p_tls TLSフラグ
     */
    public function __construct(bool $p_tls = null)
    {
        parent::__construct($p_tls);
    }


    //--------------------------------------------------------------------------
    // マインクラフト専用
    //--------------------------------------------------------------------------

    /**
     * 自身の接続がマインクラフト接続かどうかを検査
     * 
     * @param string $p_cid 接続ID
     * @return bool true（マインクラフト接続） or false（マインクラフト接続以外）
     */
    public function isMinecraft(string $p_cid = null)
    {
        $cid = null;
        if($p_cid !== null)
        {
            $cid = $p_cid;
        }

        $ret = true;
        $hdrs = $this->getHeaders($cid);
        if(isset($hdrs['User-Agent']))
        {
            $ret = false;
        }
        return $ret;
    }

    /**
     * 自身の接続がブラウザからのショップ接続かどうかを検査
     * 
     * @param string $p_cid 接続ID
     * @return bool true（ショップ接続） or false（ショップ接続以外）
     */
    public function isShop(string $p_cid = null)
    {
        $cid = null;
        if($p_cid !== null)
        {
            $cid = $p_cid;
        }

        // マインクラフト接続の場合は抜ける
        if($this->isMinecraft($cid) === true)
        {
            return false;
        }

        // ショップ情報があるか
        $shop = $this->getTempBuff(['shop'], $cid);
        if($shop === null)
        {
            return false;
        }

        return true;
    }

    /**
     * UUIDv4の取得
     * 
     * @return string UUID(V4)
     */
    public function getUuidv4()
    {
        $pattern = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
        $chrs = str_split($pattern);
        foreach($chrs as $i => $chr)
        {
            if($chr === 'x')
            {
                $chrs[$i] = dechex(random_int(0, 15));
            }
            else
            if($chr === 'y')
            {
                $chrs[$i] = dechex(random_int(8, 11));
            }
        }
        $uuidv4 = implode('', $chrs);

        return $uuidv4;
    }

    /**
     * マインクラフトへ送信するサブスクライブデータを取得
     * 
     * @param string $p_eve サブスクライブするイベント名
     * @return array 送信データ
     */
    public function getSubscribeData(string $p_eve): array
    {
        // UUIDの取得
        $uuidv4 = $this->getUuidv4();

        // サブスクライブエントリデータ
        $w_ret =
        [
            "header" =>
            [
                "version" => 1, // プロトコルのバージョンを指定。現時点では1で問題ない
                "requestId" => $uuidv4, // UUIDv4を指定
                "messageType" => "commandRequest",  // "commandRequest" を指定
                "messagePurpose" => "subscribe", // "subscribe" を指定
            ],
            "body" =>
            [
                "eventName" => $p_eve // イベント名を指定。
            ]
        ];

        return $w_ret;
    }

    /**
     * マインクラフトへサブスクライブデータを送信
     * 
     */
    public function sendSubscribesData()
    {
        $types = config('minecraft.subscribe_types');

        foreach($types as $type)
        {
            // 送信データの設定
            $w_ret = $this->getSubscribeData($type);
            $subscribe_entry =
            [
                "data" => $w_ret
            ];
            $this->setSendStack($subscribe_entry);
        }
    }

    /**
     * 入室前のコマンドデータの送信
     * 
     * @return array 送信データ
     */
    public function execCommandBeforeEntrance()
    {
        $cmds = [];
        $cmds[] = "gamerule sendcommandfeedback false";
        $cmds[] = "event entity @s customize:is_shop_reset";
        $cmds[] = "event entity @s customize:set_wind_rod_revised_normal_size";

        foreach($cmds as $cmd)
        {
            $cmd_data = $this->getCommandData($cmd, 'before-entrance');
            $data =
            [
                'data' => $cmd_data
            ];
            $this->setSendStack($data);
        }
        return;
    }

    /**
     * マインクラフトへ送信するコマンドデータを取得
     * 
     * @param string $p_cmd コマンド文字列
     * @param string $p_typ 処理タイプ文字列（'response'コマンドで利用）
     * @param ?string $p_cid 接続ID
     * @param bool $p_no_set レスポンス情報不要フラグ
     * @return array 送信データ
     */
    public function getCommandData(string $p_cmd, string $p_typ = null, string $p_cid = null, bool $p_no_set = false): array
    {
        // UUIDの取得
        $uuidv4 = $this->getUuidv4();

        // サブスクライブエントリデータ
        $w_ret =
        [
            "header" =>
            [
                "version" => 1,
                "requestId" => $uuidv4, // UUIDv4を生成して指定
                "messageType" => "commandRequest", // commandRequestを指定
                "messagePurpose" => "commandRequest", // commandRequestを指定
            ],
            "body" =>
            [
                "origin" =>
                [
                    "type" => "player" // 誰がコマンドを実行するかを指定（ただし、Player以外にどの値が利用可能かは要調査）
                ],
                "version" => 1,
                "commandLine" => $p_cmd, // マイクラで実行したいコマンドを指定
            ]
        ];

        // レスポンス情報不要
        if($p_no_set === true)
        {
            return $w_ret;
        }

        // 待ち受けるレスポンス情報を設定
        $this->setAwaitResponse($uuidv4, $p_typ, $p_cid);

        return $w_ret;
    }

    /**
     * マインクラフトへ送信するメッセージコマンドデータを取得
     * 
     * @param string $p_typ 処理タイプ文字列
     * @param string $p_usr ユーザー名
     * @param string $p_cmt コメント
     * @param string $p_preposition 前置詞
     * @return array 送信データ
     */
    public function getCommandDataForMessage(string $p_typ, string $p_usr, string $p_cmt, string $p_preposition = 'by'): array
    {
        $cmd = "say {$p_cmt}[{$p_preposition} {$p_usr}]";
        $w_ret = $this->getCommandData($cmd, $p_typ);
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するサブタイトルコマンドデータを取得
     * 
     * @param string $p_typ 処理タイプ文字列
     * @param string $p_usr ユーザー名
     * @param string $p_preposition 前置詞
     * @return array 送信データ
     */
    public function getCommandDataForSubTitle(string $p_typ, string $p_usr, string $p_preposition = 'by'): array
    {
        $cmd = "title @s subtitle §o§7{$p_preposition} {$p_usr}";
        $w_ret = $this->getCommandData($cmd, $p_typ);
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するタイトルコマンドデータを取得
     * 
     * @param string $p_typ 処理タイプ文字列
     * @param string $p_cmt コメント
     * @param string $p_preposition 前置詞
     * @return array 送信データ
     */
    public function getCommandDataForTitle(string $p_typ, string $p_cmt): array
    {
        $cmd = "title @s title §e{$p_cmt}";
        $w_ret = $this->getCommandData($cmd, $p_typ);
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するプライベートコメントコマンドデータを取得
     * 
     * @param string $p_typ 処理タイプ文字列
     * @param string $p_susr 送信元ユーザー名
     * @param string $p_dusr 送信先ユーザー名
     * @param string $p_cmt コメント
     * @return array 送信データ
     */
    public function getCommandDataForPrivate(string $p_typ, string $p_susr, string $p_dusr = null, string $p_cmt): array
    {
        $cmd = "msg @s {$p_cmt}[by {$p_susr}]";
        $w_ret = $this->getCommandData($cmd, $p_typ);
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する雷コマンドデータを取得
     * 
     * @param float $p_x 相対X座標
     * @param float $p_y 相対Y座標
     * @param float $p_z 相対Z座標
     * @param string $p_id アイテムID
     * @return array 送信データ
     */
    public function getCommandDataForSummonThunder(float $p_x, float $p_y, float $p_z, string $p_id): array
    {
        $cmd = null;

        if($p_id === 'thunder_sword')
        {
            $cmd = "function thunder_sword_restore";
        }
        else
        if($p_id === 'thunder_sword_revised')
        {
            $cmd = "function thunder_sword_revised_restore";
        }

        if($cmd !== null)
        {
            $cmd_data = $this->getCommandData($cmd, 'item-used');
            $data =
            [
                'data' => $cmd_data
            ];
            $this->setSendStack($data);
        }

        $cmd = "summon lightning_bolt ~{$p_x} ~{$p_y} ~{$p_z}";
        $w_ret = $this->getCommandData($cmd, 'item-used');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する矢へ付与するタグデータ（ノーマル）を取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForArrowTagNormal(string $p_name): array
    {
        $cmd = "function tag_normal";
        $w_ret = $this->getCommandData($cmd, 'item-used');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する矢へ付与するタグデータ（チート）を取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForArrowTagCheat(string $p_name): array
    {
        $cmd = "function tag_cheat";
        $w_ret = $this->getCommandData($cmd, 'item-used');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する（いなずまの）矢の着弾コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForThunderArrow(string $p_name): array
    {
        $cmd = 'function arrow_thunder';
        $w_ret = $this->getCommandData($cmd, 'item-used');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する（はかいの）矢の着弾コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForExplodeArrow(string $p_name): array
    {
        $cmd = 'function arrow_explode';
        $w_ret = $this->getCommandData($cmd, 'item-used');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する２段ジャンプコマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForDoubleJump(string $p_name): array
    {
        $cmd = 'function double_jump';
        $w_ret = $this->getCommandData($cmd, null, null, true);
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する落下ダメージ設定用コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @param bool $p_flg true（ダメージあり） or false（ダメージなし）
     * @return array 送信データ
     */
    public function getCommandDataForFallDamage(string $p_name, bool $p_flg): array
    {
        $cmd = "gamerule falldamage ".var_export($p_flg, true);
        $w_ret = $this->getCommandData($cmd, 'player-travelled');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するスウィープロッド用コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForSweepRod(string $p_name): array
    {
        $cmd = "function sweep_rod";
        $w_ret = $this->getCommandData($cmd, 'sweep-rod');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するいなずまの剣改用コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForThunderSwordRevised(string $p_name): array
    {
        $cmd = "function thunder_sword_revised";
        $w_ret = $this->getCommandData($cmd, 'thunder-sword-revised-for-sweep');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するエフェクト非表示解除用コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForEffectIconReset(string $p_name): array
    {
        $cmd = "hud @s reset status_effects";
        $w_ret = $this->getCommandData($cmd, 'thunder-sword-revised');
        return $w_ret;
    }

    //--------------------------------------------------------------------------
    // 不動の杖用 <START>
    //--------------------------------------------------------------------------

    /**
     * 「不動の杖」immovableエンティティのkill用コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForKillImmovable(string $p_name): array
    {
        $minecraft_name = $this->getTempBuff(['minecraft-name']);
        $cmd = "kill @e[tag=\"immovable_{$minecraft_name['minecraft-name']}\"]";
        $w_ret = $this->getCommandData($cmd, 'immovable-rod');
        return $w_ret;
    }

    /**
     * 「不動の杖」immovableエンティティの召喚用コマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForImmovable(string $p_name): array
    {
        $cmd = "function immovable_rod";
        $w_ret = $this->getCommandData($cmd, 'immovable-rod');
        return $w_ret;
    }

    /**
     * 「不動の杖」immovableエンティティへタグを付与するコマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForTagImmovable(string $p_name): array
    {
        $minecraft_name = $this->getTempBuff(['minecraft-name']);
        $cmd = "tag @e[type=customize:immovable,r=10] add \"immovable_{$minecraft_name['minecraft-name']}\"";
        $w_ret = $this->getCommandData($cmd, 'immovable-rod');
        return $w_ret;
    }

    /**
     * 「不動の杖」相手を浮遊させるコマンドデータを取得
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForFloatingByImmovableRod(string $p_name): array
    {
        $minecraft_name = $this->getTempBuff(['minecraft-name']);
        $cmd = "damage @e[tag=\"immovable_{$minecraft_name['minecraft-name']}\",r=10] 1 entity_attack entity @s";
        $w_ret = $this->getCommandData($cmd, 'immovable-rod');
        return $w_ret;
    }

    //--------------------------------------------------------------------------
    // 不動の杖用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // スタンドの弓矢用 <START>
    //--------------------------------------------------------------------------

    /**
     * 待ち受けるレスポンス情報の設定
     * 
     * @param string $p_typ 処理タイプ文字列
     * @param ?string $p_rid リクエストID
     */
    public function setAwaitResponseForCustomize(string $p_typ, ?string $p_rid)
    {
        $this->setTempBuff(
            [
                'response_'.$p_typ => $p_rid
            ]
        );
    }

    /**
     * 待ち受けるレスポンス情報の取得
     * 
     * @param string $p_typ 処理タイプ文字列
     * @return string|null リクエストID
     */
    public function getAwaitResponseForCustomize(string $p_typ)
    {
        $w_ret = $this->getTempBuff(['response_'.$p_typ]);
        if($w_ret === null)
        {
            return null;
        }

        return $w_ret['response_'.$p_typ];
    }

    /**
     * マインクラフトへ送信するコマンドデータを取得
     * 
     * @param string $p_cmd コマンド文字列
     * @param ?string &$p_rid リクエストID格納エリア
     * @return array 送信データ
     */
    public function getCommandOrganicData(string $p_cmd, ?string &$p_rid): array
    {
        // UUIDの取得
        $p_rid = $this->getUuidv4();

        // サブスクライブエントリデータ
        $w_ret =
        [
            "header" =>
            [
                "version" => 1,
                "requestId" => $p_rid, // UUIDv4を生成して指定
                "messageType" => "commandRequest", // commandRequestを指定
                "messagePurpose" => "commandRequest", // commandRequestを指定
            ],
            "body" =>
            [
                "origin" =>
                [
                    "type" => "player" // 誰がコマンドを実行するかを指定（ただし、Player以外にどの値が利用可能かは要調査）
                ],
                "version" => 1,
                "commandLine" => $p_cmd, // マイクラで実行したいコマンドを指定
            ]
        ];

        return $w_ret;
    }

    /**
     * スタンド召喚用データを取得
     * 
     * ※「スタンドの弓矢」アイテム用
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForStandSummon(string $p_name): array
    {
        $cmd = "function stand_summon";
        $w_ret = $this->getCommandData($cmd, 'stand-summon');
        return $w_ret;
    }

    /**
     * 座標計算用の矢のスポーンデータを取得
     * 
     * ※「スタンドの弓矢」アイテム用
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForStandArrowSpawn(string $p_name, float $p_x, float $p_y, float $p_z): array
    {
        $cmd = "summon arrow stand_attack ~{$p_x} ~ ~{$p_z}";
        $w_ret = $this->getCommandData($cmd, 'stand-attack');
        return $w_ret;
    }

    /**
     * 座標計算用の矢へのタグ付与データを取得
     * 
     * ※「スタンドの弓矢」アイテム用
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForStandArrowTag(string $p_name, float $p_x, float $p_y, float $p_z): array
    {
        $cmd = "tag @e[type=arrow,x=~{$p_x},z=~{$p_z},c=1] add stand_attack";
        $w_ret = $this->getCommandData($cmd, 'stand-attack');
        return $w_ret;
    }

    /**
     * スタンド攻撃相手用タグ付与データを取得
     * 
     * ※「スタンドの弓矢」アイテム用
     * 
     * @param string $p_name プレイヤー名
     * @return array 送信データ
     */
    public function getCommandDataForStandAttackTag(string $p_name): array
    {
        $cmd = "function tag_stand_attack";
        $w_ret = $this->getCommandData($cmd, 'stand-attack');
        return $w_ret;
    }

    /**
     * スタンド攻撃コマンド実行
     * 
     * ※「スタンドの弓矢」アイテム用
     * 
     * @return array 送信データ
     */
    public function sendCommandDataForStandAttack()
    {
        $cmd = "function stand_attack";
        $cmd_data = $this->getCommandData($cmd, 'stand-attack');
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStack($data);

        $rid = null;
        $cmd = "querytarget @e[family=mob,tag=stand_attack,r=10,c=1]";
        $cmd_data = $this->getCommandOrganicData($cmd, $rid);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStack($data);
        $this->setAwaitResponseForCustomize('stand-attack', $rid);
        return;
    }

    //--------------------------------------------------------------------------
    // スタンドの弓矢用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // 階段チェア用 <START>
    //--------------------------------------------------------------------------

    /**
     * マインクラフトへ送信するブロック検査用コマンドデータを取得
     * 
     * @param string $p_id 階段ブロックID
     * @return array 送信データ
     */
    public function getCommandDataForStairsTest(string $p_id): array
    {
        $cmd = "testforblock ~ ~-1 ~ {$p_id}";
        $w_ret = $this->getCommandData($cmd, 'chair-test');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するsitエンティティのデスポーンコマンドデータを取得
     * 
     * @param int $p_x X座標（階段ブロック）
     * @param int $p_y Y座標（階段ブロック）
     * @param int $p_z Z座標（階段ブロック）
     * @return array 送信データ
     */
    public function getCommandDataForDespawnSit(int $p_x, int $p_y, int $p_z): array
    {
        $p_y--;
        $cmd = "kill @e[type=customize:sit,tag=,x={$p_x},y={$p_y},z={$p_z},c=1]";
        $w_ret = $this->getCommandData($cmd, 'chair-despawn');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する体力ゲージ非表示コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForGaugeHide(): array
    {
        $cmd = "hud @s hide horse_health";
        $w_ret = $this->getCommandData($cmd, 'chair-gauge-hide');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信する体力ゲージ表示コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForGaugeShow(): array
    {
        $cmd = "hud @s reset horse_health";
        $w_ret = $this->getCommandData($cmd, 'chair-gauge-show');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するsitエンティティの召喚コマンドデータを取得
     * 
     * @param int $p_x X座標（階段ブロック）
     * @param int $p_y Y座標（階段ブロック）
     * @param int $p_z Z座標（階段ブロック）
     * @param int $p_yrot ヨー角
     * @return array 送信データ
     */
    public function getCommandDataForSummonSit(int $p_x, int $p_y, int $p_z, int $p_yrot): array
    {
        $p_y--;
        $cmd = "function sit_summon";
        $w_ret = $this->getCommandData($cmd, 'chair-summon');
        return $w_ret;
    }

    /**
     * マインクラフトへ送信するプレイヤー搭乗のコマンドデータを取得
     * 
     * @param int $p_x X座標（階段ブロック）
     * @param int $p_y Y座標（階段ブロック）
     * @param int $p_z Z座標（階段ブロック）
     * @return array 送信データ
     */
    public function getCommandDataForRidePlayer(int $p_x, int $p_y, int $p_z): array
    {
        $p_y--;
        $cmd = "ride @s start_riding @e[type=customize:sit,tag=,x={$p_x},y={$p_y},z={$p_z},c=1]";
        $w_ret = $this->getCommandData($cmd, 'chair-ride');
        return $w_ret;
    }

    //--------------------------------------------------------------------------
    // 階段チェア用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // はやぶさの剣用 <START>
    //--------------------------------------------------------------------------

    /**
     * 「はやぶさの剣」リストア用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForHayabusaSwordUse(float $p_x, float $p_y, float $p_z, float $p_yrot): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        $cmd = "effect @s resistance 2 4 true";
        $cmd_datas[] = $this->getCommandData($cmd, 'item-used');

        // 座標退避
        $w_x = $p_x;
        $w_y = $p_y;
        $w_z = $p_z;

        // ヨー角補正
        $w_yrot = $p_yrot;
        if($p_yrot > 0)
        {
            $w_yrot = $p_yrot - 180;
        }
        else
        {
            $w_yrot = $p_yrot + 180;
        }

        // ダミーエンティティ座標の計算
        $this->getRelativeCoordinates($w_x, $w_y, $w_z, $w_yrot, 1);

        $cmd = "summon customize:hayabusa hayabusa_move_{$name['minecraft-name']} ~{$w_x} ~ ~{$w_z}";
        $cmd_datas[] = $this->getCommandData($cmd, 'item-used');

        // 座標退避
        $w_x = $p_x;
        $w_y = $p_y;
        $w_z = $p_z;

        // ウインドチャージ座標の計算
        $this->getRelativeCoordinates($w_x, $w_y, $w_z, $w_yrot, 1.07);

        $cmd = "summon wind_charge_projectile ~{$w_x} ~0.1 ~{$w_z}";
        $cmd_datas[] = $this->getCommandData($cmd, 'item-used');

        $cmd = "function hayabusa_sword_restore";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-sword-restore');

        return $cmd_datas;
    }

    /**
     * 「はやぶさの剣」テレポート用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForHayabusaTeleport(): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        $cmd = "tp @s @e[name=hayabusa_move_{$name['minecraft-name']}]";
        $cmd_datas[] = $this->getCommandData($cmd, 'item-used');

        $cmd = "tp @e[name=hayabusa_move_{$name['minecraft-name']}] ~ -50 ~";
        $cmd_datas[] = $this->getCommandData($cmd, 'item-used');

        $cmd = "kill @e[name=hayabusa_move_{$name['minecraft-name']}]";
        $cmd_datas[] = $this->getCommandData($cmd, 'item-used');

        return $cmd_datas;
    }

    /**
     * 「はやぶさの剣」持ち物検査用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForHayabusaSwordMainhandTest(): array
    {
        $cmd = "querytarget @s[hasitem={item=customize:hayabusa_sword,location=slot.weapon.mainhand}]";
        $cmd_data = $this->getCommandData($cmd, 'hayabusa-sword-test');
        return $cmd_data;
    }

    /**
     * 「はやぶさの剣」攻撃用タグの付与用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForHayabusaAttackTagAdd(): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        // タグを消去
        $cmd = "tag @e[tag=\"hayabusa_attack_{$name['minecraft-name']}\"] remove \"hayabusa_attack_{$name['minecraft-name']}\"";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-sword-tag-remove');

        // タグを付与
        $cmd = "tag @e[family=mob,r=15,c=1] add \"hayabusa_attack_{$name['minecraft-name']}\"";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-sword-tag-add');

        return $cmd_datas;
    }

    /**
     * 「はやぶさの剣」ターゲット検査用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForHayabusaAttackTargetTest(): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        // 初期化
        $cmd = "effect @s resistance 2 4 true";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-attack-init');

        // ターゲットを検査
        $cmd = "querytarget @e[tag=\"hayabusa_attack_{$name['minecraft-name']}\"]";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-attack-target-test');

        return $cmd_datas;
    }

    /**
     * 「はやぶさの剣」攻撃用コマンドデータを取得
     * 
     * @param float $p_x X座標
     * @param float $p_y Y座標
     * @param float $p_z Z座標
     * @param float $p_yrot ヨー角
     * @param float $p_r 半径
     * @return array 送信データ
     */
    public function getCommandDataForHayabusaAttack(float $p_x, float $p_y, float $p_z, float $p_yrot, float $p_r): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        // ヨー角補正
        $w_yrot = $p_yrot;
        if($p_yrot > 0)
        {
            $w_yrot -= 180;
        }
        else
        {
            $w_yrot += 180;
        }

        // 座標退避
        $w_x = $p_x;
        $w_y = $p_y;
        $w_z = $p_z;

        // テレポート座標の計算
        $this->getRelativeCoordinates($w_x, $w_y, $w_z, $w_yrot, $p_r);

        // テレポート座標補正
        $w_x += $p_x;
        $w_z += $p_z;

        // テレポート
        $cmd = "tp @s {$w_x} {$w_y} {$w_z}";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-attack-teleport');

        // ターゲットへパーティクル付与
        $cmd = "particle minecraft:sonic_explosion {$p_x} ~2 {$p_z}";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-attack-particle');

        // ターゲットを攻撃
        $cmd = "damage @e[tag=\"hayabusa_attack_{$name['minecraft-name']}\"] 15 entity_attack entity @s";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-attack');

        // カメラシェイク
        $cmd = "camerashake add @s 0.2 0.3 rotational";
        $cmd_datas[] = $this->getCommandData($cmd, 'hayabusa-attack-shake');

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // はやぶさの剣用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // 不動の魔石用 <START>
    //--------------------------------------------------------------------------

    /**
     * 不動の魔石用コマンドデータを取得
     * 
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForImmovableStone()
    {
        $cmd_datas = [];

        $minecraft_name = $this->getTempBuff(['minecraft-name']);

        // コマンド送信（sitエンティティの破棄）
        $cmd = "tp @e[tag=\"immovable_stone_{$minecraft_name['minecraft-name']}\"] ~ -50 ~";
        $cmd_datas[] = $this->getCommandData($cmd, 'chair-despawn');

        // コマンド送信（sitエンティティのデスポーン）
        $cmd = "kill @e[tag=\"immovable_stone_{$minecraft_name['minecraft-name']}\"]";
        $cmd_datas[] = $this->getCommandData($cmd, 'chair-despawn');

        // コマンド送信（体力ゲージ非表示）
        $cmd = "hud @s hide horse_health";
        $cmd_datas[] = $this->getCommandData($cmd, 'chair-gauge-hide');

        // コマンド送信（sitエンティティの召喚）
        $cmd = "function immovable_stone";
        $cmd_datas[] = $this->getCommandData($cmd, 'chair-summon');

        // コマンド送信（タグの付与）
        $cmd = "tag @e[type=customize:sit,tag=,y=~-2,c=1] add \"immovable_stone_{$minecraft_name['minecraft-name']}\"";
        $cmd_datas[] = $this->getCommandData($cmd, 'immovable-stone-tag');

        // コマンド送信（プレイヤーの搭乗）
        $cmd = "ride @s start_riding @e[tag=\"immovable_stone_{$minecraft_name['minecraft-name']}\"]";
        $cmd_datas[] = $this->getCommandData($cmd, 'chair-ride');

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // 不動の魔石用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // 浮遊の羽用 <START>
    //--------------------------------------------------------------------------

    /**
     * 浮遊の羽用コマンドデータを取得
     * 
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForFloatingFeather()
    {
        $cmd_datas = [];

        // コマンド送信（浮遊のエフェクト）
        $cmd = "function floating_feather";
        $cmd_datas[] = $this->getCommandData($cmd, 'floating-feather');

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // 浮遊の羽用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // SHOP用 <START>
    //--------------------------------------------------------------------------

    /**
     * ゲームモード取得のコマンドデータを取得
     * 
     * @param string $p_cid 接続ID
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForGetGamemode(string $p_cid)
    {
        // コマンド送信
        $cmd = "querytarget @s[m=survival]";
        $cmd_data = $this->getCommandData($cmd, 'get-gamemode', $p_cid);

        return $cmd_data;
    }

    /**
     * サバイバルモード変更のコマンドデータを取得
     * 
     * @param string $p_cid 接続ID
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForChangeSurvival(string $p_cid)
    {
        // コマンド送信
        $cmd = "gamemode survival @s";
        $cmd_data = $this->getCommandData($cmd, 'change-survival', $p_cid);

        return $cmd_data;
    }

    /**
     * 鍵を渡すコマンドデータを取得
     * 
     * @return string コマンドデータ
     */
    public function getCommandDataForSendLock()
    {
        // コマンド送信（鍵を渡す）
        $cmd = "loot give @s loot shop_lock";
        $cmd_data = $this->getCommandData($cmd, 'send-lock');

        return $cmd_data;
    }

    /**
     * 所持金取得のコマンドデータを取得
     * 
     * @param ?string $p_cid 接続ID
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForGetWallet(string $p_cid = null)
    {
        $cmd_datas = [];

        $obj_name = config('shop.wallet-object-name');

        // コマンド送信（スコアボードのオブジェクト設置）
        $cmd = "scoreboard objectives add {$obj_name} dummy";
        $cmd_datas[] = $this->getCommandData($cmd, 'wallet-obj', $p_cid);

        // コマンド送信（所持金取得）
        $cmd = "scoreboard players add @s {$obj_name} 0";
        $cmd_datas[] = $this->getCommandData($cmd, 'wallet-get', $p_cid);

        return $cmd_datas;
    }

    /**
     * 入店前のコマンドデータを取得
     * 
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForShopInit()
    {
        $cmd_datas = [];

        // プレイヤーのis_shopフラグを立てる
        $cmd = "event entity @s customize:is_shop_set";
        $cmd_datas[] = $this->getCommandData($cmd, 'shop-init');

        return $cmd_datas;
    }

    /**
     * 購入時のコマンドデータを取得
     * 
     * @param string $p_cid 接続ID
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForBuy(string $p_cid, string $p_item)
    {
        $cmd_datas = [];

        $cnf_item = config("shop.buy_list.{$p_item}");

        // コマンド送信（購入商品の配布）
        if($cnf_item['type'] === 'loot')
        {
            $cmd = "loot give @s loot {$p_item}";
            $cmd_datas[] = $this->getCommandData($cmd, 'buy-delivery', $p_cid);
        }

        // コマンド送信（所持金減額）
        $obj_name = config("shop.wallet-object-name");
        $cmd = "scoreboard players remove @s {$obj_name} {$cnf_item['price']}";
        $cmd_datas[] = $this->getCommandData($cmd, 'buy-pay', $p_cid);

        return $cmd_datas;
    }

    /**
     * 返却時のコマンドデータを取得
     * 
     * @param string $p_cid マインクラフトの接続ID
     * @param array $p_item アイテム情報
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForRelease(string $p_cid, array $p_item)
    {
        $cmd_data = null;

        // コマンド送信（商品の返却）
        if($p_item['type'] === 'loot')
        {
            $cmd = "loot give @s loot {$p_item['id']}";
            $cmd_data = $this->getCommandData($cmd, 'shop-sell-release', $p_cid);
        }
        else
        if($p_item['type'] === 'customize')
        {
            $cmd = "give @s {$p_item['type']}:{$p_item['id']}";
            $cmd_data = $this->getCommandData($cmd, 'shop-sell-release', $p_cid);
        }

        return $cmd_data;
    }

    /**
     * 売却時のコマンドデータを取得
     * 
     * @param string $p_cid 接続ID
     * @param int $p_price 加算する金額
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForSell(string $p_cid, int $p_price)
    {
        // コマンド送信（所持金加算）
        $obj_name = config("shop.wallet-object-name");
        $cmd = "scoreboard players add @s {$obj_name} {$p_price}";
        $cmd_data = $this->getCommandData($cmd, 'sell-paid', $p_cid);

        return $cmd_data;
    }

    /**
     * 退店後のコマンドデータを取得
     * 
     * @param string $p_cid 接続ID
     * @return array コマンドデータのリスト
     */
    public function getCommandDataForShopClose(string $p_cid)
    {
        $cmd_datas = [];

        // プレイヤーのis_shopフラグを落とす
        $cmd = "event entity @s customize:is_shop_reset";
        $cmd_datas[] = $this->getCommandData($cmd, 'shop-close', $p_cid);

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // SHOP用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // 繰風弾の杖用 <START>
    //--------------------------------------------------------------------------

    /**
     * 繰風弾発現用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForWindControlRodDashAndSneak(): array
    {
        $cmd_datas = [];

        $cmd = "querytarget @s[hasitem={item=customize:wind_control_rod,location=slot.weapon.mainhand}]";
        $cmd_datas[] = $this->getCommandData($cmd, 'wind-control-rod-summon');

        return $cmd_datas;
    }

    /**
     * 「繰風弾の杖」セレクト用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForWindControlRodItemUsed(int $variant, float $yrot = null): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        if($yrot !== null)
        {
            $this->setTempBuff(['yrot' => $yrot]);
        }

        $str_variant = null;
        if($variant & ParameterForMinecraft::MASK_VARIANT_SQUAT)
        {
            $str_variant = 'squat';
        }
        else
        if($variant & ParameterForMinecraft::MASK_VARIANT_SNEAKING)
        {
            $str_variant = 'sneaking';
        }
        else
        {
            $str_variant = 'normal';
        }
        $response_type = "wind-control-rod-{$str_variant}";

        $cmd = "querytarget @e[tag=wind_control_rod_{$name['minecraft-name']}]";
        $cmd_datas[] = $this->getCommandData($cmd, $response_type);

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // 繰風弾の杖用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // ファンネルユニット用 <START>
    //--------------------------------------------------------------------------

    /**
     * ファンネル回収用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForFunnelUnitDashAndSneak(): array
    {
        $cmd_datas = [];

        $cmd = "querytarget @s[hasitem={item=customize:funnel_unit,location=slot.weapon.mainhand}]";
        $cmd_datas[] = $this->getCommandData($cmd, 'funnel-unit-equiped');

        return $cmd_datas;
    }

    /**
     * ファンネル発射用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForFunnelUnitItemUsed(float $p_x, float $p_y, float $p_z, float $p_yrot): array
    {
        $cmd_datas = [];

        // マインクラフト名を取得
        $name = $this->getTempBuff(['minecraft-name']);

        $this->setTempBuff([
            'funnel-unit' => [
                'x' => $p_x,
                'y' => $p_y,
                'z' => $p_z,
                'yrot' => $p_yrot
            ]
        ]);

        $response_type = "funnel-shoot";

        $cmd = "querytarget @e[tag=\"funnel_{$name['minecraft-name']}\"]";
        $cmd_datas[] = $this->getCommandData($cmd, $response_type);

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // ファンネルユニット用 <END>
    //--------------------------------------------------------------------------

    //--------------------------------------------------------------------------
    // 風のつえ改用 <START>
    //--------------------------------------------------------------------------

    /**
     * 風のつえ改用コマンドデータを取得
     * 
     * @return array 送信データ
     */
    public function getCommandDataForWindRodRevisedDashAndSneak(): array
    {
        $cmd_datas = [];

        $cmd = "querytarget @s[hasitem={item=customize:wind_rod_revised,location=slot.weapon.mainhand}]";
        $cmd_datas[] = $this->getCommandData($cmd, 'wind-rod-revised-equiped');

        return $cmd_datas;
    }

    //--------------------------------------------------------------------------
    // 風のつえ改用 <END>
    //--------------------------------------------------------------------------

    /**
     * 現在の座標からヨー角を考慮した相対座標を取得
     * 
     * @param float &$p_x X座標
     * @param float &$p_y Y座標
     * @param float &$p_z Z座標
     * @param float $p_yrot ヨー角
     * @param float $p_r 半径
     */
    public function getRelativeCoordinates(float &$p_x, float &$p_y, float &$p_z, float $p_yrot, float $p_r)
    {
        // ヨー角を絶対値へ変換
        $yrot_abs = abs($p_yrot);

        // Z座標の計算
        $p_z = cos(deg2rad($yrot_abs)) * $p_r;

        // X座標の計算
        $p_x = sin(deg2rad($yrot_abs)) * $p_r;
        if($p_yrot > 0)
        {
            $p_x = -$p_x;
        }

        return;
    }

    /**
     * 待ち受けるレスポンス情報の設定
     * 
     * @param ?string $p_rid リクエストID
     * @param ?string $p_typ 処理タイプ文字列
     * @param ?string $p_cid 接続ID
     */
    public function setAwaitResponse(?string $p_rid, ?string $p_typ, string $p_cid = null)
    {
        $responses = null;
        $w_ret = $this->getTempBuff(['responses']);
        if($w_ret === null)
        {
            $responses = [];
        }
        else
        {
            $responses = $w_ret['responses'];
        }

        $responses[] =
        [
            'requestId' => $p_rid,
            'type' => $p_typ
        ];
        $this->setTempBuff(
            [
                'responses' => $responses
            ],
            $p_cid
        );
    }

    /**
     * 待ち受けるレスポンス情報の削除
     * 
     * @param ?string $p_rid リクエストID
     * @param ?string $p_cid 接続ID
     */
    public function delAwaitResponse(?string $p_rid, string $p_cid = null)
    {
        $responses = null;
        $w_ret = $this->getTempBuff(['responses']);
        if($w_ret === null)
        {
            return;
        }
        $responses = $w_ret['responses'];
        $count = count($responses);

        for($i = 0; $i < $count; $i++)
        {
            if($responses[$i]['requestId'] === $p_rid)
            {
                unset($responses[$i]);
            }
        }

        $responses = array_values($responses);

        $this->setTempBuff(
            [
                'responses' => $responses
            ],
            $p_cid
        );
    }

    /**
     * 待ち受けるレスポンス情報の取得
     * 
     * @return array [['requestId' => <リクエストID>, 'type' => <実行するコマンド>]...]
     */
    public function getAwaitResponse()
    {
        $ret = null;
        $w_ret = $this->getTempBuff(['responses']);
        if($w_ret === null)
        {
            $ret = [];
        }
        else
        {
            $ret = $w_ret['responses'];
        }
        return $ret;
    }


    //--------------------------------------------------------------------------
    // サーバー間通信リクエスト用
    //--------------------------------------------------------------------------

    /**
     * サーバー間通信でプライベートコメントを送信
     * 
     * @param string $p_suser 送信元ユーザー名
     * @param string $p_duser 送信先ユーザー名
     * @param string $p_comment 送信コメント
     */
    public function searchSendPrivateComment(string $p_suser, string $p_duser, string $p_comment)
    {
        $cid = $this->getConnectionId();

        $server = $this->server();
        if($server !== null)
        {
            $this->server()->requestPrivateComment($cid, $p_suser, $p_duser, $p_comment, null);
            return;
        }

        // サーバー間通信連携がない場合は結果NGで返信する
        $data =
        [
            'data' =>
            [
                'cmd' => CommandQueueEnumForMinecraft::PRIVATE_RESULT->value,
                'user' => $p_duser,
                'rno' => null,
                'result' => false
            ]
        ];
        $this->setRecvStack($data);

        return;
    }


    //--------------------------------------------------------------------------
    // サーバー間通信用パラメータクラスの連携用
    //--------------------------------------------------------------------------


    //--------------------------------------------------------------------------
    // その他
    //--------------------------------------------------------------------------

    /**
     * メッセージコマンド配信
     * 
     * @param array $p_msg メッセージコマンドデータ
     */
    public function sendMessage(array $p_msg)
    {
        $p_msg['result'] = true;

        // HTML変換
        $p_msg['comment'] = htmlspecialchars($p_msg['comment']);

        $minecraft = $this->isMinecraft();

        // 全ブラウザへ配信
        $data =
        [
            'data' => $p_msg
        ];
        $this->setSendStackAll($data, $minecraft, function(ParameterForMinecraft $p_param)
        {
            return !$p_param->isMinecraft();
        }, $this);

        // 自身がマインクラフトなら抜ける
        if($minecraft === true)
        {
            return;
        }

        // 全マインクラフトへ配信
        $cmd_data = $this->getCommandDataForSubtitle('subtitle', $p_msg['user']);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStackAll($data, $minecraft, function(ParameterForMinecraft $p_param)
        {
            return $p_param->isMinecraft();
        }, $this);

        // 全マインクラフトへ配信
        $cmd_data = $this->getCommandDataForTitle('title', $p_msg['comment']);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStackAll($data, $minecraft, function(ParameterForMinecraft $p_param)
        {
            return $p_param->isMinecraft();
        }, $this);
    }

    /**
     * 退室コマンド配信
     * 
     * @param array $p_msg 退室コマンドデータ
     */
    public function sendExit(array $p_msg)
    {
        // 全ブラウザへ配信
        $data =
        [
            'data' => $p_msg
        ];
        $this->setSendStackAll($data, true, function(ParameterForMinecraft $p_param)
        {
            return !$p_param->isMinecraft();
        }, $this);

        // 全マインクラフトへ配信
        $cmd_data = $this->getCommandDataForPrivate('exit', $p_msg['user'], null, $p_msg['comment']);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStackAll($data, false, function(ParameterForMinecraft $p_param)
        {
            return $p_param->isMinecraft();
        }, $this);

        // 自身の接続がマインクラフトかどうか
        $minecraft = $this->isMinecraft();

        if($minecraft === false)
        {
            // 切断パラメータを設定
            $close_param =
            [
                // 切断コード
                'code' => ParameterForMinecraft::CHAT_SELF_CLOSE_CODE,
                // シリアライズ対象データ
                'data' =>
                [
                    // 切断時パラメータ（現在日時）
                    'datetime' => $p_msg['datetime']
                ]
            ];

            // 自身を切断
            $this->close($close_param);
        }
    }

    /**
     * 切断コマンド配信
     * 
     * @param array $p_msg 退室コマンドデータ
     */
    public function sendClose(array $p_msg)
    {
        // 自身の接続がマインクラフトかどうか
        $minecraft = $this->isMinecraft();

        // 全ブラウザへ配信
        $data =
        [
            'data' => $p_msg
        ];
        $this->setSendStackAll($data, $minecraft, function(ParameterForMinecraft $p_param)
        {
            return !$p_param->isMinecraft();
        }, $this);

        // 全マインクラフトへ配信
        $cmd_data = $this->getCommandDataForPrivate('private', self::CHAT_ADMIN_USER, null, $p_msg['comment']);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStackAll($data, !$minecraft, function(ParameterForMinecraft $p_param)
        {
            return $p_param->isMinecraft();
        }, $this);

        // 受信データを取得
        $w_ret = $this->getRecvData();

        // 自身の切断シーケンス開始
        $close_param =
        [
            // 切断コード
            'code' => $w_ret['close_code'],
            // シリアライズ対象データ
            'data' => $w_ret['data']
        ];
        $this->close($close_param);
    }

    /**
     * プライベートコメントを送信
     * 
     * @param string $p_src 送信元ユーザー名
     * @param string $p_dst 送信先ユーザー名
     * @param string $p_comment コメント
     * @return bool true（成功） or false（送信先ユーザーが存在しない）
     */
    public function sendPrivate(string $p_src, string $p_dst, string $p_comment)
    {
        $match_cid = null;
        foreach($this->user_list as $cid => $name)
        {
            // 送信先ユーザー名が一致
            if($p_dst === $name)
            {
                $match_cid = $cid;
            }
        }

        // 送信先ユーザーがいない
        if($match_cid === null)
        {
            return false;
        }

        $manager = $this->getSocketManager();

        // 送信データを作成
        $datetime = date(self::CHAT_DATETIME_FORMAT);
        $data =
        [
            'data' =>
            [
                'cmd' => CommandQueueEnumForMinecraft::PRIVATE->value,
                'datetime' => $datetime,
                'user_count' => $this->getClientCount(),
                'user' => $p_src,
                'comment' => $p_comment,
            ]
        ];

        // 自身の接続がマインクラフトかどうか
        $minecraft = $this->isMinecraft($match_cid);
        if($minecraft === true)
        {
            $minecraft_data = $this->getCommandDataForPrivate('private', $p_src, $p_dst, $p_comment);
            $data =
            [
                'data' => $minecraft_data
            ];
        }

        // 送信データをエントリ
        $manager->setSendStack($match_cid, $data);

        // チャットをログに残す
        $this->privateLogWriter($datetime, $p_src, $p_dst, $p_comment, true);

        return true;
    }

    /**
     * プライベートコメント返信
     * 
     * @param string $p_usr 宛先ユーザー名
     * @param array $p_cmts コメント
     * @param bool $p_res 結果
     */
    public function sendPrivateReply(string $p_usr, array $p_cmts, bool $p_res)
    {
        // 自身の接続がマインクラフトかどうか
        $minecraft = $this->isMinecraft();

        // コメント生成
        $cmt_join = '';
        foreach($p_cmts as $cmt)
        {
            if(strlen($cmt_join) > 0)
            {
                if($minecraft === true)
                {
                    $cmt_join .= '。';
                }
                else
                {
                    $cmt_join .= '<br />';
                }
            }
            $cmt_join .= $cmt;
        }

        // 送信データ生成
        $data = [];
        if($minecraft === true)
        {
            $minecraft_data = $this->getCommandDataForPrivate('private-reply', self::CHAT_ADMIN_USER, null, $cmt_join);
            $data =
            [
                'data' => $minecraft_data
            ];
        }
        else
        {
            $data =
            [
                'data' =>
                [
                    'cmd' => 'private-reply',   // クライアントへ返すコマンド
                    'user' => $p_usr,
                    'result' => $p_res,
                    'comment' => $cmt_join
                ]
            ];
        }

        // 送信
        $this->setSendStack($data);
    }

    /**
     * 入室時返信
     * 
     * ユーザー名重複なし時の返信
     * 
     * @param array $p_msg 入室コマンドデータ
     */
    public function sendEntranceReply(array $p_msg)
    {
        // マインクラフト接続かどうか
        $minecraft = $this->isMinecraft();

        // 全ブラウザへ配信
        $data =
        [
            'data' => $p_msg
        ];
        $this->setSendStackAll($data, true, function(ParameterForMinecraft $p_param)
        {
            return !$p_param->isMinecraft();
        }, $this);

        // 全マインクラフトへ配信
        $cmd_data = $this->getCommandDataForPrivate('usersearch-result', $p_msg['user'], null, $p_msg['comment']);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->setSendStackAll($data, !$minecraft, function(ParameterForMinecraft $p_param)
        {
            return $p_param->isMinecraft();
        }, $this);

        // マインクラフトでない場合
        if($minecraft !== true)
        {
            // オプションデータを設定
            $p_msg['opts'] = $this->getOptions();
            $data =
            [
                'data' => $p_msg
            ];
            // 自身へメッセージ配信
            $this->setSendStack($data);
        }
        else
        {
            /**
             * サブスクライブのエントリ
             */

             $this->sendSubscribesData();
        }
    }

    /**
     * コメントなし時のレスポンス配信
     * 
     * @param array $p_msg レスポンスデータ
     */
    public function responseNoComment(array $p_msg)
    {
        $minecraft = $this->isMinecraft();
        if($minecraft === true)
        {
            // マインクラフトへ配信
            $cmd_data = $this->getCommandDataForPrivate('no-comment', self::CHAT_ADMIN_USER, null, $this->options['no_comment']);
            $data =
            [
                'data' => $cmd_data
            ];
            $this->setSendStack($data);
        }
        else
        {
            // レスポンス配信
            $data =
            [
                'data' => $p_msg
            ];
            $this->setSendStack($data);
        }
    }

    /**
     * ユーザー名重複時の切断処理
     * 
     * @return ?string ステータス名 or null
     */
    public function closeUserDuplication(): ?string
    {
        $minecraft = $this->isMinecraft();
        if($minecraft === true)
        {
            // マインクラフトへ配信
            $cmd_data = $this->getCommandDataForPrivate('user-duplication', self::CHAT_ADMIN_USER, null, $this->options['duplication_comment']);
            $data =
            [
                'data' => $cmd_data
            ];
            $this->setSendStack($data);
            return CommandStatusEnumForMinecraft::SENDING->value;
        }
        else
        {
            $close_param =
            [
                // 切断コード
                'code' => ParameterForWebsocket::CHAT_DUPLICATION_CLOSE_CODE,
                // シリアライズ対象データ
                'data' =>
                [
                    // 切断時パラメータ（現在日時）
                    'datetime' => date(ParameterForWebsocket::CHAT_DATETIME_FORMAT)
                ]
            ];
            $this->close($close_param);
            return null;
        }
    }

    /**
     * ユーザー名なし時の切断処理
     */
    public function closeNoUser()
    {
        $minecraft = $this->isMinecraft();
        if($minecraft === true)
        {
            // マインクラフトへ配信
            $cmd_data = $this->getCommandDataForPrivate('no-user', self::CHAT_ADMIN_USER, null, $this->options['no_user_comment']);
            $data =
            [
                'data' => $cmd_data
            ];
            $this->setSendStack($data);
        }
        else
        {
            $close_param =
            [
                // 切断コード
                'code' => ParameterForWebsocket::CHAT_NO_USER_CLOSE_CODE,
                // シリアライズ対象データ
                'data' =>
                [
                    // 切断時パラメータ（現在日時）
                    'datetime' => date(ParameterForWebsocket::CHAT_DATETIME_FORMAT)
                ]
            ];
            $this->close($close_param);
        }
    }

    /**
     * クライアントからの強制切断時のコールバック
     * 
     * @param ParameterForWebsocket $p_param UNITパラメータ
     */
    public function forcedCloseFromClient(ParameterForWebsocket $p_param)
    {
        // クラスのキャストのため代入
        $this->param = $p_param;

        $this->param->logWriter('debug', [__METHOD__ => '緊急切断', 'minecraft' => "flag[{$this->param->isMinecraft()}]"]);

        // ショップの場合はマインクラフト側のショップ情報を削除
        if($this->param->isShop() === true)
        {
            $shop_browser = $this->param->getTempBuff(['shop']);
            $shop_browser['shop']['sta'] = ShopStatusEnum::CLOSE->value;
            $p_param->setTempBuff(['shop' => $shop_browser['shop']]);
            $this->param->setTempBuff(['shop' => null], $shop_browser['shop']['cid']);
            return;
        }

        $msg = [];

        // コマンドを設定
        $msg['cmd'] = CommandQueueEnumForMinecraft::CLOSE->value;

        // 現在日時を設定
        $msg['datetime'] = date(ParameterForMinecraft::CHAT_DATETIME_FORMAT);

        // 現在のユーザー数を設定
        $msg['count'] = $this->param->getClientCount() - 1;

        // 自身のユーザー名を設定
        $msg['user'] = $this->param->getUserName();
        if($msg['user'] === null)
        {
            return;
            $msg['user'] = $this->options['unknown_user'];
        }

        // 退室コメントを設定
        $opts = $this->param->getOptions();
        $msg['comment'] = $opts['forced_close_comment'];

        // ユーザー名をリストから削除
        $this->param->delUserName();

        // ユーザーリストを設定
        $msg['user_list'] = $this->param->getUserList();

        // 全ブラウザへ配信
        $data =
        [
            'data' => $msg
        ];
        $this->param->setSendStackAll($data, true, function(ParameterForMinecraft $p_param)
        {
            return !$p_param->isMinecraft();
        }, $this->param);

        // 全マインクラフトへ配信
        $cmd_data = $this->param->getCommandDataForPrivate('forced-close', $msg['user'], null, $msg['comment']);
        $data =
        [
            'data' => $cmd_data
        ];
        $this->param->setSendStackAll($data, true, function(ParameterForMinecraft $p_param)
        {
            return $p_param->isMinecraft();
        }, $this->param);

        // チャットをログに残す
        $this->param->chatLogWriter($msg['datetime'], $msg['user'], $msg['comment']);

        return;
    }
}
