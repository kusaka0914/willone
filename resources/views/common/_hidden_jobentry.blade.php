<input type="hidden" name="orderId" value="{{ $recommendOrder->sf_order_id }}">
<input type="hidden" name="user" value="{{ $user }}">
<input type="hidden" name="exceptReentryFrag" value="{{ $exceptReentryFrag }}">
<input type="hidden" name="action" value="{{ $action }}">
<input type="hidden" name="mailmgzFlag" value="1">
<input type="hidden" name="entryStatus" value="0">
<input type="hidden" name="_token" value="{{ csrf_token() }}"> {{-- トークンチェック設定 --}}
