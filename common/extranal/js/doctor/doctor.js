"use strict";
$(document).ready(function () {
    "use strict";

    $(".table").on("click", ".editbutton", function () {
        "use strict";

        var iid = $(this).attr('data-id');
        $("#img").attr("src", "uploads/cardiology-patient-icon-vector-6244713.jpg");
        $('#editDoctorForm').trigger("reset");
        $('#myModal2').modal('show');
        $.ajax({
            url: 'doctor/editDoctorByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";

                // Populate the form fields with the data returned from server
                $('#editDoctorForm').find('[name="id"]').val(response.doctor.id).end();
                $('#editDoctorForm').find('[name="name"]').val(response.doctor.name).end();
                $('#editDoctorForm').find('[name="password"]').val(response.doctor.password).end();
                $('#editDoctorForm').find('[name="email"]').val(response.doctor.email).end();
                $('#editDoctorForm').find('[name="address"]').val(response.doctor.address).end();
                $('#editDoctorForm').find('[name="phone"]').val(response.doctor.phone).end();
                $('#editDoctorForm').find('[name="department"]').val(response.doctor.department).end();
                $('#editDoctorForm').find('[name="profile"]').val(response.doctor.profile).end();

                if (typeof response.doctor.img_url !== 'undefined' && response.doctor.img_url !== '') {
                    // $("#img").attr("src", response.doctor.img_url);
                    $("#img_url_output").attr("src", response.doctor.img_url).show();
                }

                $('.js-example-basic-single.department').val(response.doctor.department).trigger('change');
            }
        })
    });
});

$(document).ready(function () {
    "use strict";

    $(".table").on("click", ".inffo", function () {
        "use strict";

        var iid = $(this).attr('data-id');

        $('.nameClass').html("").end();
        $('.emailClass').html("").end();
        $('.addressClass').html("").end();
        $('.phoneClass').html("").end();
        $('.departmentClass').html("").end();
        $('.profileClass').html("").end();
        $.ajax({
            url: 'doctor/editDoctorByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";

                $('#editDoctorForm1').find('[name="id"]').val(response.doctor.id).end();
                $('.nameClass').append(response.doctor.name).end();
                $('.emailClass').append(response.doctor.email).end();
                $('.addressClass').append(response.doctor.address).end();
                $('.phoneClass').append(response.doctor.phone).end();
                $('.departmentClass').append(response.doctor.department).end();
                $('.profileClass').append(response.doctor.profile).end();

                $("#img1").attr("src", "uploads/cardiology-patient-icon-vector-6244713.jpg");

                if (typeof response.doctor.img_url !== 'undefined' && response.doctor.img_url !== '') {
                    $("#img1").attr("src", response.doctor.img_url);
                }

                $('#infoModal').modal('show');
            }
        })
    });
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
                    url: 'doctor/toggleDoctorStatus',
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

// $(document).ready(function () {
//     "use strict";

//     var table = $('#editable-sample').DataTable({
//         responsive: true,

//         "processing": true,
//         "serverSide": true,
//         "searchable": true,
//         "bScrollCollapse": true,
//         "ajax": {
//             url: "doctor/getDoctor",
//             type: 'POST',
//         },
//         scroller: {
//             loadingIndicator: true
//         },

//         dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
//                 "<'row'<'col-sm-12'tr>>" +
//                 "<'row'<'col-sm-5'i><'col-sm-7'p>>",

//         buttons: [
//             {extend: 'copyHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
//             {extend: 'excelHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
//             {extend: 'csvHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
//             {extend: 'pdfHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
//             {extend: 'print', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
//         ],
//         aLengthMenu: [
//             [10, 25, 50, 100, -1],
//             [10, 25, 50, 100, "All"]
//         ],
//         iDisplayLength: 100,
//         "order": [[0, "desc"]],

//         "language": {
//             "lengthMenu": "_MENU_",
//             search: "_INPUT_",
//             "url": "common/assets/DataTables/languages/" + language + ".json"
//         }
//     });
//     table.buttons().container().appendTo('.custom_buttons');
// });

$(document).ready(function () {
    "use strict";

    var table = $('#editable-sample').DataTable({
        responsive: true,

        "processing": true,
        "serverSide": true,
        "searchable": false,//remove to show searching 
        "searching": false, //remove to show searching 
        "paging": false, //remove to show pagination
        "info": false,//remove to show searching 
        "ordering": false, //remove to show sorting
        "bScrollCollapse": true,
        "ajax": {
            url: "doctor/getDoctor",
            type: 'POST',
        },
        scroller: {
            loadingIndicator: true
        },
        //Uncomment here for display export options.
        // dom: 'Bfrtip',
        // dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
        //         "<'row'<'col-sm-12'tr>>" +
        //         "<'row'<'col-sm-5'i><'col-sm-7'p>>",

        // buttons: [
        //     {extend: 'copyHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
        //     {extend: 'excelHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
        //     {extend: 'csvHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
        //     {extend: 'pdfHtml5', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
        //     {extend: 'print', exportOptions: {columns: [0, 1, 2, 3, 4, 5], }},
        // ],
        // aLengthMenu: [
        //     [10, 25, 50, 100, -1],
        //     [10, 25, 50, 100, "All"]
        // ],
        // iDisplayLength: 100,
        // "order": [[0, "desc"]],

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

    $(".flashmessage").delay(3000).fadeOut(100);
});

var loadDocFile = function(event, id, error_id) {
    var file = event.target.files[0];
    var output = document.getElementById(id);
    var allowedExtensions = ['jpeg', 'png', 'jpg', 'svg'];
    var extension = file.name.split('.').pop().toLowerCase();

    if (allowedExtensions.indexOf(extension) === -1) {
        $('span#' + error_id + '').text('Invalid file format. Please select a JPG, JPEG, or PNG image file.');
        output.setAttribute('src', '');
        output.style.display = "none";
        return;
    }

    $('span#' + error_id + '').text('');
    output.src = URL.createObjectURL(file);
    output.style.display = "block";

    output.onload = function() {
        URL.revokeObjectURL(output.src); // free memory
    };
};
