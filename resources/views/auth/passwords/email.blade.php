@php
$title = 'パスワード再設定';
@endphp

@extends('app')

@section('content')
<h2>{{ __('Reset Password') }}</h2>

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<form class="c-form" action="{{ route('password.email') }}" method="post">
    @csrf

    {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}

    {{-- メールアドレス --}}
    <div class="c-form__item">
        <label for="email">{{ __('E-Mail Address') }}</label><br>
        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- 登録ボタン -->
    <div class="c-form__item">
        <input type="submit" class="c-btn  c-btn--l" value="{{ __('Send Password Reset Link') }}">
    </div>

</form>
@endsection
