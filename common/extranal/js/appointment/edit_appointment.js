"use strict";
$(document).ready(function () {
    $(".doctor_div").on("change", "#adoctors", function () {

        "use strict";
        var id = $('#appointment_id').val();
        var date = $('#date').val();
        var doctorr = $('#adoctors').val();
        $('#aslots').find('option').remove();

        $.ajax({
            url: 'schedule/getAvailableSlotByDoctorByDateByAppointmentIdByJason?date=' + date + '&doctor=' + doctorr + '&appointment_id=' + id,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                var slots = response.aslots;
                $.each(slots, function (key, value) {
                    "use strict";
                    $('#aslots').append($('<option>').text(value).val(value)).end();
                });

                if ($('#aslots').has('option').length == 0) {                    //if it is blank. 
                    $('#aslots').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
                }
            }
        })
    });

});

$(document).ready(function () {
    "use strict";
    var id = $('#appointment_id').val();
    var date = $('#date').val();
    var doctorr = $('#adoctors').val();
    $('#aslots').find('option').remove();

    $.ajax({
        url: 'schedule/getAvailableSlotByDoctorByDateByAppointmentIdByJason?date=' + date + '&doctor=' + doctorr + '&appointment_id=' + id,
        method: 'GET',
        data: '',
        dataType: 'json',
        success: function (response) {
            "use strict";
            var slots = response.aslots;
            $.each(slots, function (key, value) {
                "use strict";
                $('#aslots').append($('<option>').text(value).val(value)).end();
            });

            $("#aslots").val(response.current_value).trigger('change');


            if ($('#aslots').has('option').length == 0) {
                $('#aslots').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
            }
        }
    })

});




$(document).ready(function () {
    "use strict";
    $('#date').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    })
            //Listen for the change even on the input
            .change(dateChanged)
            .on('changeDate', dateChanged);
});

function dateChanged() {
    "use strict";
    var id = $('#appointment_id').val();
    var date = $('#date').val();
    var doctorr = $('#adoctors').val();
    $('#aslots').find('option').remove();

    $.ajax({
        url: 'schedule/getAvailableSlotByDoctorByDateByAppointmentIdByJason?date=' + date + '&doctor=' + doctorr + '&appointment_id=' + id,
        method: 'GET',
        data: '',
        dataType: 'json',
        success: function (response) {
            "use strict";
            var slots = response.aslots;
            $.each(slots, function (key, value) {
                "use strict";
                $('#aslots').append($('<option>').text(value).val(value)).end();
            });

            if ($('#aslots').has('option').length == 0) {                    //if it is blank. 
                $('#aslots').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
            }
        }
    })

}
