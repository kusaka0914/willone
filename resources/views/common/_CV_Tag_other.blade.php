{{-- アフィリエイト（ASPレントラックス） --}}
@if(!empty($is_target_rentracks_result_tag) && $is_target_rentracks_result_tag === true && !empty($name_kan) && (mb_truncate($name_kan,5,"") != '【テスト】' || mb_truncate($name_kan,7,"") == '【テスト】タグ' ))
<script type="text/javascript">
    (function(){
    function loadScriptRTCV(callback){
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://www.rentracks.jp/js/itp/rt.track.js?t=' + (new Date()).getTime();
    if ( script.readyState ) {
    script.onreadystatechange = function() {
    if ( script.readyState === 'loaded' || script.readyState === 'complete' ) {
    script.onreadystatechange = null;
    callback();
    };
    };
    } else {
    script.onload = function() {
    callback();
    };
    };
    document.getElementsByTagName('head')[0].appendChild(script);
    }

    loadScriptRTCV(function(){
    _rt.sid = 9487;
    _rt.pid = 13472;
    _rt.price = 0;
    _rt.reward = -1;
    _rt.cname = '';
    _rt.ctel = '';
    _rt.cemail = '';
    _rt.cinfo = '{{ $customer_id }}';
    rt_tracktag();
    });
    }(function(){}));
</script>
@endif
