プロセスID: {{ $data['pid'] }}
発生日時: {{ $data['date'] }}
対象バッチ: {{ $data['batch_name'] }}
@isset($data['target_table'])
対象テーブル: {{ $data['target_table'] }}
@endisset
@isset($data['target_file'])
対象ファイル: {{ $data['target_file'] }}
@endisset

【エラー内容】
@foreach ($data['errors'] as $error)
{!! $error !!}

@endforeach
