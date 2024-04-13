<br>
いつも{{ config('app.name') }}をご利用いただき、誠にありがとうございます。
<br>
<br>
{{ $params['sender_name'] }}から、メッセージが届きました。
<br>
<br>
━━━━━━━━━━━━━━━━━
<br>
メッセージ内容：<pre>{{ $params['message'] }}</pre>
<br>
━━━━━━━━━━━━━━━━━
<br>
{{ $params['sender_name'] }}とのメッセージ画面はこちら
<br>
<a href="{{ config('app.url') }}bord/{{ $params['bord_id'] }}">{{ config('app.url') }}bord/{{ $params['bord_id'] }}</a>
<br>
━━━━━━━━━━━━━━━━━
<br>
<br>
スムーズにやり取りを進めるためにも、お早めの返信をお願いいたします。
<br>
<br>
※本メールは送信専用のメールアドレスから送信しております。ご返信できませんのでご了承ください。
<br>
<br>
////////////////////////////////////////
<br>
{{ config('app.name') }}運営事務局
<br>
URL：<a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
<br>
E-mail：mail@wakisakaryohei.com
<br>
////////////////////////////////////////