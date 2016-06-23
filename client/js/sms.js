$(function ()
{   /*************************************************************************** sms words verfications*/
        var specialKeys = new Array();
        var specialKeys2 = new Array();
        specialKeys2.push(8); //Backspace
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        specialKeys.push(32); //Right
        specialKeys.push(33,40,41,42,43,44,45,47,58,59,61,63,64,91,92,93,95,123,124,125,128,224,232,233);
        $("#sms-text").keypress(function (e)
        {
            var keyCode = e.which;
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(keyCode) != -1));
            if (!ret) {
                $("#error-sender").css('display', 'inline');
                e.preventDefault();
            } else {
                $("#error-sender").css('display', 'none');
            }
            var ret2 = ( (specialKeys2.indexOf(keyCode) != -1));//Backspace only for length
            $("#error-sender-length").html($("#sms-text").val().length+1);
            if($("#sms-text").val().length>158)
            { 
                if (!ret2) {
                    e.preventDefault();

                }
                $("#error-sender-length").css('color', 'red');
            }else{
                $("#error-sender-length").css('color', 'blue');
            }
        });
   
    /*************************************************************************** check box controle*/
    $('body').on('change', '#check-date', function () {
        if (this.checked) {
            $('#date-send-sms').attr('readonly', false);
        }
        else {
            $('#date-send-sms').attr('readonly', true);
        }
    });
    
    var total_check = false;
    check_all($('#from-total').attr('id'),$('#from-total').prop('checked'));
    $('body').on('change', '#from-total', function () {
       check_all(this.id,this.checked);
    });
    
    $('body').on('change', '#all_list', function () {
        if (this.checked) {
            $('.list-contact').prop('checked', true);
            if (!total_check)
                $('.number-of-list').attr('readonly', false);
        } else {
            $('.list-contact').prop('checked', false);
            if (!total_check)
                $('.number-of-list').attr('readonly', true);
        }
        total_count();
        count_total_list();
    });
    $.each($("input[name='chk[]']:checked"), function () {
        check_checked(this.id, this.checked);
    });
    $('body').on('change', '.list-contact', function () {
        check_checked(this.id, this.checked);
    });

    $('body').on('change', '.number-of-list', function () {

        var max = $(this).data('max');
        if ($(this).val() > max)
            $(this).val(max);
        else if ($(this).val() < 0)
            $(this).val(0);
        total_count();
    });
    var list_count_total = 0;
    $('body').on('change', '#total-sms', function () {
        count_total_list();
        if ($(this).val() > list_count_total)
            $(this).val(list_count_total);
        else if ($(this).val() < 0)
            $(this).val(0);
    });
    /******************************************************************************* count total contacts functions  */
    function check_checked(id, checked) {
        if ((id) && (!total_check)) {
            if (checked)
                $("input[data-id='" + id + "']").attr('readonly', false);
            else
                $("input[data-id='" + id + "']").attr('readonly', true);
        } else {
            $("input[data-id='" + id + "']").attr('readonly', true);
        }
        if ($("input[name='chk[]']:checked").length == $(".list-contact").length) {
            $('#all_list').prop('checked', true);
        } else {
            $('#all_list').prop('checked', false);
        }
        total_count();
        count_total_list();
    }
    function check_all(id,checked){
        if (checked) {
            $('#total-sms').attr('readonly', false);
            $('.number-of-list').attr('readonly', true);
            total_check = true;
        } else
        {
            $('#total-sms').attr('readonly', true);
            $.each($("input[name='chk[]']:checked"), function () {
                $("input[data-id='" + this.id + "']").attr('readonly', false);
            });
            total_check = false;
        }
    }
    function total_count()
    {
        var total_count = 0;
        $.each($("input[name='chk[]']:checked"), function () {
            total_count += parseInt($("input[data-id='" + this.id + "']").val());
        });
        if (isNaN(total_count))
        {
            total_count = 0;
        }
        $(".count-contacts").text(total_count);
        $(".selceted-count-contacts").val(total_count);
    }

    function count_total_list()
    {
        list_count_total = 0;
        $.each($("input[name='chk[]']:checked"), function () {
            list_count_total += parseInt($("input[data-id='" + this.id + "']").data('max'));
        });
        if (isNaN(list_count_total))
        {
            list_count_total = 0;
        }
        $(".list_count_total").text(list_count_total);
    }

    /******************************************************************************* date time picker*/
    var d = new Date();
    $('#date-send-sms').datetimepicker({
        yearOffset: 0,
        lang: 'ch',
        timepicker: true,
        format: 'Y-m-d h:i:s',
        formatDate: 'Y-m-d',
        minDate: d,
        minTime: d,
    });
    
    $('body').on('change', '#check_date', function () {
        if(this.checked){
            $('#date-send-sms').show();
        }else{
            $('#date-send-sms').hide();
        }
    });
   
    /******************************************************************************* save campaign*/
    
    $('body').on('click','#save-campaign-btn',function(){
        $('#save-camp').val(1);
        $('.form-step1').submit();
    });
    $('body').on('click', '#save-campaign-btn-2', function () {
        $('#save-camp').val(1);
        $('.form-step2').submit();
    });

    $('body').on('click', '#envoie-test', function () {
        var num = $('#num').val();
        var campaign = $('#campaign').val();
        var oadc = $('#name-sender-select').val();
        var msg = $('#sms-text').val();
        if(num != "" && campaign !="" && oadc != "" && msg != ""){
            $.ajax({
            url: 'http://mysms.tn/client/index.php/sms/envoie_test',
            contentType: 'text',
            data: { 'num' : num,
                    'campaign':campaign,
                    'oadc':oadc,
                    'msg':msg},
            type: 'get',
            success: function (data) {
                $('#alert').html(data);
            }
        }); 
        }
    });

    
    
});