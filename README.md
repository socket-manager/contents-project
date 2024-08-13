# CONTENTS-PROJECT on SOCKET-MANAGER Framework
マインクラフト専用のコンテンツに特化したプロジェクト環境です。

<img src="https://socket-manager.github.io/document/minecraft-contents/img/bow-mine/thunder_cannon.gif" />

<img src="https://socket-manager.github.io/document/minecraft-contents/img/bow-mine/explode_gatling_min.gif" />

## 運用ポリシー
この環境はWebsocketサーバーを使った運用を前提としています。<br />
スコアボードやコマンドを駆使して四苦八苦しながらイベントを判定したりするのを避け、サーバープログラミングを使って極力運用の負担を減らそうというのが狙いです。<br />
基本的にはファミリー向けに楽しく遊べるものを目指しているので、グローバルコンテンツや商用利用は今のところ考えていません。

※この環境はデモ環境（"demo-project"）をベースに構築していますので、サーバーやクライアントの起動方法はデモ環境と全く同じです。また、チャット機能もそのまま引き継いでいます。

## サーバーの起動
マインクラフトを含めたチャットサーバーの場合は、プロジェクトルートディレクトリで以下のコマンドを実行すれば起動できます。

<pre>
> php worker app:minecraft-chat-server [<ポート番号>]
</pre>

## クライアントの起動
### ブラウザの場合
以下のディレクトリにjQuery/React版のHTMLファイルが入っていますのでお好きな方をブラウザにドラッグ＆ドロップしてください（Webサーバーを起動する必要はありません）。

/app/client/jquery/chat.html（jQuery版）<br />
/app/client/react/chat.html（React版）

### マインクラフトの場合
マインクラフトのチャット画面で以下のコマンドを実行すれば接続できます。

<pre>
> /wsserver localhost:10000/<ユーザー名>
</pre>

サーバーへ接続後は普通にチャットできます。<br />
以下のフォーマットで入力すれば特定のユーザーへプライベートコメントが送信できます。

<pre>
> <メッセージ>#<宛先ユーザー名>
</pre>

※マインクラフトはUWPアプリのため以下のコマンドを実行してループバックアドレスへのアクセスを許可しておく必要があります。

<pre>
> CheckNetIsolation.exe LoopbackExempt -a -n="Microsoft.MinecraftUWP_8wekyb3d8bbwe"
</pre>

## 補足
詳しい実装内容やコンテンツの詳細は<a href="https://socket-manager.github.io/document/minecraft-contents/">こちら</a>をご覧ください。

このプロジェクトにはデモ環境と同じ６種類のサーバーをご用意しています。<br />
詳しい使い方は<a href="https://socket-manager.github.io/document/extra-demo.html">こちら</a>をご覧ください。

このプロジェクトはLaravelと連携できます。<br />
詳しい連携方法は<a href="https://socket-manager.github.io/document/laravel.html">こちら</a>をご覧ください。

## Contact Us

バグ報告やご要望などは<a href="mailto:lib.tech.engineer@gmail.com">`こちら`</a>から受け付けております。
