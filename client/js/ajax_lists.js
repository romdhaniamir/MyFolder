$(function () {
    service_web_url = $.parseJSON(ajax_object_url);
    var length_trash = 0;

    var service_web_url = $.parseJSON(ajax_object_url);

    get_length_trash(service_web_url);

    var list_id = "";
    $(document).on("click", ".move-all-list", function () {
        list_id = this.id;
        get_list_for_user();
        $('#popup-move-all-list').modal();
    });

    $(document).on("click", "#popup-move-all-list #accepted", function () {
        $('#popup-move-all-list').modal('hide');

        var data = {
            'action': 'move_list',
            'list_id': list_id,
            'move_id': $('.select-lists-all').val()
        };


        $.post(service_web_url.ajax_lists, data, function (response) {

            $(".alert").addClass('hide');
            $(".container-fluid").prepend(response);
            hide_alert();
            try {
                get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), 10);
                get_length_tabel();
                $(".table-to-scroll").getNiceScroll().resize();

            } catch (err) {
            }

        });
        list_id = "";


    });
    $(document).on("click", "#export-all", function () {
        var data = {
            'action': 'export_all', }
        $('#export-all-load-icon').removeClass('hidden');
        $.post(service_web_url.ajax_lists, data, function (path) {
            console.log("excel file :" + path);
            window.open(
                    path,
                    '_blank' // <- This is what makes it open in a new window.
                    );
            $('#export-all-load-icon').addClass('hidden');
        });
    });
    

    $(document).on("click", ".export-list", function () {
     
        var data = {
            'action': 'export_list',
            'list_id':$(this).data('id')
        }
        $('#export-all-load-icon').removeClass('hidden');
        $.post(service_web_url.ajax_lists, data, function (path) {
            console.log("excel file :" + path);
            window.open(
                    path,
                    '_blank' // <- This is what makes it open in a new window.
                    );
            $('#export-all-load-icon').addClass('hidden');
        });
    });


    function get_list_for_user() {
        var data = {
            'action': 'get_lists',
        };

        $.post(service_web_url.ajax_lists, data, function (response) {
            var list_options = "";
            var obj = $.parseJSON(response);
            console.log(obj);
            $.each(obj, function (key, object) {
                list_options += '<option value="' + object.list_id + '">' + object.name + '</option>'
            });


            $('.select-lists-all').html(list_options);

        });
    }
    function hide_alert() {
        setTimeout(function () {
            $(".alert-top").slideUp('400');
        }, 5000);
    }

});
var service_web_url = $.parseJSON(ajax_object_url);
function get_length_trash()
{
    var data = {
        'action': 'length_trash',
    };
    $.post(service_web_url.ajax_lists, data, function (response) {

        length_trash = response;
        $("#trash-count").html('<span class="animated bounce">' + length_trash + '</span>');
    });


}

