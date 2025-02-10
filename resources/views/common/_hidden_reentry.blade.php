<input type="hidden" name="action" value="{{ $action }}" >
<input type="hidden" name="btn_action" id="btn_action" value="" >
<input type="hidden" name="user" value="{{ $user }}" >
<input type="hidden" name="req_date" id="req_date" value="" >
<input type="hidden" name="req_emp_type" id="req_emp_type" value="" >
<input type="hidden" name="retirement_intention" id="retirement_intention" value="" >
<input type="hidden" name="t" value="{{ $t }}" >
<input type="hidden" name="client_id" value="{{ $client_id ?? '' }}" >
<input type="hidden" name="_token" value="{{ csrf_token() }}"> {{-- トークンチェック設定 --}}
