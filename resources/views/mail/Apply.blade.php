<br>
{{ config('app.name') }}をご利用いただき、誠にありがとうございます。
<br>
<br>
下記の求人に応募しました。
<br>
<br>
【{{ $params['jobName']  }} 】
<br>
<br>
●求人詳細ページは<a href="{{ config('app.url') }}job/{{ $params['jobId'] }}">こちら</a>
<br>
<br>
<a href="{{ config('app.url') }}bord/{{ $params['bordId'] }}">メッセージ投稿ページ</a>で会社とのやりとりを進めましょう。
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