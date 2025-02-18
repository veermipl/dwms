"use strict";
$(document).ready(function () {
    "use strict";
    $(".table").on("click", ".editbutton", function () {
        "use strict";
        var iid = $(this).attr('data-id');
        console.log(iid);
        $('#editTimeSlotForm').trigger("reset");
        $('#myModal2').modal('show');
        $.ajax({
            url: 'finance/editTariffByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                console.log(response);
                // Populate the form fields with the data returned from server
                $('#editTimeSlotForm').find('[name="id"]').val(response.tariff.id).end();
                $('#editTimeSlotForm').find('[name="doctor_id"]').val(response.tariff.doctor_id).end();
                $('#editTimeSlotForm').find('[name="membership_details_id"]').val(response.tariff.membership_details_id).end();
                $('#editTimeSlotForm').find('[name="amount"]').val(response.tariff.amount).end();
                $('#editTimeSlotForm').find('[name="status"]').val(response.tariff.status).end();
                
            }
        })
    });
});

$(document).ready(function () {
    "use strict";
    var table = $('#editable-sample').DataTable({
        responsive: true,

        dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",

        buttons: [
            {extend: 'copyHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
            {extend: 'excelHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
            {extend: 'csvHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
            {extend: 'pdfHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
            {extend: 'print', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
        ],
        aLengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: -1,
        "order": [[0, "desc"]],

        "language": {
            "lengthMenu": "_MENU_",
            search: "_INPUT_",
            "url": "common/assets/DataTables/languages/" + language + ".json"
        },

    });

    table.buttons().container()
            .appendTo('.custom_buttons');
});

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
                "use strict";
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                "use strict";
                return {
                    results: response
                };
            },
            cache: true
        }

    });
   
     $("#adoctors").select2({
        placeholder: select_doctor,
        allowClear: true,
        ajax: {
            url: 'doctor/getDoctorInfo',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                "use strict";
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                "use strict";
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
                "use strict";
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                "use strict";
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

