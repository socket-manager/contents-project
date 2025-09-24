# CONTENTS-PROJECT：マインクラフト専用環境プロジェクト
マインクラフト向けのコンテンツ開発と運用を支援するプロジェクト環境です。WebSocket ベースの通信でサーバー側ロジックを活用し、スコアボードや複雑なコマンド運用に頼らずにコンテンツを実装できます。

<!-- 重要なキーワードを冒頭に含めることで README の検索性を向上します -->
- 対象: Minecraft（UWP 含む）
- 技術: WebSocket, PHP, Laravel（連携可能）
- 用途: チャットサーバー、ゲームコンテンツ、イベント運用

<img alt="「機雷の弓」を使って「いなずまの矢」の効果を同時発動" src="https://socket-manager.github.io/document/minecraft-contents/img/bow-mine/thunder_cannon.gif" />
<img alt="「機雷の弓」を使って「はかいの矢」の効果を同時発動" src="https://socket-manager.github.io/document/minecraft-contents/img/bow-mine/explode_gatling_min.gif" />

## 【 概要 】
CONTENTS-PROJECT は、Minecraft 向けのコンテンツを容易に作成・運用できる環境を提供します。サーバー側でイベント判定やゲームロジックを処理することで、運用の負担を軽減し、安定したマルチプレイヤー体験を実現します。

## 【 特徴 】
- WebSocket によるリアルタイム通信
- サーバー側でのイベント管理により、クライアント側の複雑なコマンド処理を不要に
- jQuery / React のクライアントサンプルを同梱
- Laravel と連携可能（既存の Laravel プロジェクトへ組み込み可）
- デモ環境での簡単な起動手順を提供

## 【 運用ポリシー 】
この環境は WebSocket サーバーを利用した運用を前提としています。スコアボードや複雑なコマンド判定に頼らず、サーバーサイドのプログラムでイベントや状態管理を行うことで、運用負荷の低減を目指します。基本的にファミリー向けで楽しく遊べるコンテンツを想定しており、現時点ではグローバル公開や商用利用を主目的としていません。

## 【 デモとベースプロジェクトについて 】
このプロジェクトはデモ環境（"demo-project"）をベースに構築しています。サーバーやクライアントの起動手順はデモ環境と同一です。詳細な実装やサンプルはドキュメントを参照してください。

## 【 サーバーの起動 】
プロジェクトルートで以下（コンテンツサーバーの例）を実行してください。

```bash
php worker app:minecraft-chat-server [<ポート番号>]
```

デフォルトポートや追加オプションはドキュメントを参照してください。

## 【 クライアントの起動 】

### ブラウザ
jQuery / React のサンプル HTML を用意しています。任意のブラウザでファイルを開くか、静的ファイルサーバーに配置してアクセスしてください。

- /app/client/jquery/chat.html（jQuery 版チャット画面）
- /app/client/jquery/shop.html（jQuery 版アイテムショップ画面）
- /app/client/react/chat.html（React 版チャット画面）

### Minecraft（UWP）の接続方法
Minecraft 内のチャット画面から以下のコマンドを実行して接続できます。

```text
/wsserver localhost:10000/<ユーザー名>
```

接続後は通常のチャット操作が可能です。特定ユーザーへのプライベートメッセージは以下のフォーマットで送信できます。

```text
<メッセージ>#<宛先ユーザー名>
```

注意: Minecraft（UWP）はループバック制限があるため、Windows 上で接続する場合は以下のコマンドでループバックアクセスを許可してください。

```powershell
CheckNetIsolation.exe LoopbackExempt -a -n="Microsoft.MinecraftUWP_8wekyb3d8bbwe"
```

## 【 補足 / ドキュメント 】
詳細な実装やコンテンツの一覧はドキュメントを参照してください  
https://socket-manager.github.io/document/minecraft-contents/

デモ環境の詳細な使い方  
https://socket-manager.github.io/document/extra-demo.html

Laravel 連携方法  
https://socket-manager.github.io/document/laravel.html

## 【 Contact Us 】
バグ報告やご要望などは<a href="mailto:lib.tech.engineer@gmail.com">`こちら`</a>から受け付けております。

## 【 License 】
MIT, see <a href="https://github.com/socket-manager/contents-project/blob/main/LICENSE">LICENSE file</a>.