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
        $('#zip').autocomplete("close");    // オートコンプリートを閉じる
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
                if (eval_obj) {
                    //var eval_obj = eval(value);
                    if(eval_obj == undefined || eval_obj == null || eval_obj.length == 0){
                        return false;
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
                    _setOptions(city.arr_cities, SearchCity.dst_city, city);
                    //市区町村以下の情報(city_detail)があればそれを番地・建物名にセットする
                    $(SearchCity.dst_address).val(city.city_detail);

                    //郵便番号を変換した値に置き換える
                    $(SearchCity.src_zip).val(city.zip_code);
                    if ($("#addr2_w").length > 0) {
                        $(document).changeCityListByAddr1();
                    }

                    // 希望勤務都道府県
                    setReq1Addr1();

                    // イベント発火
                    triggerEventGa();
                }
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
                obj.append($('<option>').attr({ value: "" }).text("選択して下さい"));
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
        for (index = 0; index < $(select_city).options.length; index++) {
            if ($(select_city).options[index].val() ==_value) {
                $(select_city).selectedIndex = index;
                break;
            }
        }
    }

    // 検索データ受信時の処理
    function zipDataReceive(response, data) {
        response($.map(data, function (item,key) {
            if(key != 'city_list') {
              var address = item.city_pref_name + item.city_name + item.city_detail;
              var city_pref = item.city_pref;
              var label = item.zip_code + ' : ' + address;
            }
            return {
                label: label,
                zip_code: item.zip_code,
                address: item.city_detail,
                city_pref: city_pref,
                city_id: item.city_id,
                city_list: data.city_list,
            }
        }));
    }

    // フォームの項目を更新
    function zipDataUpdate(ui) {
        if (typeof $.onZipAuto === 'function') {
            // GAイベント
            $.onZipAuto();
        }

        $('#zip').val(ui.item.zip_code).change();
        $('#addr3').val(ui.item.address);
        $('#addr1').val(ui.item.city_pref);
        $('#addr2').empty();
        var option = '<option value="">選択してください</option>';
        $.each(ui.item.city_list, function(i, list){
            option += '<option value='+ i +'>'+ list +'</option>';
        });

        $('#addr2').append(option);
        $('#addr2').val(ui.item.city_id);

        // イベント発火
        triggerEventGa();

        // 希望勤務都道府県
        setReq1Addr1();
    }

    // 郵便番号の入力フィールドに Autocomplete を適用
    $('#zip').autocomplete({
        delay: 1,
        minLength: 3,
        position:{
            at:"right top"
        },
        source: function (request, response) {
            $.ajax({
                type: "POST",
                url: '/woa/api/getCityAll',
                dataType: 'json',
                data: {
                    'zip' : $('#zip').val()
                },
            }).done(function (data) {
                zipDataReceive(response, data);
            }).fail(function (data) {});
        },
        select: function (event, ui) {
            zipDataUpdate(ui);
            return false;
        }
    });
});
