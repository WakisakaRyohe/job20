@php
$title = '419エラーページ';
// header( "refresh:5;url = http://localhost:8000/search" );
@endphp

@extends('app')

@section('content')
<h2>CSRF対策に失敗</h2>

<div style="text-align:center;">
	POST通信の際のCSRF対策に失敗しました。<br>５秒後に検索ページへ移動します。
</div>
@endsection
