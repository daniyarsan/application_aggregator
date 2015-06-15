$(function () {
    $(document).on('click', '.send_message_to_user', function () {
        $('.box-info').show();
        var userId = $(this).attr('id');
        var path = Routing.generate('appform_backend_user_sendmessage', {"id": userId});

        $.ajax({
            url: path,
            success: function (data) {
                $('.box-info').hide();
                alert(data);
            },
            error: function (data) {
                $('.box-info').hide();
                alert('error');
            }
        });
    });

    $(".checkbox-toggle").click(function () {
        var clicks = $(this).data('clicks');
        if (clicks) {
            $("input[type='checkbox']", ".table-striped").iCheck("uncheck");
        } else {
            $("input[type='checkbox']", ".table-striped").iCheck("check");
        }
        $(this).data("clicks", !clicks);
    });

    $(document).on('click', '.send-message-from-checkbox', function () {


        var usersIds = $(".table-striped input:checkbox:checked").map(function () {
            return $(this).attr('id');
        }).get();
        if (usersIds.length) {
            $('.box-info').show();
            var path = Routing.generate('appform_backend_send_mess_to_users');
            $.ajax({
                type: "POST",
                url: path,
                data: {data: usersIds},
                success: function (data) {
                    $('.box-info').hide();
                },
                error: function (data) {
                    $('.box-info').hide();
                }
            });
        }
    });

    $(document).on('click', '.remove-users-from-checkbox', function () {
        var usersIds = $(".table-striped input:checkbox:checked").map(function () {
            return $(this).attr('id');
        }).get();

        if (usersIds.length) {
            $('.box-info').show();
            var path = Routing.generate('appform_backend_remove_users');
            $.ajax({
                type: "POST",
                url: path,
                data: {usersId: usersIds},
                success: function (data) {
                    $('.box-info').hide();
                    location.reload();
                },
                error: function (data) {
                    $('.box-info').hide();
                }
            });
        }
    });

    $(document).on('click', '.create-report-from-checkbox', function () {
        var usersIds = $(".table-striped input:checkbox:checked").map(function () {
            return $(this).attr('id');
        }).get();

        if (usersIds.length) {
            $('.box-info').show();
            var path = Routing.generate('appform_backend_user_report');
            $.ajax({
                type: "POST",
                url: path,
                data: {usersId: usersIds},
                success: function (data) {
                    $('.box-info').hide();
                    $('#dwlxls').html('<a href="'+data.url+'" class="btn btn-warning btn-sm create-report-from-checkbox">Download created report</a>');
                },
                error: function (data) {
                    $('.box-info').hide();
                }
            });
        }
    });

});