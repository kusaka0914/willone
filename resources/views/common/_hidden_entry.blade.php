<input type="hidden" name="t" value="{{ $t }}">
<input type="hidden" name="action" id="action" value="{{ $action }}">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="birth_day" value="1">
<input type="hidden" name="birth_month" value="1">
<input type="hidden" name="entry_order" value="{{ $entry_order }}">
<input type="hidden" name="entry_category_manual" value="">
<input type="hidden" name="job_id" value="{{ $job_id }}">
<input type="hidden" name="agreement_flag" value="1">
<input type="hidden" name="utm_source" value="{{ $utm_source }}">
<input type="hidden" name="utm_medium" value="{{ $utm_medium }}">
<input type="hidden" name="utm_campaign" value="{{ $utm_campaign }}">
<input type="hidden" name="tracking_url" value="{{ $tracking_url }}">
<input type="hidden" name="not_move" id="not_move" value="{{ implode(',', array_keys(config('ini.STATE_NOT_MOVE'))) }}">
<input type="hidden" name="branch" id="branch_data" value="{{ $branch ?? '' }}">
<input type="hidden" name="cp" value="{{ $cp ?? '' }}">
<input type="hidden" name="referral_salesforce_id" value="{{request()->input('friend')}}" >
