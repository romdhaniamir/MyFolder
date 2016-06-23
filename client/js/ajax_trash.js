$(function () {
    /***** ajax ***/
    var count = 0;
    var length_tabel = 0;
    var total = 0;
    var unity = 0;
    var service_web_url = $.parseJSON(ajax_object_url);
    /*get length table */
    function get_length_tabel()
    {
        var data = {
            'action': 'length_tabel',
        };
        $.post(service_web_url.ajax_trash, data, function (response) {
            length_tabel = response;
            total = count_pages();
            get_selection_page(count, total);//html selection
             $(".total span").text(length_tabel);
             $("#trash-count").html('<span class="animated bounce">'+length_tabel+'</span>');
        });
    }
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
    $(document).on("click", ".edit-button", function () {
        trach_ajax('restore' ,this.id);
    });
    var delete_id="";
    $(document).on("click", ".delete-button", function () {
        delete_id= this.id;
        $('#popup-action').modal();
    });
    
   $(document).on("click","#popup-action #accepted", function () {
        $('#popup-action').modal('hide');
        trach_ajax('delete', delete_id);
        delete_id="";
      
       
   });
   
     var checked = [];
   $(document).on("click", "#popup-action-list #accepted", function () {
          $('#popup-action-list').modal('hide');
          trach_ajax('delete_list' ,checked);
          checked = [];
   });
               
    /*************************************functions***************************/
    function trach_ajax(action ,object){
        var data = {
            'action':action ,
            'id': object

        };

        $.post(service_web_url.ajax_trash, data, function (response) {
            $(".alert").addClass('hide');
            $(".container-fluid").prepend(response);
            
            get_tabel_from_server('tabel_action', $('#unity_table').val(), $('#list_id').val(), count);
            get_length_tabel();
            $(".table-to-scroll").getNiceScroll().resize();
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
        $.post(service_web_url.ajax_trash, data, function (response) {
            //   console.log('Got this from the server: ' + response);
            $(".canvas-table").html(response);
            $(".table-to-scroll").getNiceScroll().resize();
            hide_alert();

        });

    }

    function get_list_for_user() {
        var data = {
            'action': 'get_lists',
        };

        $.post(service_web_url.ajax_trash, data, function (response) {
            var list_options = "";
            var obj = $.parseJSON(response);
            console.log(obj);
            $.each(obj, function (key, object) {
                list_options += '<option value="' + object.list_id + '">' + object.name + '</option>'
            });


            $('.select-lists').html(list_options);

        });

    }

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
     * 
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


    /*===========================================Action to do for list ========================*/


    $('body').on('change', '#all_edit', function () {

        if (this.checked) {
            $('.item-to-edit').prop('checked', true);
        } else {
            $('.item-to-edit').prop('checked', false);
        }
    });
     
    $(".delete-list").click(function () {
     
        if ($("input[name='chk[]']:checked").length > 0) {

            $("input[name='chk[]']:checked").each(function ()
            {
                checked.push(parseInt($(this).val()));
             

            });

            $('#popup-action-list').modal();
         




        } else {
            $('#all_edit').prop('checked', false);
        }
        // console.log(checked);
    });
    $(".restore-list").click(function () {

        if ($("input[name='chk[]']:checked").length > 0) {

            $("input[name='chk[]']:checked").each(function ()
            {
                checked.push(parseInt($(this).val()));
                trach_ajax('restore_list' ,checked); 

            });

        } else {
            $('#all_edit').prop('checked', false);
        }
        // console.log(checked);
    });

function hide_alert(){
  setTimeout(function() {
        $(".alert-top").slideUp('400');
    }, 5000);
    }
});

