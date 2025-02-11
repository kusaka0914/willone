API実行時にエラーが発生しました。
@if (isset($sendData['customer_id']) && !empty($sendData['customer_id']))

※以下のSQLを実行して、入力内容をご確認ください。
SELECT * FROM woa_customer WHERE id={{$sendData['customer_id']}}\G
@endif

【エラー内容】
{{$sendData['error_message']}}

※詳細は、以下のログをご確認ください。
/var/www/woa/storage/logs/laravel.log
