  "use strict";
$('.multi-select').multiSelect({

    selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder=' search...'>",
    selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder=''>",
    afterInit: function (ms) {
        "use strict";
        var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function (e) {
                    "use strict";
                    if (e.which === 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function (e) {
                    "use strict";
                    if (e.which === 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
    },
    afterSelect: function () {
        "use strict";
        this.qs1.cache();
        this.qs2.cache();
    },
    afterDeselect: function () {
        "use strict";
        this.qs1.cache();
        this.qs2.cache();
    }
});

$('#my_multi_select3').multiSelect();

$('.default-date-picker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    startDate: '01-01-1900',
    clearBtn: true,
    language: '<?php echo $langdate; ?>'
});


$('#date').on('changeDate', function () {
    "use strict";

    $('#date').datepicker('hide', {
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: '01-01-1900',
        language: '<?php echo $langdate; ?>'
    });
});

$('#date1').on('changeDate', function () {
    "use strict";
    $('#date1').datepicker('hide', {
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        startDate: '01-01-1900',
        language: '<?php echo $langdate; ?>'
    });
});

$(document).ready(function () {
    "use strict";
    $('#calendar').fullCalendar({
        lang: 'en',
        events: 'appointment/getAppointmentByJason',
        header:
                {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',
                },

        timeFormat: 'h(:mm) A',
        eventRender: function (event, element) {
            element.find('.fc-time').html(element.find('.fc-time').text());
            element.find('.fc-title').html(element.find('.fc-title').text());

        },
        eventClick: function (event) {
            $('#medical_history').html("");
            if (event.id) {
                $.ajax({
                    url: 'patient/getMedicalHistoryByJason?id=' + event.id + '&from_where=calendar',
                    method: 'GET',
                    data: '',
                    dataType: 'json',
                    success: function (response) {
                         "use strict";
                    $('#medical_history').html("");
                    $('#medical_history').append(response.view);
                    }
                    
                })


            }

            $('#cmodal').modal('show');
        },

        slotDuration: '00:5:00',
        businessHours: false,
        slotEventOverlap: false,
        editable: false,
        selectable: false,
        lazyFetching: true,
        minTime: "6:00:00",
        maxTime: "24:00:00",
        defaultView: 'month',
        allDayDefault: false,
        displayEventEnd: true,
        timezone: false

    });
});






$(document).ready(function () {
    "use strict";
    $('.timepicker-default').timepicker({defaultTime: 'value'}).on('change', function (event) {
                  calculateTime();
                  // calculateTime1(); comment by mohit beacuse second time click is not working 
                                

    });

});







$(document).ready(function () {
    "use strict";
    $(".js-example-basic-single").select2();

    $(".js-example-basic-multiple").select2();
});








$(document).ready(function () {
    "use strict";
    var windowH = $(window).height();
    var wrapperH = $('#container').height();
    if (windowH > wrapperH) {
        $('#sidebar').css('height', (windowH) + 'px');
    } else {
        $('#sidebar').css('height', (wrapperH) + 'px');
    }
    var windowSize = window.innerWidth;
    if (windowSize < 768) {
        $('#sidebar').removeAttr('style');
    }
});
function onElementHeightChange(elm, callback) {
    "use strict";
    var newHeight;
    var lastHeight = elm.clientHeight, newHeight;
    (function run() {
        "use strict";
        newHeight = elm.clientHeight;
        if (lastHeight !== newHeight)
            callback();
        lastHeight = newHeight;
        if (elm.onElementHeightChangeTimer)
            clearTimeout(elm.onElementHeightChangeTimer);
        elm.onElementHeightChangeTimer = setTimeout(run, 200);
    })();
}




onElementHeightChange(document.body, function () {
    "use strict";
    var windowH = $(window).height();
    var wrapperH = $('#container').height();
    if (windowH > wrapperH) {
        $('#sidebar').css('height', (windowH) + 'px');
    } else {
        $('#sidebar').css('height', (wrapperH) + 'px');
    }

    var windowSize = $(window).width();
    if (windowSize < 768) {
        $('#sidebar').removeAttr('style');
    }
});





$(window).resize(function () {
    "use strict";
    if (width === $(window).width()) {
        return;
    }
    var width = $(window).width();
    if (width < 600) {
        $('#sidebar').hide();
    } else {
        $('#sidebar').show();
    }

});