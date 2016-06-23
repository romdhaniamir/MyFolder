$(function (){
	var price = 0;
	var count = 1;
  document.getElementById("commander").disabled = true; 
	$('.acheter').click(function() {
    document.getElementById("commander").disabled = false; 
    	var id = $(this).attr('id');
    	price = parseInt($(this).attr('value'));
    	count = $('#number_pack').val();
    	$('#id_pack').attr('value',id);
    	$('#price').html('<b>'+price * count+'  </b><sup>DT</sup>');
  	});
  $('.personnaliser').click(function() {
    document.getElementById("commander").disabled = false; 
      price = price_personalise;
      count = $('#number_pack').val();
      $('#id_pack').attr('value',0);
      $('#price').html('<b>'+price * count+'  </b><sup>DT</sup>');
    });

  	$('body').on('change', '#number_pack', function () {
  		count = $('#number_pack').val();
      if(count < 1){
          $('#price').html('<b>'+price * 1 +'  </b><sup>DT</sup>');
          $("#alert_number").html('Le nombre doit être supérieur à 0 !');
      }else{
    	   $('#price').html('<b>'+price * count+'  </b><sup>DT</sup>');
         $("#alert_number").html('');
      }  	
    });

    /*  
        Paque personalisé
    */
    var price_sms_unitaire = 0.050;
    var number_sms = 1000;
    var price_personalise = number_sms * price_sms_unitaire;
    var slider = document.getElementById('slider');
        noUiSlider.create(slider, {
            start: 5000,
            step: 100,
            connect: 'lower',
            range: {
                  'min': 500,
                  'max': 50000
            }
        });
        var nbr_sms = document.getElementById('nbr_sms');
        slider.noUiSlider.on('update', function (values, handle) {
           
            number_sms = parseInt(values[handle].slice(0, -3));
            price_personalise = number_sms * price_sms_unitaire;
            nbr_sms.innerHTML = '<b>'+values[handle].slice(0, -3)+' </b> SMS  => <b>'+price_personalise+'</b> <sup>DT</sup>';
        });
      $('body').on('click', '#commander', function () {
        count = $('#number_pack').val();
        if(count > 0){
        var id_pack = $('#id_pack').attr('value');
        var amount = count * price;
          $.ajax({
            url: 'http://mysms.tn/client/index.php/achats/get_form/'+amount+'/'+count+'/'+id_pack,
            contentType: 'text',
            type: 'get',
            success: function (result) {
              $('#form_commande').html(result);
              $('#paiment_form').submit();
            }
          });
        }
      });
});