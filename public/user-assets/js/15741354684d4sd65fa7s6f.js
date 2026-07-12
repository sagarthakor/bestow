const CLEVER = {
    numberInput(evt, decimal = true) {

        let theEvent = evt || window.event;

        // Handle paste
        let key;

        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }

        let regex = decimal ? /[0-9]|\./ : /[0-9]/;

        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }

    },
    showPassword(element) {

        var passwordElement = $(element).parent().find('input.passwordInput');

        var inputType = passwordElement.attr('type');

        if (inputType === 'password') {
            $(element).html('<i class="fas fa-eye"></i>');
            passwordElement.attr('type', 'text');
        }
        else {
            $(element).html('<i class="fas fa-eye-slash"></i>');
            passwordElement.attr('type', 'password');
        }

    },
    blockUI(show = true) {

        if (!show) {
            $.unblockUI();
            return false;
        }

        $.blockUI({
            message:
                '<div class="d-flex justify-content-center align-items-center"><p class="me-50 mb-0">Please wait...</p> ' +
                '<div class="spinner-grow spinner-grow-sm text-white" role="status"></div> </div>',
            css: {
                backgroundColor: 'transparent',
                color: '#fff',
                border: '0'
            },
            overlayCSS: {
                opacity: 0.5
            }
        });

    },
};

$(document).ready(function(e) {

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-Token': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery(document).on('change', '.select-change', function (e) {

        e.preventDefault();
        e.stopPropagation();

        var val		= jQuery(this).val();
        var target  = jQuery(this).attr('data-target');
        var string 	= jQuery(this).attr('data-string');
        var url 	= jQuery(this).attr('data-url');
        var type 	= jQuery(this).attr('data-type');
        var other   = jQuery(this).attr('data-other-target');
        var trigger = jQuery(this).attr('select-trigger');

        if (val === 'Others_state') {
            $("#add_city_show").removeClass('d-none');
            $("#add_state_show").removeClass('d-none');
            $(".old_city").addClass('d-none');

        } else if(val === 'Others_city') {
            $("#add_city_show").removeClass('d-none');
        } else {
            $("#add_city_show").addClass('d-none');
            $("#add_state_show").addClass('d-none');
            $(".old_city").removeClass('d-none');

        }

        jQuery.ajax({
            type: type,
            url: url+'?'+string+'='+val,
            dataType : 'JSON',
            beforeSend : function() {
                jQuery('#'+target).html('<option value="">Loading..</option>');

                if( other !== '' && other !== 'undefined' ) {
                    jQuery('.'+other).html('<option value="">Choose..</option>');
                }
            },
            success : function(data) {

                if (data.addClass==='') {
                    jQuery('#'+target).html(data.html);
                } else {
                    jQuery('#'+target).addClass(data.addClass);
                }

                if(data.parent_id !== '' && data.parent_id !== undefined){
                    jQuery('.'+data.child_class).show();

                    jQuery('.'+data.sub_child_class).show();

                    if(data.child_class !== '' && data.child_url !== ''){
                        jQuery('.'+data.child_class).attr('href',data.child_url);
                    }

                } else {
                    jQuery('.'+data.child_class).css('display','none');
                    jQuery('.'+data.sub_child_class).css('display','none');
                }

                jQuery('#'+target).html(data.options);

                if( trigger !== '' && trigger !== 'undefined' ) {
                    jQuery("."+trigger).trigger( "change" );
                }
            },
        });

    });

    $(".select2").select2();
});
