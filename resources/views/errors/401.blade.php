@php
$title = '401エラーページ';
header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>Unauthorized</h2>

<div style="text-align:center;">
	アクセス権がありません。または認証に失敗しました。<br>５秒後に検索ページへ移動します。
</div>
@endsection
