@php
$title = '500エラーページ';
header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>Internal Server Error</h2>

<div style="text-align:center;">
	サーバ内で何らかのエラーが発生しました。<br>５秒後に検索ページへ移動します。
</div>	
@endsection
