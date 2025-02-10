//希望就業エリア１
jQuery(document).ready(function($){
	if($('input[name="job_change_type[{$value}]"] option[checked="checked"]').length){
    	$('#value-checkbox').html('<p>OK</p>');
    }
    if($('select[name="req_date"] option[selected="selected"]').length){
    	$('#value-pulldown1').html('<p>OK</p>');
    }
    if($('select[name="pref1"] option[selected="selected"]').length){
    	$('#value-pulldown2').html('<p>OK</p>');
    }
    if($('select[name="experience"] option[selected="selected"]').length){
    	$('#value-pulldown3').html('<p>OK</p>');
    }
    if($('input[name="req_work_type[{$value}]"] option[checked="checked"]').length){
    	$('#value-radio').html('<p>OK</p>');
    }
});