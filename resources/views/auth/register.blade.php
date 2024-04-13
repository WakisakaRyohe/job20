@php
    $title = '会員登録';
@endphp

@extends('app')

@section('content')
<div class="p-liogin c-inner--s">
    <h2>{{ __('Register') }}</h2>

    <form class="c-form" action="{{ route('register') }}" method="post">
    @csrf

        {{-- 名前 --}}
        <div class="c-form__item">
            <div class="c-form__labelArea">
                <label for="name">{{ __('Name') }}</label><br>
            </div>
            <input id="name" type="text" class="@error('name') c-form__errInput @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
                <span class="c-table__errMsg" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div class="c-form__item">
            <div class="c-form__labelArea">
                <label for="email">{{ __('E-Mail Address') }}</label><br>
            </div>
            <input id="email" type="email" class="@error('email') c-form__errInput @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="c-table__errMsg" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
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

        {{-- パスワード（再入力） --}}
        <div class="c-form__item">
            <div class="c-form__labelArea">
                <label for="password-confirm">{{ __('Confirm Password') }}</label><br>
            </div>
            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <!-- 登録ボタン -->
        <div class="c-form__item">
            <input type="submit" class="c-btn" value="{{ __('Register') }}">
        </div>

    </form>
</div>
@endsection
