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
        /**
         * ":name"をプレースホルダとしてマインクラフトユーザー名が設定される
         * 
         * @var string ファンネル名フォーマット
         */
        'name_format' => 'ファンネル（:name）',

        /**
         * @var int 発射の上限
         */
        'shoot_limit' => 6,

        /**
         * @var int 発射の上限（Ｎジャマー搭載時）
         */
        'njammer_limit' => 12,

        /**
         * @var array メッセージリスト
         */
        'messages' =>
        [
            /**
             * @var string 発射の上限に達した時のメッセージ
             */
            'shoot_limit' => 'これ以上発射できません',

            /**
             * @var string ファンネルの回収に成功した時のメッセージ
             */
            'collect_success' => 'ファンネルを回収しました',

            /**
             * @var string 回収できるファンネルが存在しない時のメッセージ
             */
            'collect_fail' => '回収できるファンネルはありません'
        ]
    ]
];
