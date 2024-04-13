@php
    $title = 'ログイン';
@endphp

@extends('app')

@section('content')
<div class="p-liogin c-inner--s">
    <h2>{{ __('Login') }}</h2>

    <form class="c-form" action="{{ route('login') }}" method="post">
    @csrf

        {{-- エラーメッセージ --}}
        @error('email')
        <div class="c-form__item">
            <span class="c-table__errMsg" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        </div>
        @enderror

        {{-- メールアドレス --}}
        <div class="c-form__item">
            <div class="c-form__labelArea">
                <label for="email">{{ __('E-Mail Address') }}</label><br>
            </div>
            <input id="email" type="email" class="@error('email') @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        </div>

        {{-- パスワード --}}
        <div class="c-form__item">
            <div class="c-form__labelArea">
                <label for="password">{{ __('Password') }}</label><br>
            </div>
            <input id="password" type="password" class="@error('password') c-form__errInput @enderror" name="password" value="{{ old('password') }}" required autocomplete="current-password">
            @error('password')
                <span class="c-table__errMsg" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- パスワードを忘れた場合 --}}
        @if (Route::has('password.request'))
        <div class="c-form__item">
            <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        </div>
        @endif
        
        <!-- ログインボタン -->
        <div class="c-form__item">
            <input type="submit" class="c-btn" value="{{ __('Login') }}">
        </div>

    </form>
</div>
@endsection
