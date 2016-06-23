$(function ()
{
var d = new Date();
    $('.date').datetimepicker({
        yearOffset: 0,
        lang: 'ch',
        timepicker: true,
        format: 'Y-m-d',
        formatDate: 'Y-m-d',
        minDate: d,
        minTime: d,
    });
});