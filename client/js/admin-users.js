$(function ()
{
  /*****************************************************************************SUBMIT WHEN SELECT CHANGE "change rows table"*/
    $('#submit').hide();
    $('body').on('change', '#campaign', function () {
       $('#submit').trigger('click'); 
    });
});