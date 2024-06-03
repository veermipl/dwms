"use strict";
$(document).ready(function () {
    "use strict";
    $("#patientchoose").select2({
        placeholder: select_patient,
        allowClear: true,
        ajax: {
            url: 'patient/getPatientinfo',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }

    });



});

$(document).ready(function () {
    "use strict";
    $(".startDate").on("change", "#datetimepicker", function () {
        "use strict";
        var date = $(this).val();
        var category = $('#bedcategory').val();
        $.ajax({
            url: 'bed/getNotAvailableBed?date=' + date + '&category=' + category,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                var data = ' ';
                if (response.bedlist.length !== 0) {
                    $.each(response.bedlist, function (index, value) {

                        data = value.d_time;
                    });
                    alert(already_booked + '\n' + avaiable_bed_after + data);
                    $('#enddatetimepicker').val(" ");
                    $('#datetimepicker').val(" ");
                }
            }
        })

    });
    $(".endDate").on("change", "#enddatetimepicker", function () {
        "use strict";

        var date = $(this).val();
        var category = $('#bedcategory').val();
        $.ajax({
            url: 'bed/getNotAvailableBed?date=' + date + '&category=' + category,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                var startdata = ' ';
                var enddata = ' ';
                if (response.bedlist.length !== 0) {
                    $.each(response.bedlist, function (index, value) {

                        enddata = value.d_time;
                        startdata = value.a_time
                    });
                    alert(already_booked + '\n' + startdata + ' To ' + enddata + '\n' + please_choose_bed_after_that + '\n');
                    $('#enddatetimepicker').val(" ");
                    $('#datetimepicker').val(" ");
                }
            }
        })
    });


});




