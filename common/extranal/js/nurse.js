"use strict";
$(document).ready(function () {
    "use strict";
    $(".table").on("click", ".editbutton", function () {
        "use strict";
        var iid = $(this).attr('data-id');
        $('#editNurseForm').trigger("reset");
        $.ajax({
            url: 'nurse/editNurseByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                $('#editNurseForm').find('[name="id"]').val(response.nurse.id).end();
                $('#editNurseForm').find('[name="name"]').val(response.nurse.name).end();
                $('#editNurseForm').find('[name="password"]').val(response.nurse.password).end();
                $('#editNurseForm').find('[name="email"]').val(response.nurse.email).end();
                $('#editNurseForm').find('[name="address"]').val(response.nurse.address).end();
                $('#editNurseForm').find('[name="phone"]').val(response.nurse.phone).end();
                $('#myModal2').modal('show');
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
            {extend: 'copyHtml5', exportOptions: {columns: [1, 2, 3, 4], }},
            {extend: 'excelHtml5', exportOptions: {columns: [1, 2, 3, 4], }},
            {extend: 'csvHtml5', exportOptions: {columns: [1, 2, 3, 4], }},
            {extend: 'pdfHtml5', exportOptions: {columns: [1, 2, 3, 4], }},
            {extend: 'print', exportOptions: {columns: [1, 2, 3, 4], }},
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
        }
    });
    table.buttons().container().appendTo('.custom_buttons');
});

$(document).ready(function () {
    "use strict";

    $(".table").on("click", ".changeStatus", function () {
        "use strict";

        var iid = $(this).attr('data-id');
        var user_ion_id = $(this).attr('data-user_ion_id');
        var status_iid = $(this).attr('data-status');

        Swal.fire({
            title: "Do you want to change the status?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Yes",
            denyButtonText: `No`,
            icon: `warning`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'nurse/toggleNurseStatus',
                    method: 'post',
                    data: {
                        'iid': iid,
                        'user_ion_id': user_ion_id,
                        'status_iid': status_iid,
                    },
                    dataType: 'json',
                    success: function (response) {
                        "use strict";

                        if(response.error === false){
                            var toggleStatus = $('a.changeStatus[data-user_ion_id="'+user_ion_id+'"]');
                            if(parseInt(status_iid) == 0){
                                toggleStatus.removeClass('btn-danger').addClass('btn-success').attr('data-status', '1').text('Active');
                            }else{
                                toggleStatus.removeClass('btn-success').addClass('btn-danger').attr('data-status', '0').text('Inactive');
                            }
                            // $('a.changeStatus[data-user_ion_id="'+user_ion_id+'"]').removeClass();
                        }else{

                        }
                    }
                })
            } else if (result.isDenied) {
                return true;
            }
        });
    });
});

$(document).ready(function () {
    "use strict";
    $(".flashmessage").delay(3000).fadeOut(100);
});


