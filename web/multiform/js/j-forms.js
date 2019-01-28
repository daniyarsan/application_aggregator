$(document).ready(function () {
    /***************************************/
    /* Form validation */
    /***************************************/
    $('#j-forms').validate({
        /* @validation states + elements */
        errorClass: 'error-view',
        validClass: 'success-view',
        errorElement: 'span',
        onkeyup: false,
        onclick: false,
        /* @validation rules */
        rules: {
            'appform_frontendbundle_applicant[personalInformation][completion]': {
                required: true
            }
        },
        messages: {},
        // Add class 'error-view'
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
});