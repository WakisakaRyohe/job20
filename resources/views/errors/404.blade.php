@php
$title = '404エラーページ';
header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>Not Found</h2>

<div style="text-align:center;">
	Webページが見つかりません。<br>５秒後に検索ページへ移動します。
</div>
@endsection
