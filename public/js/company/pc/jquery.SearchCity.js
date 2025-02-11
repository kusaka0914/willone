jQuery(document).ready(function($) {
    var SearchCity = function() {};

    SearchCity.dst_pref = "";
    SearchCity.dst_city = "";
    SearchCity.dst_city_num = 0;
    SearchCity.dst_address = "";
    SearchCity.src_zip = "";

    /**
     * 初期設定
     * 都道府県市区町村番地建物名のID保持します。
     * 必ず最初に実行してください。
     */
    $.fn.searchCityInit = function(src_zip, dst_pref, dst_city, dst_address) {
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
    $("select").change(function() {
        if ("#" + $(this).attr("id") == SearchCity.dst_pref) {
            $(document).changeCityListSync();
        }
    });

    /**
     * 都道府県に該当する市区町村の一覧を取得
     */
    $.fn.changeCityListSync = function() {
        var _pref = $(SearchCity.dst_pref).val();
        // 検索
        $.ajax({
            url: "/woa/api/getCity",
            type: "POST",
            async: true,
            data: {
                addr1: _pref
            },
            dataType: "json",
            success: function(value) {
                if (value) {
                    if (_pref == "") {
                        $(SearchCity.dst_city).disabled = true;
                    } else {
                        $(SearchCity.dst_city).disabled = false;
                    }

                    Callback(value);
                    // <select>にラベルを設定している場合も設定
                    setSelectLabel();
                }

            }
        });

    };

    /**
     * 都道府県に該当する市区町村の一覧を取得
     */
    $.fn.changeCityListSyncAddr2 = function() {
        var async = true;
        var _pref = $(SearchCity.dst_pref).val();
        if (!_pref) {
            // 東京で仮値を設定
            _pref = 26;
        }

        // 検索
        $.ajax({
            url: "/woa/api/getCity",
            type: "POST",
            async: async,
            data: {
                addr1: _pref
            },
            dataType: "json",
            success: function(value) {
                if (value) {
                    if (_pref == "") {
                        $(SearchCity.dst_city).disabled = true;
                    } else {
                        $(SearchCity.dst_city).disabled = false;
                    }

                    Callback(value);

                    // 市区町村を選択
                    $(SearchCity.dst_city).val($('#form_addr2').val());

                    // <select>にラベルを設定している場合も設定
                    setSelectLabel();
                }

            }
        });

    };

    // 郵便番号に該当する住所情報を取得
    $.fn.changeCityByZipCode = function() {
        return _getResultByZipCodeOnSP();
    };

    // 郵便番号に該当する住所情報を取得
    $.fn.changeCityByZipCodeOnSP = function() {
        return _getResultByZipCodeOnSP();
    };

    //コールバック
    function Callback(eval_obj) {
        //選択された都道府県に該当した市区町村をセット
        var addr2 = $(SearchCity.dst_city).val();
        var addr2_textbox = $(SearchCity.dst_city + '_textbox').val();
        if (typeof(addr2_textbox) != 'undefined' && addr2_textbox != null) {
            var zip = $('#zip');
            if (zip.value != "") {
                addr2_textbox.value = eval_obj[SearchCity.dst_city_num - 1].addr2;
            }
        } else {
            _setOptions(eval_obj, SearchCity.dst_city, "");
        }
    }


    //都道府県リストが選択された時に市区町村リストを更新する
    function _changeCityListSync(src_pref, dst_city) {
        SearchCity.src_pref = src_pref;
        SearchCity.dst_city = dst_city;
        var _pref = $(SearchCity.src_pref).value;
        var myAjax = new Ajax.Request("/woa/api/getCity", { method: "POST", asynchronous: false, parameters: "addr1=" + _pref });

        var addr2_textbox = document.getElementById('addr2_textbox');
        if (_pref == "") {
            if (typeof(addr2_textbox) != 'undefined' && addr2_textbox != null) {
                addr2_textbox.value = "";
            } else {
                $(SearchCity.dst_city).disabled = true;
            }
        } else {
            if (typeof(addr2_textbox) != 'undefined' && addr2_textbox != null) {

            } else {
                $(SearchCity.dst_city).disabled = false;
            }
        }
        SearchCity.Callback(myAjax.transport);
    }

    function _getResultByZipCodeOnSP() {
        var _zip = $(SearchCity.src_zip).val();
        if (!checkZipCode(_zip)) {
            return false;
        }
        // 検索
        $.ajax({
            url: "/woa/api/getCityAll",
            type: "POST",
            async: true,
            data: {
                zip: _zip
            },
            dataType: "json",
            success: function(eval_obj) {
                if (eval_obj) {
                    //var eval_obj = eval(value);
                    if (eval_obj == undefined || eval_obj == null) {
                        return false;
                    }
                    var city = eval_obj[0];
                    //都道府県をセットする
                    $(SearchCity.dst_pref).val(city.city_pref);
                    //市区町村をセットする（SearchCity.Callbackで処理が行われる）
                    SearchCity.dst_city_num = city.city_order;
                    //市区町村入力フォームがあった場合の処理
                    var addr2_w_ojb = $("#addr2_w");
                    if (addr2_w_ojb.size() > 0) {
                        addr2_w_ojb.val(city.city_name);
                    }
                    // fill city list
                    _setOptions(eval_obj.city_list, SearchCity.dst_city, city);
                    //市区町村以下の情報(city_detail)があればそれを番地・建物名にセットする
                    $(SearchCity.dst_address).val(city.city_detail);
                    //郵便番号を変換した値に置き換える
                    $(SearchCity.src_zip).val(city.zip_code);
                    if ($("#addr2_w").size() > 0) {
                        $(document).changeCityListByAddr1();
                    }

                    if (city.city_detail != '') {
                        var parent_label = $(SearchCity.dst_address).previousSibling;
                        if (parent_label) {
                            parent_label.style.display = "none";
                        }
                    }

                    // semantic.ui を使用していたら、郵便番号検索で市区町村等が変更する様にする
                    if ($('.selectWrap.addr2').find($('.ui.dropdown')).length == 1) {
                        $("select#addr1").parent().dropdown({ 'set selected': city.city_pref });
                        $("select#addr2").parent().dropdown('set text', city.city_name);
                    }

                    // <select>にラベルを設定している場合も設定
                    setSelectLabel();

                    if (typeof callbackZip === 'function') {
                        callbackZip();
                    }
                }
            },
            error: function(){
                // Callback
                if (typeof callbackZipError === 'function') {
                    callbackZipError();
                }
            }
        });

        return true;
    }

    //optionクリア
    function _clearOptions (dst_id) {
        var obj = $(dst_id);
        obj.children().remove();
        if (("#" + obj.attr('id')) == SearchCity.dst_city) {
            obj.append($('<option>').attr({ value: "" }).text("市区町村"));
        }
    }

    //optionセット
    function _setOptions (eval_obj, dst_city, city) {
        //一度クリアする
        var addr2 = $(SearchCity.dst_city).val();
        if (typeof(addr2) != 'undefined' && addr2 != null) {
            _clearOptions(dst_city);

            var dst_obj = $(dst_city);
            $.each(eval_obj, function(idx, val) {
                if (("#" + dst_obj.attr("id")) == SearchCity.dst_city) {
                    if (val.id == addr2) {
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

    function setSelectedIndex(select_city, _value) {
        for (index = 0; index < $(select_city).options.length; index++) {
            if ($(select_city).options[index].val() == _value) {
                $(select_city).selectedIndex = index;
                break;
            }
        }
    }

    //コールバック
    function Callback_getResultByZipCode(request) {
        var eval_obj = eval(request.responseText);
        if (eval_obj == undefined || eval_obj == null) {
            return;
        }
        var city = eval_obj[0];
        //都道府県をセットする
        $(SearchCity.dst_pref).val(city.city_pref);
        //市区町村をセットする（SearchCity.Callbackで処理が行われる）
        SearchCity.dst_city_num = city.city_order;

        // 検索
        $.ajax({
            url: "/woa/api/getCity",
            type: "POST",
            async: true,
            data: {
                addr1: city.city_pref
            },
            success: function(eval_obj) {
                if (eval_obj) {
                    Callback(eval_obj);
                    //市区町村以下の情報(city_detail)があればそれを番地・建物名にセットする
                    var obj_dst_address = $(SearchCity.dst_address);
                    obj_dst_address.val(city.city_detail);
                    // 番地建物名がセットされた場合は背景色を変更する
                    if (city.city_detail) {
                        var className = $(SearchCity.dst_address).attr("class");
                        var classNameNew = className.replace('_err', '');
                        document.getElementById(SearchCity.dst_address).className = classNameNew;
                        document.getElementById(SearchCity.dst_address).style.color = "";
                    }
                    //郵便番号を変換した値に置き換える
                    $(SearchCity.src_zip).val(city.zip_code);
                }

            }
        });

    }

    // ------------------------------------------------------------------------
    // 郵便番号住所検索の初期化
    // ------------------------------------------------------------------------
    // 郵便番号検索の初期化
    if (typeof($('#zip').val()) != 'undefined' && typeof($('#addr1').val()) != 'undefined' && typeof($('#addr2').val()) != 'undefined' && typeof($('#addr3').val()) != 'undefined') {
        $(document).searchCityInit("zip", "addr1", "addr2", "addr3");
    }

    function checkZipCode(_zipCode) {
        if (_zipCode == null || _zipCode == '') {
            return false;
        } else if (!_zipCode.match(/^[0-9]{7}$/)) {
            return false;
        }
        return true;
    }

    /**
     * 都道府県・市区町村のプルダウンラベルを設定
     */
    function setSelectLabel() {
        if ($('.addr1 .select01')) {
            $('.addr1 .select01').text($('#addr1 option:selected').text());
            if ($('.selectWrap.addr1') && $('#addr1 option:selected').text() != "都道府県") {
                $('.selectWrap.addr1').addClass('on');
            }
        }
        if ($('.addr2 .select01')) {
            $('.addr2 .select01').text($('#addr2 option:selected').text());
            if ($('.selectWrap.addr2') && $('#addr2 option:selected').text() != "市区町村") {
                $('.selectWrap.addr2').addClass('on');
            }
        }
    }

    var pre_zip = $("#zip").val(); // #871_3149

    setInterval(function(){
        var zip = $("#zip").val();
        if (zip == null) zip = ""; // #871_3149
        if (zip != pre_zip) {
            if ((zip.indexOf("-") < 0 && zip.length == 7) || (zip.indexOf("-") >= 0 && zip.length == 8)) {
                $(this).changeCityByZipCode('zip','prefecture_id','city_id','after_address');
            }
        }
        pre_zip = zip;
    }, 100);
});