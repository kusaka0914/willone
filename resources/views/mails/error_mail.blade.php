@isset($data['pid'])
プロセスID: {{ $data['pid'] }}
@endisset
@isset($data['date'])
発生日時: {{ $data['date'] }}
@endisset
@isset($data['url'])
URL: {{ $data['url']}}
@endisset
@isset($data['referer'])
Referer: {{ $data['referer']}}
@endisset
@isset($data['ip'])
IP: {{ $data['ip']}}
@endisset
@isset($data['ua'])
UA: {{ $data['ua']}}
@endisset

【エラー内容】
{!! $data['message'] !!}
@isset($data['code'])
{{ $data['class']}}(code: {{ $data['code'] }})
@endisset
@isset($data['line'])
{{ $data['file']}}({{ $data['line'] }})
@endisset
@isset($data['trace'])
[stacktrace]
{!! $data['trace'] !!}
@endisset
