"use strict";

$(document).ready(function () {
    "use strict";
    $(".medical_history_button").on("click", ".editbutton", function () {

        "use strict";
        var iid = $(this).attr('data-id');
        $('#myModal2').modal('show');
        $.ajax({
            url: 'patient/editMedicalHistoryByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                var date = new Date(response.medical_history.date * 1000);
                var de = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();

                $('#medical_historyEditForm').find('[name="id"]').val(response.medical_history.id).end();
                $('#medical_historyEditForm').find('[name="date"]').val(de).end();
                $('#medical_historyEditForm').find('[name="title"]').val(response.medical_history.title).end();
                myEditor.setData(response.medical_history.description);
            }

        })
    });
});

var myEditor=" ";

$(document).ready(function () {

    ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editor.ui.view.editable.element.style.height = '200px';
                myEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

});

$(document).ready(function () {
    "use strict";
    $(".edit_appointment_button").on("click", ".editAppointmentButton", function () {
        "use strict";
        var iid = $(this).attr('data-id');
        var id = $(this).attr('data-id');

        $('#editAppointmentForm').trigger("reset");
        $('#editAppointmentModal').modal('show');
        $.ajax({
            url: 'appointment/editAppointmentByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                var de = response.appointment.date * 1000;
                var d = new Date(de);
                var da = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();

                $('#editAppointmentForm').find('[name="id"]').val(response.appointment.id).end();
                $('#editAppointmentForm').find('[name="patient"]').val(response.appointment.patient).end();

                $('#editAppointmentForm').find('[name="date"]').val(da).end();
                $('#editAppointmentForm').find('[name="status"]').val(response.appointment.status).end();
                $('#editAppointmentForm').find('[name="remarks"]').val(response.appointment.remarks).end();
                var option1 = new Option(response.doctor.name + '-' + response.doctor.id, response.doctor.id, true, true);
                $('#editAppointmentForm').find('[name="doctor"]').append(option1).trigger('change');

                $('.js-example-basic-single.patient').val(response.appointment.patient).trigger('change');




                var date = $('#date1').val();
                var doctorr = $('#adoctors1').val();
                var appointment_id = $('#appointment_id').val();

                $.ajax({
                    url: 'schedule/getAvailableSlotByDoctorByDateByAppointmentIdByJason?date=' + date + '&doctor=' + doctorr + '&appointment_id=' + appointment_id,
                    method: 'GET',
                    data: '',
                    dataType: 'json',
                    success: function (response) {
                        "use strict";
                        $('#aslots1').find('option').remove();
                        var slots = response.aslots;
                        $.each(slots, function (key, value) {
                            $('#aslots1').append($('<option>').text(value).val(value)).end();
                        });

                        $("#aslots1").val(response.current_value)
                                .find("option[value=" + response.current_value + "]").attr('selected', true);

                        if ($('#aslots1').has('option').length == 0) {
                            $('#aslots1').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
                        }
                    }
                })
            }
        })
    });
});

$(document).ready(function () {
    "use strict";
    $(".doctor_div").on("change", "#adoctors", function () {
        "use strict";
        var iid = $('#date').val();
        var doctorr = $('#adoctors').val();
        $('#aslots').find('option').remove();

        $.ajax({
            url: 'schedule/getAvailableSlotByDoctorByDateByJason?date=' + iid + '&doctor=' + doctorr,
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
    var iid = $('#date').val();
    var doctorr = $('#adoctors').val();
    $('#aslots').find('option').remove();

    $.ajax({
        url: 'schedule/getAvailableSlotByDoctorByDateByJason?date=' + iid + '&doctor=' + doctorr,
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

            .change(dateChanged)
            .on('changeDate', dateChanged);
});

function dateChanged() {
    "use strict";
    var iid = $('#date').val();
    var doctorr = $('#adoctors').val();
    $('#aslots').find('option').remove();

    $.ajax({
        url: 'schedule/getAvailableSlotByDoctorByDateByJason?date=' + iid + '&doctor=' + doctorr,
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

            if ($('#aslots').has('option').length == 0) {
                $('#aslots').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
            }
        }
    })

}

$(document).ready(function () {
    "use strict";
    $(".doctor_div1").on("change", "#adoctors1", function () {
        "use strict";
        var id = $('#appointment_id').val();
        var date = $('#date1').val();
        var doctorr = $('#adoctors1').val();
        $('#aslots1').find('option').remove();

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
                    $('#aslots1').append($('<option>').text(value).val(value)).end();
                });

                if ($('#aslots1').has('option').length == 0) {
                    $('#aslots1').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
                }
            }
        })
    });
});

$(document).ready(function () {
    "use strict";
    var id = $('#appointment_id').val();
    var date = $('#date1').val();
    var doctorr = $('#adoctors1').val();
    $('#aslots1').find('option').remove();

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
                $('#aslots1').append($('<option>').text(value).val(value)).end();
            });

            if ($('#aslots1').has('option').length == 0) {                    //if it is blank. 
                $('#aslots1').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
            }
        }
    })
});




