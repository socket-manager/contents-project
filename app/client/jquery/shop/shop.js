$(window).on('load', function()
{
});

$(function()
{
    //--------------------------------------------------------------------------
    // キャッシュ対応
    //--------------------------------------------------------------------------

    $('script').each(function(index, element) {
        const src = $(element).attr('src');
        $(element).attr('src', src + '?' + new Date().getTime());
    });

    $('link').each(function(index, element) {
        const src = $(element).attr('href');
        $(element).attr('href', src + '?' + new Date().getTime());
    });

    //--------------------------------------------------------------------------
    // 表示の初期化
    //--------------------------------------------------------------------------

    // ローディング非表示
    $('.loading-img').hide();

    // 吹き出しボタン非表示
    $('.talk-button-box').hide();

    //--------------------------------------------------------------------------
    // 定数定義
    //--------------------------------------------------------------------------

    /**
     * 自身による退室
     * 
     * @type {number}
     */
    const CHAT_SELF_CLOSE_CODE = 10;

    /**
     * サーバーからの切断
     * 
     * @type {number}
     */
    const CHAT_SERVER_CLOSE_CODE = 20;

    /**
     * サーバーからの切断（ユーザー名重複）
     * 
     * @type {number}
     */
    const CHAT_DUPLICATION_CLOSE_CODE = 30;

    /**
     * サーバーからの切断（ユーザー名なし）
     * 
     * @type {number}
     */
    const CHAT_NO_USER_CLOSE_CODE = 40;

    /**
     * クライアントからの切断
     * 
     * @type {number}
     */
    const CHAT_CLIENT_CLOSE_CODE = 3010;


    //--------------------------------------------------------------------------
    // 変数の初期設定
    //--------------------------------------------------------------------------

    /**
     * Websocketのインスタンス
     * 
     * @type {Object}
     */
    let websocket = null;

    /**
     * @typedef {Object} opts - オプションデータ（入室時にサーバーから取得）
     * @property {string} unknown_user - 不明なユーザー名
     * @property {string} other_than_minecraft - マインクラフトユーザー以外
     * @property {string} other_than_survival - サバイバルモード以外
     * @property {string} admin_user - 運営サイドのユーザー名
     * @property {string} exit_comment - 退室コメント
     * @property {string} server_close_comment - サーバーからの切断コメント
     * @property {string} forced_close_comment - 強制切断コメント
     * @property {string} unexpected_close_comment - 予期しない切断コメント
     * @property {string} error_comment - エラーコメント
     * @property {string} no_user_comment - ユーザー名なし時のコメント
     * @property {string} wait_comment - 入店中のコメント
     * @property {string} paying_comment - 会計中のコメント
     * @property {string} thankyou_comment - サンキューコメント
     * @property {string} releasing_comment - 返却中のコメント
     * @property {string} released_comment - 返却済みコメント
     * @property {string} selling_comment - 売却中のコメント
     * @property {string} sold_comment - 売却済みコメント
     */
    /** @type {opts} */
    let opts =
    {
        unknown_user: null,
        other_than_minecraft: null,
        other_than_survival: null,
        admin_user: '<b>運営チーム</b>',
        exit_comment: null,
        server_close_comment: null,
        forced_close_comment: null,
        unexpected_close_comment: null,
        error_comment: 'エラーが発生しました。',
        no_user_comment: 'ユーザー名を入力してください。',
        wait_comment: 'マインクラフトからの接続待ちです．．．',
        paying_comment: null,
        thankyou_comment: null,
        releasing_comment: null,
        released_comment: null,
        selling_comment: null,
        sold_comment: null
    };

    /**
     * 初回入室のフラグ
     * 
     * @type {boolean}
     */
    let flg_first_entrance = true;

    /**
     * エラー発生時のフラグ
     * 
     * @type {boolean}
     */
    let flg_error = false;

    /**
     * 吹き出しボタン押下時の処理モード
     * 
     * @type {number} - 'buy'（購入アイテム決定時） or 'sell'（売却決定時） or 'sell-release'（返却決定時） or 'survival'（サバイバル変更時）
     */
    let switch_talk_btn = null;


    //--------------------------------------------------------------------------
    // イベント定義
    //--------------------------------------------------------------------------

    // 入店する／退店するボタン
    $(document).on('click', '#connect_button', function()
    {
        if(websocket === null)
        {
            // ボタン名変更
            $('#connect_button').text('退店する');

            // URI入力を禁止
            $('input[name="uri"]').prop('disabled', true);

            // ユーザー名入力を禁止
            $('input[name="user"]').prop('disabled', true);

            // Websocketを開く
            setOpenWebsocket();

            // ローディング表示
            $('.loading-img').show();

            // 吹き出し表示
            $('.talk').html(opts.wait_comment);
        }
        else
        {
            // 切断要求を送信
            let param =
            {
                'cmd': 'close',
                'code': CHAT_CLIENT_CLOSE_CODE
            };
            websocket.close(CHAT_CLIENT_CLOSE_CODE, JSON.stringify(param));
        }
    });

    // 購入可能リストクリック
    $(document).on('click', '.buy-radio', function()
    {
        // 吹き出し表示
        let val = $(this).val();
        let item = $(`label[for="${val}"]`).html();
        item = '<div class="talk-item">' + item + '</div><br />&nbsp;&nbsp;&nbsp;&nbsp;購入しますか？';
        $('.talk').html(item);

        // 吹き出しボタン表示
        $('.talk-button-box').show();

        // 購入処理モード
        switch_talk_btn = 'buy';
    });

    // 売却ボタン
    $(document).on('click', '#btn-sell', function()
    {
        // 吹き出し表示
        let talk = '売却しますか？';
        $('.talk').html(talk);

        // 吹き出しボタン表示
        $('.talk-button-box').show();

        // 売却処理モード
        switch_talk_btn = 'sell';
    });

    // 返却ボタン
    $(document).on('click', '#btn-sell-release', function()
    {
        // 吹き出し表示
        let talk = '返却しますか？';
        $('.talk').html(talk);

        // 吹き出しボタン表示
        $('.talk-button-box').show();

        // 返却処理モード
        switch_talk_btn = 'sell-release';
    });

    // 購入／売却時吹き出しボタン（はい）
    $(document).on('click', '#btn-npc-ok', function()
    {
        // 購入処理
        if(switch_talk_btn === 'buy')
        {
            let item = $('.buy-list .buy-radio:checked').val();

            // 店内非表示
            $('.contents-box').hide();
            $('.entrance-box').hide();

            // 吹き出しセット
            $('.talk').html(opts.paying_comment);

            // ローディング表示
            $('.loading-img').show();

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();

            // 購入コマンド送信
            let data =
            {
                  'cmd': 'shop-buy'
                , 'item': item
            };
            websocket.send(JSON.stringify(data));
        }
        // 売却処理
        else
        if(switch_talk_btn === 'sell')
        {
            // 売却・返却ボタン共に非活性にする
            $('#btn-sell').prop('disabled', true);
            $('#btn-sell-release').prop('disabled', true);

            // 店内非表示
            $('.contents-box').hide();
            $('.entrance-box').hide();

            // 吹き出しセット
            $('.talk').html(opts.selling_comment);

            // ローディング表示
            $('.loading-img').show();

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();

            // 売却コマンド送信
            let data =
            {
                  'cmd': 'shop-sell'
            };
            websocket.send(JSON.stringify(data));
        }
        // 返却処理
        else
        if(switch_talk_btn === 'sell-release')
        {
            // 店内非表示
            $('.contents-box').hide();
            $('.entrance-box').hide();

            // 吹き出しセット
            $('.talk').html(opts.releasing_comment);

            // ローディング表示
            $('.loading-img').show();

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();

            // 返却コマンド送信
            let data =
            {
                'cmd': 'shop-sell-release'
            };
            websocket.send(JSON.stringify(data));
        }
        // サバイバルモード変更処理
        else
        if(switch_talk_btn === 'survival')
        {
            // 店内非表示
            $('.contents-box').hide();
            $('.entrance-box').hide();

            // 吹き出しセット
            $('.talk').html(opts.wait_comment);

            // ローディング表示
            $('.loading-img').show();

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();

            // サバイバルモード変更コマンド送信
            let data =
            {
                'cmd': 'shop-survival-change'
            };
            websocket.send(JSON.stringify(data));
        }
    });

    // 購入／売却時吹き出しボタン（いいえ）
    $(document).on('click', '#btn-npc-cancel', function()
    {
        // 購入処理
        if(switch_talk_btn === 'buy')
        {
            // ラジオボタンのチェックをはずす
            $('.buy-radio').prop('checked', false);

            // 吹き出しセット
            $('.talk').html(opts.welcome_comment);

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();
        }
        // 売却処理
        else
        if(switch_talk_btn === 'sell')
        {
            // 吹き出しセット
            $('.talk').html(opts.welcome_comment);

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();
        }
        // 返却処理
        else
        if(switch_talk_btn === 'sell-release')
        {
            // 吹き出しセット
            $('.talk').html(opts.welcome_comment);

            // 吹き出しボタン非表示
            $('.talk-button-box').hide();
        }
        // サバイバル変更処理
        else
        if(switch_talk_btn === 'survival')
        {
            // 切断要求を送信
            let param =
            {
                'cmd': 'close',
                'code': CHAT_CLIENT_CLOSE_CODE
            };
            websocket.close(CHAT_CLIENT_CLOSE_CODE, JSON.stringify(param));
        }
    });

    //--------------------------------------------------------------------------
    // 関数定義
    //--------------------------------------------------------------------------

    /**
     * Websocketイベントの定義
     * 
     * @returns {void}
     */
    function setOpenWebsocket()
    {
        let uri = $('input[name="uri"]').val();

        // Websocket接続
        websocket = new WebSocket(uri);

        /**
         * 接続完了イベント
         * 
         * @param {*} event イベントインスタンス
         * @returns 
         */
        websocket.onopen = function(event)
        {
            flg_error = false;

            let data =
            {
                  'cmd': 'shop-entrance'
                , 'user': $('input[name="user"]').val()
            };
            websocket.send(JSON.stringify(data));
        };
    
        /**
         * データ受信イベント
         * 
         * @param {*} event イベントインスタンス
         * @returns 
         */
        websocket.onmessage = function(event)
        {
            let data = JSON.parse(event.data);

            console.log('↓コマンドデータ');
            console.dir(data);

            // 入店コマンド
            if(data.cmd === 'shop-entrance')
            {
                if(typeof(data.opts) !== 'undefined')
                {
                    // オプションデータを退避
                    opts = data.opts;
                }
    
                // 不明なユーザー名
                if(data.result === -1)
                {
                    // 吹き出しの設定
                    $('.talk').html(opts.unknown_user);
                    return;
                }
                // マインクラフトユーザーではない
                if(data.result === -2)
                {
                    // 吹き出しの設定
                    $('.talk').html(opts.other_than_minecraft);
                    return;
                }
                // ゲームモードがサバイバルモードではない
                if(data.result === -3)
                {
                    // 吹き出しの設定
                    let talk = opts.other_than_survival + '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;サバイバルモードへ変更しますか？';
                    $('.talk').html(talk);

                    // 吹き出しボタン表示
                    $('.talk-button-box').show();

                    // サバイバル変更処理モード
                    switch_talk_btn = 'survival';
                    return;
                }
        
                // ユーザー名を再設定
                if(flg_first_entrance === true)
                {
                    $('input[name="user"]').val(data.user);
                    flg_first_entrance = false;
                }

                // 吹き出しの設定
                $('.talk').html(opts.welcome_comment);

                // ウォレットの設定
                $('.money-value').html(data.wallet);

                // 購入可能リストの設定
                setBuyList(data.buy_list, data.wallet);

                // 売却リストの設定
                setSellList([]);

                // 店内表示
                $('.contents-box').html($('.menu-list-box').html());
                $('.entrance-box').html($('.entrance-hidden-box').html());
                $('.contents-box').show();
                $('.entrance-box').show();

                // ローディング非表示
                $('.loading-img').hide();
            }
            else
            // 購入コマンド
            if(data.cmd === 'shop-buy')
            {
                // 吹き出しの設定
                $('.talk').html(opts.thankyou_comment);

                setTimeout(function(buy_list, wallet)
                {
                    // ウォレットの設定
                    $('.money-value').html(wallet);

                    // 購入可能リストの設定
                    setBuyList(buy_list, wallet);

                    // 店内表示
                    $('.contents-box').show();
                    $('.entrance-box').show();

                    // 吹き出しの設定
                    $('.talk').html(opts.welcome_comment);

                    // ローディング非表示
                    $('.loading-img').hide();
                }, 2000, data.buy_list, data.wallet);
            }
            else
            // 売却登録コマンド
            if(data.cmd === 'shop-sell-entry')
            {
                // 売却リストの設定
                setSellList(data.sell_list);

                // 売却・返却ボタン共に活性化する
                $('#btn-sell').prop('disabled', false);
                $('#btn-sell-release').prop('disabled', false);
            }
            else
            // 売却コマンド
            if(data.cmd === 'shop-sell')
            {
                // 吹き出しの設定
                $('.talk').html(opts.sold_comment);

                setTimeout(function(sell_list, buy_list, wallet)
                {
                    // ウォレットの設定
                    $('.money-value').html(wallet);

                    // 売却リストの設定
                    setSellList(sell_list);

                    // 購入可能リストの設定
                    setBuyList(buy_list, wallet);

                    // 売却・返却ボタン共に、リストがあれば活性化する
                    if(sell_list.length > 0)
                    {
                        $('#btn-sell').prop('disabled', false);
                        $('#btn-sell-release').prop('disabled', false);
                    }

                    // 店内表示
                    $('.contents-box').show();
                    $('.entrance-box').show();

                    // 吹き出しの設定
                    $('.talk').html(opts.welcome_comment);

                    // ローディング非表示
                    $('.loading-img').hide();
                }, 2000, data.sell_list, data.buy_list, data.wallet);
            }
            else
            // 返却済みコマンド
            if(data.cmd === 'shop-sell-release')
            {
                // 吹き出しの設定
                $('.talk').html(opts.released_comment);

                setTimeout(function(sell_list)
                {
                    // 売却リストの設定
                    setSellList(sell_list);

                    // 店内表示
                    $('.contents-box').show();
                    $('.entrance-box').show();

                    // 吹き出しの設定
                    $('.talk').html(opts.welcome_comment);

                    // ローディング非表示
                    $('.loading-img').hide();
                }, 2000, data.sell_list);
            }
        };

        /**
         * 切断検知のイベント
         * 
         * @param {*} event イベントインスタンス
         * @returns 
         */
        websocket.onclose = function(event)
        {
            console.log(`Websocket切断情報[code=${event.code} reason=${event.reason}]`);

            if(flg_error === true)
            {
                return;
            }

            let talk = '・・・';
            if(event.wasClean)
            {
                // 自身の退室による切断
                if(event.code === CHAT_CLIENT_CLOSE_CODE)
                {
                    talk = opts.exit_comment;
                }
                // サーバーからの切断
                else
                if(event.code === CHAT_SERVER_CLOSE_CODE)
                {
                    talk = opts.server_close_comment;
                }
                // サーバーからの切断（ユーザー名重複）
                else
                if(event.code === CHAT_DUPLICATION_CLOSE_CODE)
                {
                    talk = opts.duplication_comment;
                }
                // サーバーからの切断（ユーザー名なし）
                else
                if(event.code === CHAT_NO_USER_CLOSE_CODE)
                {
                    talk = opts.no_user_comment;
                }
                // 不明
                else
                {
                    talk = opts.unexpected_close_comment;
                }
            }
            else
            {
                talk = opts.forced_close_comment;
            }

            // システム初期化
            systemInit(talk);
        };
    
        /**
         * エラー検知のイベント
         * 
         * @param {*} error エラーインスタンス
         */
        websocket.onerror = function(error)
        {
            flg_error = true;

            let error_message = opts.error_comment;
            if(typeof(error.message) !== 'undefined')
            {
                error_message = error.message;
            }
            console.log(`エラー発生[${error_message}]`);

            // システム初期化
            systemInit(error_message);
        };
    }

    /**
     * システム初期化
     * 
     * @param {string} talk - 吹き出しコメント
     * @returns {void}
     */
    function systemInit(talk)
    {
        // 「入店する」ボタンへ変更
        $('#connect_button').text('入店する');

        // ユーザー名入力を許可
        $('input[name="user"]').prop('disabled', false);

        // URI入力を許可
        $('input[name="uri"]').prop('disabled', false);

        // 吹き出しセット
        $('.talk').html(talk);

        // 吹き出しボタン非表示
        $('.talk-button-box').hide();

        // 店内非表示
        $('.contents-box').hide();
        $('.entrance-box').hide();

        // ローディング非表示
        $('.loading-img').hide();

        // 初回入室フラグの戻し
        flg_first_entrance = true;

        // Websocketインスタンスをクリア
        websocket = null;
    }

    /**
     * 購入可能リストの設定
     * 
     * @param {Array} list - 購入可能リストデータ
     * @param {number} wallet - 所持金
     * @returns {void}
     */
    function setBuyList(list, wallet)
    {
        $('.buy-list').html('');

        for(let [key, val] of Object.entries(list))
        {
            // ラジオボタンの設定
            let disabled = true;
            if(val.price <= wallet)
            {
                disabled = false;
            }
            $('.buy-item-hidden .buy-radio').prop('disabled', disabled);

            // ラジオボタンの設定
            $('.buy-item-hidden .buy-radio').prop('id', val.id);

            // ラジオボタンの設定
            $('.buy-item-hidden .buy-radio').prop('value', val.id);

            // ラベルの設定
            $('.buy-item-hidden .buy-item').prop('for', val.id);

            // アイコンの設定
            $('.buy-item-hidden .buy-item .buy-item-icon img').prop('src', val.icon);

            // アイテム名の設定
            $('.buy-item-hidden .buy-item .buy-item-name').html(val.name);

            // アイテム価格の設定
            $('.buy-item-hidden .buy-item .buy-item-price').html(val.price);

            let html = $('.buy-item-hidden').html();
            $('.buy-list').append(html);
        };
    }

    /**
     * 売却リストの設定
     * 
     * @param {Array} list - 購入可能リストデータ
     * @returns {void}
     */
    function setSellList(list)
    {
        $('.sell-list').html('');

        let total_price = 0;

        for(let [key, val] of Object.entries(list))
        {
            // アイコンの設定
            $('.sell-item-hidden .sell-item .sell-item-icon img').prop('src', val.icon);

            // アイテム名の設定
            $('.sell-item-hidden .sell-item .sell-item-name').html(val.name);

            // アイテム数の設定
            $('.sell-item-hidden .sell-item .sell-item-count').html(val.count);

            // アイテム価格の設定
            $('.sell-item-hidden .sell-item .sell-item-price').html(val.price);
            total_price += val.price;

            let html = $('.sell-item-hidden').html();
            $('.sell-list').append(html);
        };

        $('.sell-total-value').html(total_price);
    }
});
