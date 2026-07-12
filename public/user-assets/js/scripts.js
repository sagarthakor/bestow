const CLEVER = {

    getFinancialYearRange(step = 0) {

        let date;

        if (step > 0) {

        } else if (step < 0) {

        } else {
            date = moment().startOf('year').format('YYYY-MM-DD');
        }

        if (parseInt(moment().format('M')) >= 4) {

            moment().add('years', 0).startOf().add('months', 3).format('YYYY-MM-DD');

        } else {

        }
    },

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

    copyToClipboard(element, message) {


        var clipboard = new ClipboardJS(element);

        clipboard.on('success', function (e) {
            CLEVER.notification(message, 'success');
        });

        clipboard.on('error', function (e) {
            CLEVER.notification('Error while copying data', 'error');
        });

    },

    showPassword(element) {

        var passwordElement = $(element).parent().find('input.passwordInput');

        var inputType = passwordElement.attr('type');

        if (inputType === 'password') {
            $(element).html('<i class="fas fa-eye"></i>');
            passwordElement.attr('type', 'text');
        } else {
            $(element).html('<i class="fas fa-eye-slash"></i>');
            passwordElement.attr('type', 'password');
        }

    },

    notification(text, alertType = 'info', duration = 2500, position = 'right', gravity = 'bottom') {

        let background;
        let color = '#fff';

        if (alertType === 'danger') {
            background = "linear-gradient(to right, #F44336, #b70743)";
        } else if (alertType === 'success') {
            background = "linear-gradient(to right, #0fb000, #96c93d)";
        } else if (alertType === 'warning') {
            background = "linear-gradient(to right, #FF5722, #FF9800)";
        } else {
            background = "linear-gradient(to right, #2196F3, #3F51B5)";
        }


        Snackbar.show({
            text: text,
            duration: duration,
            pos: gravity + '-' + position,
            actionTextColor: color,
            backgroundColor: background,
            actionText: 'X'
        });

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

    formConfirmation(formId, message = '') {

        Swal.fire({
            title: "Are you sure?",
            text: message,
            icon: "info",
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
        }).then(function (result) {

            if (result.value) {
                CLEVER.blockUI(true);
                $(formId).submit();
            }

        });

    },

    slugify(str) {

        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap Ã± for n, etc
        let from = "Ã Ã¡Ã¤Ã¢Ã¨Ã©Ã«ÃªÃ¬Ã­Ã¯Ã®Ã²Ã³Ã¶Ã´Ã¹ÃºÃ¼Ã»Ã±Ã§Â·/_,:;";
        let to = "aaaaeeeeiiiioooouuuunc------";

        for (let i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str;
    },

    getChildUsers(element, input_class) {

        let btn_element = $(element);
        let unique_id = null;
        let input_element = $('.' + input_class);

        btn_element.closest('form');

        unique_id = input_element.val();

        if (unique_id.length < 6) {

            CLEVER.notification('Invalid Distributed ID, Try Again', "error");
            $('.user-get-response').remove();
            $(element).val('');
            return false;
        }

        $.get('/user/account/get-children', {'unique_id': unique_id}, function (response) {

            $('.user-get-response').remove();

            if (response.status) {

                btn_element.parent().parent().after('' +
                    '<div class="user-get-response text-center mb-2 mt-2 user_unique_id_name">' +
                    '<input type="hidden" name="user_id" value="' + response.user.id + '">' +
                    '<input type="hidden" name="unique_id" value="' + response.user.unique_id + '">' +
                    '<div class="alert alert-primary">' + response.user.name + '</div>' +
                    '</div>');
            } else {

                CLEVER.notification(response.message, 'error', 'Oops');
                $(element).val('');
            }
        });

    },

    getUser(element, input_class) {
        var btn_element = $(element);
        var unique_id = null;
        var input_element = $('.' + input_class);

        btn_element.closest('form');

        unique_id = input_element.val();

        if (unique_id.length < 6) {

            CLEVER.notification('Invalid Distributed ID, Try Again', 'error', 'Oops');
            $('.user-get-response').remove();
            input_element.val('');
            return false;
        }

        $.get('/admin/user/get', {'unique_id': unique_id}, function (response) {

            $('.user-get-response').remove();

            if (response.status) {

                btn_element.parent().after('' +
                    '<div class="user-get-response text-center m-b-10">' +
                    '<input type="hidden" name="user_id" id="user_id" value="' + response.user.id + '">' +
                    '<div class="alert alert-info mt-1 p-1 alert-validation-msg">' + response.user.name + '</div>' +
                    '</div>');
            } else {
                CLEVER.notification(response.message, 'error');
                input_element.val('');
            }
        });

    },

    getDownLineUser(element, input_class) {

        var btn_element = $(element);
        var tracking_id = null;
        var input_element = $('.' + input_class);

        btn_element.closest('form');

        tracking_id = input_element.val();

        if (tracking_id.length < 6) {

            Swal.fire('Error', 'Invalid Tracking ID, Try Again', 'error');
            $('.user-get-response').remove();
            $(element).val('');
            return false;
        }

        $.get('/user/account/get-user', {'tracking_id': tracking_id}, function (response) {

            $('.user-get-response').remove();

            if (response.status) {

                btn_element.parent().parent().after('' +
                    '<div class="user-get-response text-center mb-10 mt-2 user_tracking_name">' +
                    '<input type="hidden" name="user_id" value="' + response.user.id + '">' +
                    '<input type="hidden" name="tracking_id" value="' + response.user.tracking_id + '">' +
                    '<div class="alert alert-primary">' + response.user.name + '</div>' +
                    '</div>');

            } else {
                Swal.fire('Error', response.message, 'error');
                $(element).val('');
            }
        });

    }
};

$('.date-range').daterangepicker({
    locale: {
        format: 'DD MMM YYYY'
    },
    autoUpdateInput: false,
    buttonClasses: ['btn', 'btn-sm'],
    applyClass: 'btn-success',
    cancelClass: 'btn-danger',
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}).on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));
}).on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
});

let color = $('#onlineOfflineUser');

window.addEventListener("online", function () {
    CLEVER.notification('You are Online', 'success', "toast-bottom-right")
    color.removeClass('avatar-status-busy');
    color.addClass('avatar-status-online');
});

window.addEventListener("offline", function () {
    CLEVER.notification('Oops internet Connection lost, Offline mode', 'error', "toast-bottom-right")
    color.removeClass('avatar-status-online');
    color.addClass('avatar-status-busy');
});
