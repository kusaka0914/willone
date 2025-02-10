<tr>
    <th class="u-w20">資格</th>
    <td colspan="2" class="c-explore-td">
        @foreach(config('ini.JOB_TYPE_GROUP') as $key => $value)
            @if(!empty($type_roma) && $type_roma === $key)
                @continue
            @endif
            <div class="c-explore-box">
                <a href="{{ getJobListPageUrl(null, $key, $pref_roma, $state_roma ?? null) }}@if(!empty($query_string)){{ $query_string }}@endif" class="c-explore-link">
                    {{-- JINZAI-12923 --}}
                    {{ $value['name'] }}
                </a>
            </div>
        @endforeach
    </td>
</tr>
<tr>
    <th class="u-w20">最寄りの市区町村</th>
    <td colspan="2" class="c-explore-td c-explore-td--municipalities">
        @include('common.loading')
        <div id="search-near-cities" class="c-explore-wrap">
            @foreach($search_near_cities as $value)
                <div class="c-explore-box">
                    <a href="{{ $value['target_url'] }}@if(!empty($query_string)){{ $query_string }}@endif" class="c-explore-link"{!! getRelNofollowAttrText($value['addr2_count']) !!}>{{ $value['addr2_name'] }}</a>
                </div>
            @endforeach
        </div>
    </td>
</tr>
<tr>
    <th>フリーワード</th>
    <td>
        <div class="c-explore-word">
            <input type="text" name="freeword" placeholder="会社名・フリーワードを入力" value="{{(!empty($freeword)) ? $freeword : ''}}">
            <input class="c-button-search-word" type="submit" name="" value="検索する">
        </div>
    </td>
</tr>
