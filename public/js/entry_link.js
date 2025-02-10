// 登録画面にパラメーター付きで遷移させるJS
$( document ).ready(function( $ ) {
  $('.entry_start').on('click',function () {
    const license = $('#leadFormItem1').val();
    const req_emp_type = $('#leadFormItem2').val();
    const req_date = $('#leadFormItem3').val();
    const device = $('.entry_start').attr('device');
    let url = '';
    if (device == 'PC' || device == 'SP') {
      url = '/woa/glp/' + device + '_org_004/';
    } else {
      url = '/woa/glp/SP_org_004/';
    }
    let param = '';
    if (license != '') {
      param = 'job_type=' + license + '&';
    }
    if (req_emp_type != '') {
      param += 'req_emp_type=' + req_emp_type + '&';
    }
    if (req_date != '') {
      param += 'req_date=' + req_date + '&';
    }
    if (license != '' || req_emp_type != '' || req_date != '') {
      url = url + '?' + param + 'branch_skip=1';
    }
    location.href = url;
  });
});
