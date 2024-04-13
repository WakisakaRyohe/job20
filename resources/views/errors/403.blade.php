@php
$title = '403エラーページ';
header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>Forbidden</h2>

<div style="text-align:center;">
	閲覧権限が無いページです。<br>５秒後に検索ページへ移動します。
</div>
@endsection
