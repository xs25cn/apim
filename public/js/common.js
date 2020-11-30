function initDateRangePicker(start, end,id){
    $('#'+id).daterangepicker({
        //minDate: '01/01/2012',                //最小时间
        //maxDate : moment(),                     //最大时间
        //dateLimit : { days : 30 },             //起止时间的最大间隔
        showDropdowns: true,
        timePicker : false,                     //是否显示小时和分钟
        "linkedCalendars": true,
        "showCustomRangeLabel": false,
        "alwaysShowCalendars": true,
        "opens": "right",                        //日期选择框的弹出位置
        startDate: start,
        endDate: end,
        ranges: {
            // '最近1小时': [moment().subtract('hours',1), moment()],
            '今天': [moment(), moment()],
            '昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '最近7天': [moment().subtract(6, 'days'), moment()],
            '最近30天': [moment().subtract(29, 'days'), moment()],
            '本月': [moment().startOf('month'), moment().endOf('month')],
            '上个月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'YYYY-MM-DD',
            applyLabel: '确定',
            cancelLabel: '取消',
            fromLabel: '起始时间',
            toLabel: '结束时间',
            customRangeLabel: '自定义',
            daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                '七月', '八月', '九月', '十月', '十一月', '十二月'],
            firstDay: 1
        },
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary blue',
        cancelClass: 'btn-small',
    });
}

//全站水印
function watermark(str) {
    var class_name = 'full-watermark';
    var w = $(document).outerWidth();
    var h = $(document).outerHeight();
    $("." + class_name).remove();
    var cw = 60;
    var ch = 55;
    var row = w / cw;
    var col = h / ch;
    for (var i = 0; i < row - 1; i++) {
        for (var j = 0; j < col - 1; j++) {
            if ((i + j) % 3 != 0) {
                continue;
            }
            var left = i * cw;
            var top = j * ch;
            var div = $('<div class="' + class_name + '" style="top:' + top + 'px;left:' + left + 'px;">' + str + '</div>');
            $("html").prepend(div);
        }
    }
}