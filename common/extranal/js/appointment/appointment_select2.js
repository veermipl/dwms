 "use strict";
$(document).ready(function () {
    "use strict";
    $('.pos_client').hide();
    $(document.body).on('change', '#pos_select', function () {
        "use strict";
        var v = $("select.pos_select option:selected").val();
        if (v === 'add_new') {
            $('.pos_client').show();
        } else {
            $('.pos_client').hide();
        }
    });
    $('.pos_client1').hide();
    $(document.body).on('change', '#pos_select1', function () {
        "use strict";
        var v = $("select.pos_select1 option:selected").val();
        if (v === 'add_new') {
            $('.pos_client1').show();
        } else {
            $('.pos_client1').hide();
        }
    });

});

$(document).ready(function () {
    "use strict";
    $("#pos_select").select2({
        placeholder: select_patient,
        allowClear: true,
        ajax: {
            url: 'patient/getPatientinfoWithAddNewOption',
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
    $("#pos_select1").select2({
        placeholder: select_patient,
        allowClear: true,
        ajax: {
            url: 'patient/getPatientinfoWithAddNewOption',
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


