var $window = $(window);
var $year = $('#js-year');
var $month = $('#js-month');
var $tbody = $('#js-calendar-body');

var today = new Date();
today.setTime(today.getTime() + 1000*60*60*9);// JSTに変換
var currentYear = today.getFullYear(),
currentMonth = today.getMonth();

$window.on('load',function(){
    calendarHeading(currentYear, currentMonth);
    calendarBody(currentYear, currentMonth, today);
});
$('.js-calendar-next').on('click',function(){
    if(currentMonth >= 11) {
        currentYear += 1;
        currentMonth = 0;
    } else {
        currentMonth += 1;
    }
    calendarHeading(currentYear, currentMonth);
    calendarBody(currentYear, currentMonth, today);
});
$('.js-calendar-pref').on('click',function(){
    if(currentMonth <= 0) {
        currentYear -= 1;
        currentMonth = 11;
    } else {
        currentMonth -= 1;
    }
    calendarHeading(currentYear, currentMonth);
    calendarBody(currentYear, currentMonth, today);
});
$tbody.on('click','td',function(){
    window.location.href = '/seminar/calendar/'+$(this).data('date');
});

function calendarBody(year, month, today){
    var todayYMFlag = today.getFullYear() === year && today.getMonth() === month ? true : false;
    var startDate = new Date(year, month, 1);
    var endDate  = new Date(year, month + 1 , 0);
    var startDay = startDate.getDay();
    var endDay = endDate.getDate();
    var textSkip = true;
    var textDate = 1;
    var tableBody ='';

    for (var row = 0; row < 6; row++){
        var tr = '<tr>';

        for (var col = 0; col < 7; col++) {
            if (row === 0 && startDay === col){
                textSkip = false;
            }
            if (textDate > endDay) {
                textSkip = true;
            }
            var textTd = textSkip ? '&nbsp;' : textDate++;
            var addData = textTd > 0 ? year+'-'+getdoubleDigestNumer(month+1)+'-'+getdoubleDigestNumer(textTd) : '';
            var addClass = todayYMFlag && textDate === today.getDate() ? 'c-calendar-today' : '';
            var td = '<td data-date="'+addData+'" class="'+addClass+'">'+textTd+'</td>';
            tr += td;
        }
        tr += '</tr>';
        tableBody += tr;
    }
    $tbody.html(tableBody);
}

function calendarHeading(year, month){
    $year.text(year);
    $month.text(month + 1);
}

function getdoubleDigestNumer(number) {
    return ("0" + number).slice(-2)
}
