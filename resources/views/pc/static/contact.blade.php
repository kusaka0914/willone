@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <h1 class="p-about-head">お問い合わせ</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <form method="post" action="{{ route('ContactComp') }}" class="u-mt0">
                    {{ csrf_field() }}
                    <h2 class="c-title-l-pink u-mb0 u-mt0">
                        お問い合わせ
                    </h2>
                    <div class="c-form-base">
                        <div class="c-form-base-wrap">
                            <div style="margin-top: 10px;">
                                @if (count($errors))
                                <div style="padding: 10px; margin: 20px; font-size: 1.5rem; color: #a94442; background-color: #f2dede; border: 2px solid #ebccd1;" id="errorMsg">
                                    @foreach ($errors->all() as $message)
                                    <p>●{{$message}}</p>
                                    @endforeach
                                </div>
                                @else
                                <div style="display: none;" id="errorMsg"></div>
                                @endif
                            </div>
                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">ご用件</p>
                                <label class="c-form-base-select-ic">

                                    <select name="type" class="c-form-base-select">
                                        @foreach($type_list as $key => $value)
                                        <option label="{{ $value }}" value="{{ $key }}" @if($key==1) checked @endif @if(old('type')==$key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">お名前【必須】</p>
                                <p><input type="text" name="name" placeholder="名前を入力してください" class="c-form-base-txt" value="{{ old('name')}}"></p>
                                <p><input type="text" name="kana_name" placeholder="フリガナを入力してください" class="c-form-base-txt" value="{{ old('kana_name')}}"></p>
                            </div>
                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">メールアドレス【必須】</p>
                                <p><input type="text" name="email" placeholder="メールアドレスを入力してください" class="c-form-base-txt" value="{{ old('email')}}"></p>
                            </div>
                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">住所【必須】</p>
                                <p><input type="text" name="postcode" placeholder="郵便番号を入力してください" class="c-form-base-txt" value="{{ old('postcode')}}"></p>
                                <p><input type="text" name="address" placeholder="住所を入力してください" class="c-form-base-txt" value="{{ old('address')}}"></p>
                            </div>
                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">電話番号【必須】</p>
                                <p><input type="text" name="tel" placeholder="電話番号を入力してください" class="c-form-base-txt" value="{{ old('tel')}}"></p>
                            </div>
                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">年齢【数字のみで入力してください】</p>
                                <p><input type="text" name="age" placeholder="年齢を入力してください" class="c-form-base-txt" value="{{ old('age')}}"></p>
                            </div>

                            <div class="c-form-base-detail">
                                <p class="c-form-base-title">性別</p>
                                <div class="c-form-base-radio">
                                    @foreach($seibetsu_list as $key => $value)
                                    <input type="radio" name="seibetsu" id="select{{$key}}" value="{{$key}}" @if($key==1) checked @endif @if(old('seibetsu')==$key) checked @endif>
                                    <label for="select{{$key}}">{{$value}}</label>
                                    @endforeach
                                </div>
                                <div class="c-form-base-detail">
                                    <p class="c-form-base-title">お問い合わせ内容</p>
                                    <textarea name="toiawase" class="c-form-base-txtbox">{{ old('toiawase')}}</textarea>
                                </div>
                                <div class="c-form-base-detail">
                                    <p>
                                        【個人情報の取扱いについて】</br>
                                        ・本フォームからお客様が記入・登録された個人情報は、資料送付・電子メール送信・電話連絡などの目的で利用・保管します。</br>
                                        ・<a href="/woa/privacy">プライバシーポリシー</a>に同意の上、下記ボタンを押してください。
                                    </p>
                                </div>
                            </div>
                            <div class="c-form-base-button-wrap">
                                <input type="submit" value="この内容で問い合わせる" class="c-form-base-button">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @include('pc.mainparts.normalsidebar')
        </div>

    </main>
    @include('pc.mainparts.bodyfooter')

    @include('pc.mainparts.topscript')
</body>
@endsection
