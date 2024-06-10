"use strict";
$(document).ready(function () {
    $(".doctor_div").on("change", "#adoctors", function () {

        "use strict";
        var id = $('#appointment_id').val();
        var date = $('#date').val();
        var doctorr = $('#adoctors').val();
        $('#aslots').find('option').remove();

        getAvailableSlot(date, doctorr);
    });

});

$(document).ready(function () {
    "use strict";
    var id = $('#appointment_id').val();
    var date = $('#date').val();
    var doctorr = $('#adoctors').val();
    $('#aslots').find('option').remove();

    getAvailableSlot(date, doctorr);
});




$(document).ready(function () {
    "use strict";
    $('#date').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        startDate: new Date() // Disables selection of dates before today
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

    getAvailableSlot(date, doctorr);
}

function getAvailableSlot(date = null, doctorr = null) {

    if (date && doctorr) {
        $.ajax({
            url: 'schedule/getAvailableSlotByDoctorByDateByJason?date=' + date + '&doctor=' + doctorr,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                $('#aslots').children().remove();
                
                var slots = response.aslots;

                $.each(slots, function (key, value) {
                    $('#aslots').append($('<option>').text(value).val(value)).end();
                });

                if ($('#aslots').has('option').length == 0) {                    //if it is blank. 
                    // $('#aslots').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
                    $('#aslots').append($('<option>').text('No Further Time Slots').val('')).end();
                }
            }
        })
    } else {
        return false;
    }
}

