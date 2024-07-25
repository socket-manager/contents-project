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
         * @var float ジャンプのみなし移動量（ｍ）
         */
        'meter'     => 1.21,

        /**
         * @var float 二段ジャンプのインターバル（秒）
         */
        'interval'  => 2.3
    ]
];
