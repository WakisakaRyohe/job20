<br>
{{ config('app.name') }}をご利用いただき、誠にありがとうございます。
<br>
<br>
本メールアドレス宛にパスワード再発行のご依頼がありました。
<br>
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。
<br>
<br>
パスワード再発行認証キー入力ページ
<br>
<a href="{{ config('app.url') }}password_remind_receive">{{ config('app.url') }}password_remind_receive</a>
<br>
<br>
認証キー：{{ $auth_key }}
<br>
<br>
※認証キーの有効期限は30分となります。
<br>
期限切れの場合は、お手数ですが、下記ページでもう一度認証キーを発行していただけますようお願いいたします。
<br>
<br>
URL：<a href="{{ config('app.url') }}password_remind_send">{{ config('app.url') }}password_remind_send</a>
<br>
<br>
※本メールは送信専用のメールアドレスから送信しております。ご返信できませんのでご了承ください。
<br>
<br>
////////////////////////////////////////////////////////////////////////////////
<br>
{{ config('app.name') }}運営事務局
<br>
URL：<a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
<br>
E-mail：mail@wakisakaryohei.com
<br>
////////////////////////////////////////////////////////////////////////////////