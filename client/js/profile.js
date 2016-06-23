$(function ()
{
    $('.password-change').click(function () {
        $('.form-password').toggle('slow');

        if ($('.form-mail').css('display') != 'none')
        {
            $('.form-mail').toggle('slow');
        }

    });
    $('.mail-change').click(function () {
        $('.form-mail').toggle('slow');
        if ($('.form-password').css('display') != 'none')
        {
            $('.form-password').toggle('slow');
        }
    });
    var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    specialKeys.push(9); //Tab
    specialKeys.push(46); //Delete
    specialKeys.push(36); //Home
    specialKeys.push(35); //End
    specialKeys.push(37); //Left
    specialKeys.push(39); //Right
    specialKeys.push(32); //Right
    $("#sender_name").keypress(function (e)
    {

        var keyCode = e.which;

        var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(keyCode) != -1));
        if (!ret) {
            $("#error-sender").css('display', 'inline');
            e.preventDefault();
        } else {
            $("#error-sender").css('display', 'none');
        }

    });
});