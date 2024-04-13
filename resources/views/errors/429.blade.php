@php
$title = '429エラーページ';
header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>Too Many Requests</h2>

<div style="text-align:center;">
	一定時間内にリクエスト数が多すぎるためアクセスを拒否しました。<br>５秒後に検索ページへ移動します。
</div>	
@endsection