$(document).ready(function () {
    "use strict";

    $('#date1').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
    })

            .change(dateChanged1)
            .on('changeDate', dateChanged1);
});

function dateChanged1() {
    "use strict";
    var id = $('#appointment_id').val();
    var iid = $('#date1').val();
    var doctorr = $('#adoctors1').val();
    $('#aslots1').find('option').remove();

    $.ajax({
        url: 'schedule/getAvailableSlotByDoctorByDateByAppointmentIdByJason?date=' + iid + '&doctor=' + doctorr + '&appointment_id=' + id,
        method: 'GET',
        data: '',
        dataType: 'json',
        success: function (response) {
            "use strict";
            var slots = response.aslots;
            $.each(slots, function (key, value) {
                "use strict";
                $('#aslots1').append($('<option>').text(value).val(value)).end();
            });

            if ($('#aslots1').has('option').length == 0) {
                $('#aslots1').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();
            }
        }
    })

}

$(document).ready(function () {
    "use strict";
    $('#editable-sample').DataTable({
        responsive: true,
        dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",

        buttons: [
            {extend: 'copyHtml5', exportOptions: {columns: [0, 1], }},
            {extend: 'excelHtml5', exportOptions: {columns: [0, 1], }},
            {extend: 'csvHtml5', exportOptions: {columns: [0, 1], }},
            {extend: 'pdfHtml5', exportOptions: {columns: [0, 1], }},
            {extend: 'print', exportOptions: {columns: [0, 1], }},
        ],
        aLengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: -1,
        "order": [[0, "desc"]],
        "language": {
            "lengthMenu": "_MENU_ records per page",
        }


    });
});


$(document).ready(function () {
    "use strict";
    $(".edit_patient_div").on("click", ".editPatient", function () {
        "use strict";
        var iid = $(this).attr('data-id');
        $('#editPatientForm').trigger("reset");
        $.ajax({
            url: 'patient/editPatientByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                $('#editPatientForm').find('[name="id"]').val(response.patient.id).end();
                $('#editPatientForm').find('[name="name"]').val(response.patient.name).end();
                $('#editPatientForm').find('[name="password"]').val(response.patient.password).end();
                $('#editPatientForm').find('[name="email"]').val(response.patient.email).end();
                $('#editPatientForm').find('[name="address"]').val(response.patient.address).end();
                $('#editPatientForm').find('[name="phone"]').val(response.patient.phone).end();
                $('#editPatientForm').find('[name="sex"]').val(response.patient.sex).end();
                $('#editPatientForm').find('[name="birthdate"]').val(response.patient.birthdate).end();
                $('#editPatientForm').find('[name="bloodgroup"]').val(response.patient.bloodgroup).end();
                $('#editPatientForm').find('[name="p_id"]').val(response.patient.patient_id).end();

                if (typeof response.patient.img_url !== 'undefined' && response.patient.img_url !== '') {
                    $("#img").attr("src", response.patient.img_url);
                }


                $('.js-example-basic-single.doctor').val(response.patient.doctor).trigger('change');
                $('#infoModal').modal('show');
            }

        })
    });
});

$(document).ready(function () {
    "use strict";

    $("#adoctors").select2({
        placeholder: select_doctor,
        allowClear: true,
        ajax: {
            url: 'doctor/getDoctorInfo',
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
    $("#adoctors1").select2({
        placeholder: select_doctor,
        allowClear: true,
        ajax: {
            url: 'doctor/getDoctorInfo',
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
    $(".folder_div").on("click", ".edittbutton", function () {
        "use strict";
        var iid = $(this).attr('data-id');
        $('#editFolderForm').trigger("reset");
        $('#myModalfe').modal('show');
        $.ajax({
            url: 'patient/editFolderByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                $('#editFolderForm').find('[name="id"]').val(response.folder.id).end();
                $('#editFolderForm').find('[name="folder_name"]').val(response.folder.folder_name).end();
                $('#editFolderForm').find('[name="patient"]').val(response.folder.patient).end();
            }
        })
    });
});
$(document).on('click', '.upload', function () {
    "use strict";
    var folder_name = $(this).data("name");
    $('#hidden_folder_name').val(folder_name);
    $('#myModalff').modal('show');
});
$(document).ready(function () {
    "use strict";
    $(".folder_div").on("click", ".uploadbutton", function () {
        "use strict";
        // Get the record's ID via attribute  
        var iid = $(this).attr('data-id');
        $('#uploadFileForm').trigger("reset");
        $('#myModalff').modal('show');
        $.ajax({
            url: 'patient/editFolderByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                $('#uploadFileForm').find('[name="folder"]').val(response.folder.id).end()
            }
        })
    });
});
$(document).ready(function () {
    "use strict";
    $(".flashmessage").delay(3000).fadeOut(100);
});







