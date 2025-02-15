<?php
/**
 * マインクラフト用の定義
 */

return [

    /**
     * @var array 設定するサブスクライブタイプ（複数指定可能）
     */
    'subscribe_types' =>
    [
        'PlayerMessage',
        'ItemUsed',
        'PlayerTravelled'
    ],

    /**
     * @var array 二段ジャンプの設定
     */
    'double_jump' =>
    [
        /**
         * @var bool 無効化フラグ（true:無効 or false:有効）
         */
        'ignore'    => false,

        /**
         * @var float ジャンプのみなし移動量（ｍ）
         */
        'meter'     => 1.21,

        /**
         * @var float 二段ジャンプのインターバル（秒）
         */
        'interval'  => 2.3
    ],

    /**
     * @var array 座れる階段ブロック（複数指定可能）
     */
    'stairs_ids' =>
    [
        'stone_stairs',     // 丸石
        'oak_stairs',       // オーク
        'cherry_stairs',    // サクラ
        'brick_stairs',     // レンガ
        'quartz_stairs'     // クォーツ
    ],

    /**
     * @var array ファンネル設定
     */
    'funnel_setting' =>
    [
        // ファンネル名フォーマット（":name"にはマインクラフトユーザー名が設定される）
        'name_format' => 'ファンネル（:name）',

        // 発射の上限
        'shoot_limit' => 5,

        // メッセージ類
        'messages' =>
        [
            // 発射の上限に達した時
            'shoot_limit' => 'これ以上発射できません',

            // ファンネルの回収に成功した時
            'collect_success' => 'ファンネルを回収しました',

            // 回収できるファンネルが存在しない時
            'collect_fail' => '回収できるファンネルはありません'
        ]
    ]
];
