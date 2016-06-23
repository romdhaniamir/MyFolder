$(function ()
{
	/*****************************************************************************SUBMIT WHEN SELECT CHANGE "change rows table"*/
	$('body').on('change', '#campaign', function () {
		$(".row-count-campaign-table").submit();
	});
	$('body').on('click', '#modal-delete-camp-btn', function () {
		$("#in-camp-id").val($(this).data('id'));
	});
		$("#nums").hide();
	$('body').on('click', '#nbrefus', function () {
		$("#nums").slideToggle();
	});

});