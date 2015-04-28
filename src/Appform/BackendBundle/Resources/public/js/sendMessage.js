$(function(){
    $(document).on('click', '.send_message_to_user', function () {
        var userId = $(this).attr('id');
        var path = Routing.generate('appform_backend_user_sendmessage', {"id": userId});

        $.ajax({
            url: path,
            success: function(data)
            {
                alert(data);

            },
            error: function(e){
                alert('error');
            }
        });
    });
});