@php
$title = '503エラーページ';
header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>Service Unavailable</h2>

<div style="text-align:center;">
	一時的にサーバにアクセスが出来ません。<br>５秒後に検索ページへ移動します。
</div>	
@endsection
