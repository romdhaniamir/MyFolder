var count = 0;
var length_tabel = 0;
var total = 0;
var unity = 0;
var service_web_url = $.parseJSON(ajax_object_url);
$(function () {
    get_length_tabel();
    $('#unity_table').change(function () {

        total = count_pages();

        count = 0;
        get_selection_page(count, total);//html selection
        $('.current-page').text(count + 1 + "/" + total);
        get_tabel_from_server('tabel_action', unity, $('#list_id').val(), count);
    });

    $('.next').click(function () {
        unity = $('#unity_table').val();

        if (count + 1 < total)
        {
            count += 1;
            get_selection_page(count, total);
            $('.current-page').text(count + 1 + "/" + total);
            get_tabel_from_server('tabel_action', unity, $('#list_id').val(), count);
        }
    });

    $('.previous').click(function () {
        if (count > 0)
        {
            count -= 1;
            get_selection_page(count, total);
            $('.current-page').text(count + 1 + "/" + total);
            get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);
        }
    });

    $('.last').click(function () {
        count = total - 1;
        get_selection_page(count, total);
        $('.current-page').text(count + 1 + "/" + total);
        get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);

    });
    $('.first').click(function () {
        count = 0;
        get_selection_page(count, total);
        $('.current-page').text(count + 1 + "/" + total);
        get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);

    });

    $('.select-page').change(function () {
        count = $(this).val() - 1;
        get_selection_page(count, total);
        $('.current-page').text(count + 1 + "/" + total);
        get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);

    });

    $(document).on("click", ".delete-button", function () {


        select_action_ajax('delete', this.id)


    });
   

    /*************************************functions***************************/
    /**
     * 
     * @param String action
     * @param int/array object
     * }
     */
    function select_action_ajax(action, object) {
        var data = {
            'action': action,
            'id': object

        };

        $.post(service_web_url.ajax_contact, data, function (response) {
            $(".alert").addClass('hide');
            $(".container-fluid").prepend(response);

            get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);
            get_length_tabel();
            $(".table-to-scroll").getNiceScroll().resize();

        });
    }
    function update_list_ajax(action, array,id) {
        var data = {
            'action': action,
            'array': array,
            'id':id

        };

        $.post(service_web_url.ajax_contact, data, function (response) {
            $(".alert").addClass('hide');
            $(".container-fluid").prepend(response);

            get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);
            get_length_tabel();
             $(".table-to-scroll").getNiceScroll().resize();

        });
    }
    
    function get_list_for_user() {
        var data = {
            'action': 'get_lists',
        };

        $.post(service_web_url.ajax_contact, data, function (response) {
            var list_options = "";
            var obj = $.parseJSON(response);
            //console.log(obj);
            $.each(obj, function (key, object) {
                list_options += '<option value="' + object.list_id + '">' + object.name + '</option>'
            });
            

            $('.select-lists').html(list_options);

        });

    }

    
    
    


    /*===========================================Action to do ========================*/


    $('body').on('change', '#all_edit', function () {

        if (this.checked) {
            $('.item-to-edit').prop('checked', true);
        } else {
            $('.item-to-edit').prop('checked', false);
        }
    });


    var checked = [];
    $(".delete-list").click(function () {

        if ($("input[name='chk[]']:checked").length > 0) {

            $("input[name='chk[]']:checked").each(function ()
            {
                checked.push(parseInt($(this).val()));

            });

            select_action_ajax('delete_list', checked);

        } else {
            $('#all_edit').prop('checked', false);
        }
       
    });
    $(".move-list").click(function () {

        if ($("input[name='chk[]']:checked").length > 0) {
          
            $("input[name='chk[]']:checked").each(function ()
            {
                checked.push(parseInt($(this).val()));

            });
            $("#popup-action-list").modal();
             get_list_for_user();
        } else {
            $('#all_edit').prop('checked', false);
        }
       
    });
    $(document).on("click", "#popup-action-list #accepted", function () {
        $('#popup-action-list').modal('hide');
         update_list_ajax('move_list', checked,$('.select-lists').val());
        checked = [];
    });

});



/**
 * append pages in html select
 * @param int count
 * @param int total
 * 
 */
function get_selection_page(count, total)//html selection
{
    var i = 1;
    var options = "";

    while (i !== total + 1) {
        if (count + 1 === i)
            options += '<option selected>' + i + '</option>';
        else
            options += '<option >' + i + '</option>';

        i++;
    }

    $(".select-page").html(options);
}
/***
 * @return int total 
 */
function count_pages() {

    unity = $('#unity_table').val();
    total = 0;
    if ((length_tabel % unity) == 0)
        total = length_tabel / unity;
    else
        total = length_tabel / unity + 1;

    total = Math.floor(total);
    $('.current-page').text(count + 1 + "/" + total);

    return total;

}
/*get length table */

function get_length_tabel()
{
    var data = {
        'action': 'length_tabel',
        'list_id': $('#list_id').val()
    };
    $.post(service_web_url.ajax_contact, data, function (response) {
        length_tabel = response;
        total = count_pages();
        get_selection_page(count, total);//html selection
        $(".total span").text(length_tabel);
        get_length_trash();
    });
}

/**
 * @param string action
 * @param int unity
 * @param id list_id
 * @param int count
 * 
 */
function get_tabel_from_server(action, unity, list_id, count)
{
    
    var data = {
        'action': action,
        'unity': unity,
        'list_id': list_id,
        'count': count
    };
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    $.post(service_web_url.ajax_contact, data, function (response) {
        //   console.log('Got this from the server: ' + response);
        $(".canvas-table").html(response);
        $(".table-to-scroll").getNiceScroll().resize();
        hide_alert();

    });
function hide_alert(){
  setTimeout(function() {
        $(".alert-top").slideUp('400');
    }, 5000);
    }
}