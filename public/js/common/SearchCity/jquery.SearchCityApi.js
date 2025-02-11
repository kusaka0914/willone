jQuery(document).ready(function($) {
    var SearchCity = function() {};

    SearchCity.dst_pref = "";
    SearchCity.dst_city = "";
    SearchCity.dst_city_num = 0;
    SearchCity.dst_address = "";
    SearchCity.src_zip = "";
    SearchCity.isManagerOffSearch = false;

    /**
     * 初期設定
     * 都道府県市区町村番地建物名のID保持します。
     * 必ず最初に実行してください。
     */
    $.fn.searchCityInit = function(src_zip,dst_pref,dst_city,dst_address) {
        SearchCity.src_zip = "#" + src_zip;
        SearchCity.dst_pref = "#" + dst_pref;
        SearchCity.dst_city = "#" + dst_city;
        SearchCity.dst_address = "#" + dst_address;
        if ($('#form_addr2').val()) {
            $(document).changeCityListSyncAddr2();
        } else {
            $(document).changeCityListSync();
        }
    };

    /**
     * 都道府県プルダウンのチェンジイベント
     */
    $("select").change(function () {
        if ("#" + $(this).attr("id") == SearchCity.dst_pref) {
            $(document).changeCityListSync();

            // 希望勤務都道府県
            setReq1Addr1();
        }
    });

    /**
     * 希望勤務都道府県（第一希望）を設定
     *
     * 以下のパターンのときに設定
     * 1.希望勤務都道府県が選択されていない状態で郵便番号検索を実行
     * 2.希望勤務都道府県が選択されていない状態で都道府県を選択した場合に設定
     */
    var setReq1Addr1 = function () {
        // 希望勤務都道府県（第一希望）
        if (typeof $('#req1_addr1').val() !== undefined) {
            if ($('#req1_addr1').val() == '' && $(SearchCity.dst_pref).val()) {
                $('#req1_addr1').val($(SearchCity.dst_pref).val());
                $('#req1_addr1').css({"color":"#333"});
                $('#req1_addr1').addClass('on');
            }
        }
    };

    /**
     * 都道府県に該当する市区町村の一覧を取得
     */
    $.fn.changeCityListSync = function() {
        var _pref = $(SearchCity.dst_pref).val();
        // 検索
        $.ajax({
            url: "/woa/api/getCity",
            type: "POST",
            async: false,
            data: {
              'addr1' : _pref
            },
            dataType: "json",
            success: function(value){
                if (value) {
                    if(_pref == "") {
                        $(SearchCity.dst_city).disabled = true;
                    }else{
                        $(SearchCity.dst_city).disabled = false;
                    }
                    Callback(value);
                }
            }
        });
    };

    /**
     * 都道府県に該当する市区町村の一覧を取得
     */
    $.fn.changeCityListSyncAddr2 = function() {
        $.fn.changeCityListSync();

        // 市区町村を選択
        $(SearchCity.dst_city).val($('#form_addr2').val());
    };

    // 郵便番号に該当する住所情報を取得
    $.fn.changeCityByZipCode = function () {
        return _getResultByZipCodeOnSP();
    };

    // 郵便番号に該当する住所情報を取得
    $.fn.changeCityByZipCodeOnSP = function () {
        return _getResultByZipCodeOnSP();
    };

    // 郵便番号に該当する住所情報を取得
    function _getResultByZipCodeOnSP() {
        var _zip = $(SearchCity.src_zip).val();
        // 検索
        $.ajax({
            url: "/woa/api/getCity",
            type: "POST",
            async: false,
            data: {
              'zip' : _zip
            },
            dataType: "json",
            success: function(eval_obj){
                //var eval_obj = eval(value);
                if (eval_obj == undefined || eval_obj == null || eval_obj.length == 0) {
                    if ($('#zip_errormsg') && $('#zip_errflag')) {
                        $('#zip_errormsg').text('存在しない郵便番号です').css({'display': 'block'});
                        $('#zip_errflag').val('1');
                    }
                    return false;
                }

                if ($('#zip_errormsg') && $('#zip_errflag')) {
                    $('#zip_errormsg').text('').css({'display': 'none'});
                    $('#zip_errflag').val('0');
                }

                var city = eval_obj[0];
                //都道府県をセットする
                $(SearchCity.dst_pref).val(city.city_pref);
                //市区町村をセットする（SearchCity.Callbackで処理が行われる）
                SearchCity.dst_city_num = city.city_order;
                //市区町村入力フォームがあった場合の処理
                var addr2_w_ojb = $("#addr2_w");
                if (addr2_w_ojb.length > 0) {
                    addr2_w_ojb.val(city.city_name);
                }
                // fill city list
                _setOptions(city.arr_cities,SearchCity.dst_city, city);
                //市区町村以下の情報(city_detail)があればそれを番地・建物名にセットする
                $(SearchCity.dst_address).val(city.city_detail);

                //郵便番号を変換した値に置き換える
                $(SearchCity.src_zip).val(city.zip_code);
                if ($("#addr2_w").length > 0) {
                    $(document).changeCityListByAddr1();
                }

                // semantic.ui を使用していたら、郵便番号検索で市区町村等が変更する様にする
                if ( $('.selectWrap.addr2').find($('.ui.dropdown')).length == 1){
                    $("select#addr1").parent().dropdown({'set selected': city.city_pref});
                    $("select#addr2").parent().dropdown('set text', city.city_name);
                }

                if (typeof $(this).callbackZip === 'function') {
                    $(this).callbackZip();
                }

                // 希望勤務都道府県
                setReq1Addr1();

                // イベント発火
                triggerEventGa();

                $('#zip').blur();
            }
        });

        return true;
    }

    // 郵便番号検索でのGAイベント発火
    function triggerEventGa () {
        $('#addr1').trigger('change');
        $('#addr2').trigger('change');
        $('#addr3').trigger('change');
        $('#req1_addr1').trigger('change');
    }

    //コールバック
    function Callback (eval_obj) {
        _setOptions(eval_obj, SearchCity.dst_city, "");
    }

    //optionクリア
    function _clearOptions (dst_id) {
        var obj = $(dst_id);
        obj.children().remove();
        if(obj.attr('id') == 'addr2') {
            if(SearchCity.isManagerOffSearch) {
                obj.options[obj.length] = new Option("", "", true, false);
            } else {
                obj.append($('<option>').attr({ value: "" }).text("市区町村"));
            }
        }
    }

    //optionセット
    function _setOptions (eval_obj, dst_city, city){
        //一度クリアする
        var addr2 = $('#addr2').val();
        if (typeof(addr2) != 'undefined') {
            _clearOptions(dst_city);

            var dst_obj = $(dst_city);
            $.each(eval_obj, function(idx, val) {
                if(dst_obj.attr("id") == 'addr2') {
                    if(val.id == addr2){
                        dst_obj.append($('<option selected>').attr({ value: val.id }).text(val.addr2));
                    }else{
                        dst_obj.append($('<option>').attr({ value: val.id }).text(val.addr2));
                    }
                }
            });
            //SearchCity.dst_city_numが０以外ならその番号に該当する市区町村を表示状態にする
            if (SearchCity.dst_city_num != 0) {
                $(SearchCity.dst_city).val(city.city_id);
                SearchCity.dst_city_num = 0;
            }
        };
    }

    // 市区町村選択
    function setSelectedIndex (select_city,_value) {
        for(index=0;index<$(select_city).options.length;index++){
            if($(select_city).options[index].val() ==_value){
                $(select_city).selectedIndex = index;
                break;
            }
        }
    }

});