"use strict";
$(document).ready(function () {
    "use strict";
    $(".table").on("click", ".editbutton", function () {
        "use strict";
        var iid = $(this).attr('data-id');
        $("#img").attr("src", "uploads/cardiology-patient-icon-vector-6244713.jpg");
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
                $('#editPatientForm').find('[name="p_id"]').val(response.patient.patient_id).end();

                if (typeof response.patient.img_url !== 'undefined' && response.patient.img_url != '') {
                    $("#img").attr("src", response.patient.img_url);
                }

                if (response.doctor !== null) {
                    var option1 = new Option(response.doctor.name + '-' + response.doctor.id, response.doctor.id, true, true);
                } else {
                    var option1 = new Option(' ' + '-' + '', '', true, true);
                }
                $('#editPatientForm').find('[name="doctor"]').append(option1).trigger('change');


                $('.js-example-basic-single.doctor').val(response.patient.doctor).trigger('change');

                $('#myModal2').modal('show');
            }
        })
    });



    $(".table").on("click", ".inffo", function () {
        "use strict";
        var iid = $(this).attr('data-id');

        $('#infoPatientForm').trigger("reset");
        $.ajax({
            url: 'patient/getPatientByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";

                // $('#infoPatientForm').find('[name="id"]').val(response.patient.id).end();
                // $('#infoPatientForm').find('[name="name"]').val(response.patient.name).end();
                // $('#infoPatientForm').find('[name="password"]').val(response.patient.password).end();
                // $('#infoPatientForm').find('[name="email"]').val(response.patient.email).end();
                // $('#infoPatientForm').find('[name="address"]').val(response.patient.address).end();
                // $('#infoPatientForm').find('[name="phone"]').val(response.patient.phone).end();
                // $('#infoPatientForm').find('[name="sex"]').val(response.patient.sex).end();
                // $('#infoPatientForm').find('[name="birthdate"]').val(response.patient.birthdate).end();
                // $('#infoPatientForm').find('[name="p_id"]').val(response.patient.patient_id).end();

                // if (response.doctor !== null) {
                //     $('#infoPatientForm').find('[name="doctor"]').val(response.doctor.name).end();
                // } else {
                //     $('#infoPatientForm').find('[name="doctor"]').val('').end();
                // }


                $("#img1").attr("src", "uploads/cardiology-patient-icon-vector-6244713.jpg");
                if (typeof response.patient.img_url !== 'undefined' && response.patient.img_url != '') {
                    $("#img1").attr("src", response.patient.img_url);
                }

                $('#infoPatientForm').find('#info_name').text(response.patient.name).end();
                $('#infoPatientForm').find('#info_email').text(response.patient.email).end();
                $('#infoPatientForm').find('#info_phone').text(response.patient.phone).end();
                $('#infoPatientForm').find('#info_gender').text(response.patient.sex).end();

                $('#infoPatientForm').find('#info_doctor').text(response.doctor.name).end();
                $('#infoPatientForm').find('#info_dob').text(response.patient.birthdate).end();
                $('#infoPatientForm').find('#info_age').text(response.patient.age).end();
                $('#infoPatientForm').find('#info_bloodgroup').text(response.patient.bloodgroup).end();
                $('#infoPatientForm').find('#info_address').text(response.patient.address).end();

                $('#infoPatientForm').find('#info_chiefComplaint').text(response.patient.chiefComplaint).end();
                $('#infoPatientForm').find('#info_historyOfIllness').text(response.patient.historyOfIllness).end();
                $('#infoPatientForm').find('#info_pastMedicalHistory').text(response.patient.pastMedicalHistory).end();
                $('#infoPatientForm').find('#info_pastSurgicalHistory').text(response.patient.pastSurgicalHistory).end();
                $('#infoPatientForm').find('#info_allergies').text('( ' + response.patient.allergies + ' )').end();
                $('#infoPatientForm').find('#info_allergies_comment').text(response.patient.allergies_comment).end();

                $('#infoPatientForm').find('#info_smoking').text('( ' + response.patient.smoking + ' )').end();
                $('#infoPatientForm').find('#info_smoking_comment').text(response.patient.smoking_comment).end();
                $('#infoPatientForm').find('#info_alcohol').text('( ' + response.patient.alcohol + ' )').end();
                $('#infoPatientForm').find('#info_alcohol_comment').text(response.patient.alcohol_comment).end();
                $('#infoPatientForm').find('#info_other_activity').text('( ' + response.patient.other_activity + ' )').end();
                $('#infoPatientForm').find('#info_other_activity_comment').text(response.patient.other_activity_comment).end();

                $('#infoModal').modal('show');
            }
        })
    });

    var emailOnLoad = $.trim($('#patientEmail').val());
    validateMail(emailOnLoad);

    $(document).on("keyup", "#patientEmail", function (e) {
        e.preventDefault();

        var email = $.trim($(this).val());
        validateMail(email);
        
    });

    function validateMail(email) {
        if (email) {
            $.ajax({
                url: 'patient/validatePatientMail',
                method: 'post',
                data: {
                    'email': email,
                },
                dataType: 'json',
                beforeSend: function(){
                    $('#emailErr').text('').fadeOut();
                },
                success: function (response) {

                    if (response.error === false) {
                        $('button[name="submit"]').prop('disabled', false);
                        $('#emailErr').text('').fadeOut();
                    } else {
                        $('button[name="submit"]').prop('disabled', true);
                        $('#emailErr').text(response.msg).fadeIn();
                    }
                },
                error: function (eRes) {
                }
            });
        } else {
            return false;
        }
    }

});

$(document).ready(function () {
    "use strict";
    var table = $('#editable-sample').DataTable({
        responsive: true,
        //   dom: 'lfrBtip',

        "processing": true,
        "serverSide": true,
        "searchable": true,
        "ajax": {
            url: "patient/getPatient",
            type: 'POST',
        },
        scroller: {
            loadingIndicator: true
        },
        dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",

        buttons: [
            { extend: 'copyHtml5', exportOptions: { columns: [0, 1, 2], } },
            { extend: 'excelHtml5', exportOptions: { columns: [0, 1, 2], } },
            { extend: 'csvHtml5', exportOptions: { columns: [0, 1, 2], } },
            { extend: 'pdfHtml5', exportOptions: { columns: [0, 1, 2], } },
            { extend: 'print', exportOptions: { columns: [0, 1, 2], } },
        ],
        aLengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: 10,
        "order": [[0, "desc"]],

        "language": {
            "lengthMenu": "_MENU_",
            search: "_INPUT_",
            "url": "common/assets/DataTables/languages/" + language + ".json"
        }
    });
    table.buttons().container().appendTo('.custom_buttons');
});


$(document).ready(function () {
    "use strict";
    $("#doctorchoose").select2({
        placeholder: select_doctor,
        allowClear: true,
        ajax: {
            url: 'doctor/getDoctorinfo',
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
    $("#doctorchoose1").select2({
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
    $(".flashmessage").delay(3000).fadeOut(100);
});




