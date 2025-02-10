応募がありました。

登録情報

・名前    {{ $sendData['name_kan']}}
・ふりがな  {{ $sendData['name_cana']}}
・資格　　{{ $sendData['license']}}
・卒業年　@if($sendData['graduation_year'] != "")　{{ $sendData['graduation_year']}}
@else -----
@endif
・希望の働き方 {{ $sendData['req_emp_type']}}
・希望の転職時期 {{ $sendData['req_date']}}
・生まれ年  {{ $sendData['birth_year']}}
・退職意向　{{ $sendData['retirement_intention']}}
・電話番号　{{ $sendData['tel']}}
・メールアドレス {{ $sendData['mail']}}
・郵便番号　{{ $sendData['zip']}}
・住所　{{ $sendData['addr1']}}{{ $sendData['addr2']}}{{ $sendData['addr3']}}
・応募JOBID {{ $sendData['entry_order']}}
・登録ホスト {{ $sendData['ip']}}
・登録ユーザエージェント　{{ $sendData['ua']}}
・登録カテゴリ（手動入力）　{{ $sendData['entry_category_manual']}}
