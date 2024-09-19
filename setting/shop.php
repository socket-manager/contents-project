<?php
/**
 * ネットショップ用の定義
 */

return [

    /**
     * @var array 購入可能商品リスト
     */
    'buy_list' =>
    [
        'thunder_sword_revised' =>
        [
            'id' => 'thunder_sword_revised',
            'type' => 'loot',
            'icon' => './img/thunder_sword_revised.png',
            'name' => 'いなずまの剣（改）',
            'price' => 20000
        ],
        'thunder_sword' =>
        [
            'id' => 'thunder_sword',
            'type' => 'loot',
            'icon' => './img/thunder_sword.png',
            'name' => 'いなずまの剣',
            'price' => 10000
        ],
        'hayabusa_sword' =>
        [
            'id' => 'hayabusa_sword',
            'type' => 'loot',
            'icon' => './img/hayabusa_sword.png',
            'name' => 'はやぶさの剣',
            'price' => 15000
        ],
        'immovable_rod' =>
        [
            'id' => 'immovable_rod',
            'type' => 'loot',
            'icon' => './img/immovable_rod.png',
            'name' => '不動の杖',
            'price' => 20000
        ],
        'teleport_rod' =>
        [
            'id' => 'teleport_rod',
            'type' => 'loot',
            'icon' => './img/teleport_rod.png',
            'name' => '瞬間移動の杖',
            'price' => 15000
        ],
        'sweep_rod' =>
        [
            'id' => 'sweep_rod',
            'type' => 'loot',
            'icon' => './img/sweep_rod.png',
            'name' => 'スウィープロッド',
            'price' => 15000
        ],
        'bow_thunder' =>
        [
            'id' => 'bow_thunder',
            'type' => 'loot',
            'icon' => './img/bow_thunder.png',
            'name' => 'いなずまの弓',
            'price' => 8000
        ],
        'bow_mine' =>
        [
            'id' => 'bow_mine',
            'type' => 'loot',
            'icon' => './img/bow_mine.png',
            'name' => '機雷の弓',
            'price' => 8000
        ],
        'bow_stand' =>
        [
            'id' => 'bow_stand',
            'type' => 'loot',
            'icon' => './img/bow_stand.png',
            'name' => 'スタンドの弓',
            'price' => 8000
        ],
        'arrow_thunder' =>
        [
            'id' => 'arrow_thunder',
            'type' => 'loot',
            'icon' => './img/arrow_thunder.png',
            'name' => 'いなずまの矢',
            'price' => 3000
        ],
        'arrow_explode' =>
        [
            'id' => 'arrow_explode',
            'type' => 'loot',
            'icon' => './img/arrow_explode.png',
            'name' => 'はかいの矢',
            'price' => 3000
        ],
        'arrow_stand' =>
        [
            'id' => 'arrow_stand',
            'type' => 'loot',
            'icon' => './img/arrow_stand.png',
            'name' => 'スタンドの矢',
            'price' => 3000
        ]
    ],

    /**
     * @var array 売却可能商品リスト
     */
    'sell_list' =>
    [
        'thunder_sword_revised' =>
        [
            'id' => 'thunder_sword_revised',
            'type' => 'loot',
            'icon' => './img/thunder_sword_revised.png',
            'name' => 'いなずまの剣（改）',
            'price' => 13500
        ],
        'thunder_sword' =>
        [
            'id' => 'thunder_sword',
            'type' => 'loot',
            'icon' => './img/thunder_sword.png',
            'name' => 'いなずまの剣',
            'price' => 6700
        ],
        'hayabusa_sword' =>
        [
            'id' => 'hayabusa_sword',
            'type' => 'loot',
            'icon' => './img/hayabusa_sword.png',
            'name' => 'はやぶさの剣',
            'price' => 10000
        ],
        'immovable_rod' =>
        [
            'id' => 'immovable_rod',
            'type' => 'loot',
            'icon' => './img/immovable_rod.png',
            'name' => '不動の杖',
            'price' => 13500
        ],
        'immovable_stone' =>
        [
            'id' => 'immovable_stone',
            'type' => 'loot',
            'icon' => './img/immovable_stone.png',
            'name' => '不動の魔石',
            'price' => 6000
        ],
        'floating_feather' =>
        [
            'id' => 'floating_feather',
            'type' => 'loot',
            'icon' => './img/floating_feather.png',
            'name' => '浮遊の羽',
            'price' => 4000
        ]
    ],

    /**
     * @var array 入店時オプションデータ
     */
    'opts' =>
    [
        'unknown_user' => '入力されたユーザー名では見つかりません。',
        'other_than_minecraft' => '入力されたユーザー名はマインクラフトユーザーではありません。',
        'other_than_survival' => '接続先のマインクラフトは、<br />現在サバイバルモードではないようです。',
        'admin_user' => '運営チーム',
        'exit_comment' => 'またのお越しをお待ちしております。',
        'server_close_comment' => 'サーバーから切断されました。',
        'forced_close_comment' => '強制切断されました。',
        'unexpected_close_comment' => '予期しない切断が発生しました。',
        'error_comment' => 'エラーが発生しました。',
        'no_user_comment' => 'ユーザー名を入力してください。',
        'welcome_comment' => 'いらっしゃいませ。',
        'wait_comment' => 'マインクラフトからの接続待ちです．．．',
        'paying_comment' => '会計中．．．',
        'thankyou_comment' => 'お買い上げありがとうございました。',
        'releasing_comment' => '返却中．．．',
        'released_comment' => '返却しました。',
        'selling_comment' => '売却中．．．',
        'sold_comment' => '売却しました。'
    ]
];
