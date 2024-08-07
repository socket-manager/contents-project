/**
 * コマンド入力関連コンポーネント
 * 
 * @namespace
 */
let command =
{
    /**
     * ポチるボタン
     * 
     * @returns {Object}
     */
    Button: function()
    {
        /**
         * クリック時のイベントハンドラ
         * 
         * @returns {void} なし
         */
        function ClickButton()
        {
            let data =
            {
                  'cmd': 'execute-command'
                , 'user': global_data.command.user
                , 'command': global_data.command.command
            };
            global_data.websocket.send(JSON.stringify(data));
            global_func.command.setGuide('');
        }
    
        // ボタンエレメント生成
        let ret = React.createElement
        (
            "button",
            {
                id: "command_send_button",
                disabled: global_data.command.disabled,
                onClick: ClickButton
            },
            'ポチる'
        );
        return ret;
    },
    /**
     * 入力フォーム
     * 
     * @returns {Object}
     */
    Form: function()
    {
        /**
         * disabled切り替えのフック
         * 
         * @type {Array.<Object>}
         */
        [global_data.command.disabled, global_func.command.setDisabled] = global_func.useState(true);

        /**
         * コマンド入力フィールド
         * 
         * @returns {Object}
         */
        function InputCommand()
        {
            /**
             * コマンドのフック
             * 
             * @type {Array.<Object>}
             */
            [global_data.command.command, global_func.command.setCommand] = global_func.useState('');

            /**
             * 変更時のイベントハンドラ
             * 
             * @param {Object} e - イベント情報
             * @returns {void} なし
             */
            function OnChange(e)
            {
                global_func.command.setCommand(e.target.value);
            }

            // コマンド入力のエレメント生成
            let ret = React.createElement
            (
                "input",
                {
                    className: "command",
                    type: "text",
                    name: "command",
                    disabled: global_data.command.disabled,
                    onChange: OnChange,
                    defaultValue: global_data.command.command,
                    value: global_data.command.command,
                    placeholder: "コマンド入力"
                }
            );
            return ret;
        }
    
        /**
         * ユーザー名入力フィールド
         * 
         * @returns {Object}
         */
        function InputUser()
        {
            /**
             * 宛先ユーザーのフック
             * 
             * @type {Array.<Object>}
             */
            [global_data.command.user, global_func.command.setUser] = global_func.useState('');

            /**
             * 変更時のイベントハンドラ
             * 
             * @param {Object} e - イベント情報
             * @returns {void} なし
             */
            function OnChange(e)
            {
                global_func.command.setUser(e.target.value);
            }

            // ユーザー入力のエレメント生成
            let ret = React.createElement
            (
                "input",
                {
                    className: "command command-user",
                    type: "text",
                    name: "command-user",
                    disabled: global_data.command.disabled,
                    onChange: OnChange,
                    defaultValue: global_data.command.user,
                    value: global_data.command.user,
                    placeholder: "宛先"
                }
            );
            return ret;
        }
    
        // 入力フォームブロックのエレメント生成
        let ret = React.createElement
        (
            "div",
            {
                className: "command-div",
            },
            InputCommand(),
            InputUser(),
            command.Button()
        );
        return ret;
    },
    /**
     * メッセージガイド
     * 
     * @returns {Object}
     */
    Guide: function()
    {
        /**
         * メッセージガイドのフック
         * 
         * @type {Array.<Object>}
         */
        [global_data.command.guide, global_func.command.setGuide] = global_func.useState('');

        /**
         * メッセージガイドCSSのクラス名フック
         * 
         * @type {Array.<Object>}
         */
        [global_data.command.guide_class, global_func.command.setGuideClass] = global_func.useState('');

        // メッセージガイドブロックのエレメント生成
        let ret = React.createElement
        (
            "p",
            {
                id: "command_reply",
                className: global_data.command.guide_class
            },
            global_data.command.guide
        );
        return ret;
    }
};
