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

    /*
     * Flot Interactive Chart
     * -----------------------
     */
    // We use an inline data source in the example, usually data would
    // be fetched from a server

    var data = [], totalPoints = 31, maxResult = 0;

    function getRandomData() {

        if (data.length > 0)
            data = data.slice(1);
        var resultArray = [];

        // Do a random walk
        console.log(data.length);
        if (data.length < totalPoints) {

            var path = Routing.generate('appform_backend_register_user');
            $.ajax({
                type: "POST",
                url: path,
                success: function (result) {
                    //resultArray = result;

                    $.each(result, function (i, item) {
                        data.push(item);
                    });
                },
                error: function (result) {
                    data.push(0);
                }
            });

        }

        // Zip the generated y values with the x values
        var res = [];
        for (var i = 0; i < data.length; ++i) {
            res.push([i, data[i]]);
        }

        return res;
    }

    var interactive_plot = $.plot("#interactive", [getRandomData()], {
        grid: {
            borderColor: "#f3f3f3",
            borderWidth: 1,
            tickColor: "#f3f3f3"
        },
        series: {
            shadowSize: 0, // Drawing is faster without shadows
            color: "#3c8dbc"
        },
        lines: {
            fill: true, //Converts the line chart to area chart
            color: "#3c8dbc"
        },
        yaxis: {
            min: 0,
            max: 40,
            show: true
        },
        xaxis: {
            show: true,
            min: 1,
            max: 30,
            ticks: 30
        }
    });

    var updateInterval = 2000; //Fetch data ever x milliseconds 600000
    var realtime = "off";
    setTimeout(update, updateInterval);
    function update() {

        interactive_plot.setData([getRandomData()]);

        interactive_plot.draw();
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === "on") {
        update();
    }
    //REALTIME TOGGLE
    $("#realtime .btn").click(function () {
        if ($(this).data("toggle") === "on") {
            realtime = "on";
        }
        else {
            realtime = "off";
        }
        update();
    });
    /*
     * END INTERACTIVE CHART
     */
});