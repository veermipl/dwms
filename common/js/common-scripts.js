/*---LEFT BAR ACCORDION----*/

"use strict";

$(function() {

    "use strict";

    $('#nav-accordion').dcAccordion({

        eventType: 'click',

        autoClose: true,

        saveState: true,

        disableLink: true,

        speed: 'slow',

        showCount: false,

        autoExpand: true,

//        cookie: 'dcjq-accordion-1',

        classExpand: 'dcjq-current-parent'

    });

});



var Script = function () {

    "use strict";



//    sidebar dropdown menu auto scrolling



    jQuery('#sidebar .sub-menu > a').on('click', function () {

        var o = ($(this).offset());

        var diff = 250 - o.top;

        if(diff>0)

            $("#sidebar").scrollTo("-="+Math.abs(diff),500);

        else

            $("#sidebar").scrollTo("+="+Math.abs(diff),500);

    });



//    sidebar toggle



    $(function() {

        "use strict";

        function responsiveView() {

            "use strict";

            var wSize = $(window).width();

            if (wSize <= 768) {

                $('#container').addClass('sidebar-close');

                $('#sidebar > ul').hide();

            }



            if (wSize > 768) {

                $('#container').removeClass('sidebar-close');

                $('#sidebar > ul').show();

            }

        }

        $(window).on('load', responsiveView);

        $(window).on('resize', responsiveView);

    });



    $('.fa-bars').on('click', function () {

        if ($('#sidebar > ul').is(":visible") === true) {

            $('#main-content').css({

                'margin-left': '0px'

            });

            $('#sidebar').css({

                'margin-left': '-210px'

            });

            $('#sidebar > ul').hide();

            $("#container").addClass("sidebar-closed");

        } else {

            $('#main-content').css({

                'margin-left': '200px'

            });

            $('#sidebar > ul').show();

            $('#sidebar').css({

                'margin-left': '0'

            });

            $("#container").removeClass("sidebar-closed");

        }

    });



// custom scrollbar




  

// widget tools



    jQuery('.panel .tools .fa-chevron-down').on('click', function () {

        var el = jQuery(this).parents(".panel").children(".panel-body");

        if (jQuery(this).hasClass("fa-chevron-down")) {

            jQuery(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");

            el.slideUp(200);

        } else {

            jQuery(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");

            el.slideDown(200);

        }

    });



    jQuery('.panel .tools .fa-times').on('click', function () {

        jQuery(this).parents(".panel").parent().remove();

    });





//    tool tips



    $('.tooltips').tooltip();



//    popovers



    $('.popovers').popover();







// custom bar chart



    if ($(".custom-bar-chart")) {

        $(".bar").each(function () {

            "use strict";

            var i = $(this).find(".value").html();

            $(this).find(".value").html("");

            $(this).find(".value").animate({

                height: i

            }, 2000)

        })

    }



}();