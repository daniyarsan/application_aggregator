$(document).ready(function () {
    $("body").click
    (function (e) {
        e.target.classList.contains('toggletip') || e.target.classList.contains('helpbox') ? $(".helpbox").show() : $(".helpbox").hide();
        }
    );

    /***************************************/
    /* Form validation */
    /***************************************/
    $('#j-forms').validate({
        /* @validation states + elements */
        errorClass: 'error-view',
        validClass: 'success-view',
        errorElement: 'span',
        onkeyup: false,
        // onfocusout: false,
        onclick: false,
        rules: {
            'appform_frontendbundle_applicant[personalInformation][completion]': {required: true},
            'appform_frontendbundle_applicant[personalInformation][discipline]': {discipline: 'discipline'},
            'appform_frontendbundle_applicant[personalInformation][specialtyPrimary]': {discipline: 'specialty'},
            'appform_frontendbundle_applicant[personalInformation][yearsLicenceSp]': {experience: true},
            'appform_frontendbundle_applicant[personalInformation][phone]': {regx: /^([1+]{2})\s{1}((?!800|855|888|900)\d{3})\s{1}(\d{3})\s{1}(\d{4})$/},

        },
        messages: {},
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.input').removeClass(validClass).addClass(errorClass);
            if ($(element).is(':checkbox') || $(element).is(':radio')) {
                $(element).closest('.check').removeClass(validClass).addClass(errorClass);
            }
        },
        // Add class 'success-view'
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.input').removeClass(errorClass).addClass(validClass);
            if ($(element).is(':checkbox') || $(element).is(':radio')) {
                $(element).closest('.check').removeClass(errorClass).addClass(validClass);
            }
        },
        // Error placement
        errorPlacement: function (error, element) {
            if ($(element).is(':checkbox') || $(element).is(':radio')) {
                $(element).closest('.check').append(error);
            } else {
                $(element).closest('.unit').append(error);
            }
        },
        // Submit the form
        submitHandler: function (form) {
            // Add class 'processing' to the submit button
            $('#j-forms button[type="submit"]').attr('disabled', true).addClass('processing');
            form.submit();
        }
    });

    /* Custom Methods */
    $.validator.addMethod("regx", function (value, element, regexpr) {
        return regexpr.test(value);
    }, "1+(800,855,888,900) are not supported phone formats");
    var status;
    jQuery.validator.addMethod("discipline", function (value, element, type) {
        var errorClass = 'error-view';
        var validClass = 'success-view';
        $('#j-forms').ajaxSubmit({
            url: 'https://app.healthcaretravelers.com/validate/' + type,
            success: function (data) {
                // console.log(data);
                status = data.status;
                if (data.status == false) {
                    $(element).closest('.input').removeClass(validClass).addClass(errorClass);
                    $("#response").html('<div class="error-message unit"><i class="fa fa-warning"></i> ' + data.message + '</div>');
                } else {
                    $(element).closest('.input').removeClass(errorClass).addClass(validClass);
                    $("#response").html('');
                    $(element).parent().parent().find('span').remove();
                }
            }
        });
        return status ? true : false;
    }, 'Please set correct discipline or specialty');
    $.validator.addMethod("experience", function (value, element, arg) {
        return arg < value;
    }, '2 years minimum experience are required');


    /***************************************/
    /* end form validation */
    /***************************************/

    /***************************************/
    /* Multistep form */
    /***************************************/
    // if multistep form exists
    if ($('form.j-multistep').length) {

        // For each multistep form
        // Execute the function
        $('form.j-multistep').each(function () {

            // Variables
            var
                $id = $(this).attr('id'),							// form ID
                $i = $('#' + $id + ' fieldset').length,			// number of fieldsets
                $step = $('#' + $id + ' .step').length,				// number of steps
                $next_btn = $('#' + $id + ' .multi-next-btn'),			// 'next' button
                $prev_btn = $('#' + $id + ' .multi-prev-btn'),			// 'previous' button
                $submit_btn = $('#' + $id + ' .multi-submit-btn');			// 'submit' button

            // Add class "active-fieldset" to the first fieldset on the page
            $('#' + $id + ' fieldset').eq(0).addClass('active-fieldset');

            // If class ".step" exists
            // Add class "active-step"
            if ($step) {
                $('#' + $id + ' .step').eq(0).addClass('active-step');
                $("#j-forms").data("validator").settings.ignore = ":hidden";
            }

            // If first fieldset has class 'active'
            // Processing the buttons
            if ($('#' + $id + ' fieldset').eq(0).hasClass('active-fieldset')) {
                $submit_btn.css('display', 'none');
                $prev_btn.css('display', 'none');
            }

            // Click on the "next" button
            $next_btn.on('click', function () {
                // If current fieldset doesn't have validation errors
                // Switch to the next step
                if ($('#' + $id).valid() == true) {

                    // Switch the "active" class to the next fieldset
                    $('#' + $id + ' fieldset.active-fieldset').removeClass('active-fieldset').next('fieldset').addClass('active-fieldset');

                    // If ".step" exists
                    // Switch the "active" class to the next step
                    if ($step) {
                        $('#' + $id + ' .step.active-step').removeClass('active-step').addClass('passed-step').next('.step').addClass('active-step');
                        if ($('#' + $id + ' fieldset').eq($i - 1).hasClass('active-fieldset')) {
                            $("#j-forms").data("validator").settings.ignore = ":hidden:not(select)";
                        } else {
                            $("#j-forms").data("validator").settings.ignore = ":hidden";
                        }
                    }

                    // Display "prev" button
                    $prev_btn.css('display', 'block');
                    // If active fieldset is a last
                    // processing the buttons
                    if ($('#' + $id + ' fieldset').eq($i - 1).hasClass('active-fieldset')) {
                        $submit_btn.css('display', 'block');
                        $next_btn.css('display', 'none');
                    }

                    // If current fieldset has validation errors
                } else {
                    return false;
                }
            });

            // Click on the "prev" button
            $prev_btn.on('click', function () {
                // Switch the "active" class to the previous fieldset
                $('#' + $id + ' fieldset.active-fieldset').removeClass('active-fieldset').prev('fieldset').addClass('active-fieldset');

                // If ".step" exists
                // Switch the "active" class to the previous step
                if ($step) {
                    $('#' + $id + ' .step.active-step').removeClass('active-step').prev('.step').removeClass('passed-step').addClass('active-step');
                    $("#j-forms").data("validator").settings.ignore = ":hidden";
                }

                // If active fieldset is a first
                // processing the buttons
                if ($('#' + $id + ' fieldset').eq(0).hasClass('active-fieldset')) {
                    $prev_btn.css('display', 'none');
                }

                // If active fieldset is a penultimate
                // processing the buttons
                if ($('#' + $id + ' fieldset').eq($i - 2).hasClass('active-fieldset')) {
                    $submit_btn.css('display', 'none');
                    $next_btn.css('display', 'block');
                }
            });
        });
        // end "each" statement
    }
    /***************************************/
    /* end multistep form */
    /***************************************/

    /* Detection of mobile device */
    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    if (isMobile.any()) {
        $("#appform_frontendbundle_applicant_appOrigin").val("mobile");
        $('#appform_frontendbundle_applicant_personalInformation_licenseState option[value=0]').attr('selected', 'selected');
        $('#appform_frontendbundle_applicant_personalInformation_desiredAssignementState option[value=0]').attr('selected', 'selected');
    }

    $('.chosen').chosen({
        width: '100%'
    });
    $('input.default').css('width', '100%');

    // Test if this is a mobile device
    if (typeof $.browser == 'undefined') {
        $.browser = {};
    }
    $.browser.device = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
    if ($.browser.device) {
        $('#appform_frontendbundle_applicant_personalInformation_completion').attr('type', 'date');
    } else {
        // Datepicker for arrival date on filters
        var dp = $('#appform_frontendbundle_applicant_personalInformation_completion').datepicker(
            {
                minDate: 0,
                dateFormat: 'yy-mm-dd',
                prevText: '<i class="fa fa-caret-left"></i>',
                nextText: '<i class="fa fa-caret-right"></i>'
            }
        );
        dp.on('changeDate', function (e) {
            dp.datepicker('hide');
        });
    }

    $.mask.definitions['2'] = "[2-9]";
    $("#appform_frontendbundle_applicant_personalInformation_phone").mask('1+ 299 999 9999', {placeholder: 'x'});

    if ($("#appform_frontendbundle_applicant_personalInformation_isOnAssignement").val() == '1') {
        $('#appform_frontendbundle_applicant_personalInformation_completion').removeAttr('disabled').parent().removeClass('disabled-view');
    }
    // Enabled input
    $('#appform_frontendbundle_applicant_personalInformation_isOnAssignement').on('change', function () {
        if (this.value == '1') {
            $('#appform_frontendbundle_applicant_personalInformation_completion').attr('disabled', false).parent().removeClass('disabled-view error-view success-view');
        } else {
            $('#appform_frontendbundle_applicant_personalInformation_completion').attr('disabled', true).parent().addClass('disabled-view').removeClass('success-view error-view');
            if ($('#enable-input-error').length) {
                $('#enable-input-error').css('display', 'none');
            }
        }
        ;
    });

    $('#appform_frontendbundle_applicant_document_file').change(function () {
        $('#file_input').val(this.value);
    });
});