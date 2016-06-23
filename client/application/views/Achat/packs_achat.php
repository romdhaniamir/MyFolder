
<div class="row alert-top"><div class="container "></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
           <h2><i class="fa fa-cart-plus"></i> Mes Achats </h2>
       </div>
   </div>
</div>
<div class="page-content page-list-contact page-admin-users">
    <div class="row list-table-box">
        <div class="container">

            <div  class="col-md-8">
                <div class="plan-list flex-box row "> 
                    <?php
                    foreach ($packs_table as $pack) {?>
                        <div class="col-lg-15 col-md-3 col-sm-6 col-xs-12 box"> 
                            <div>
                                <div class="plan-tile"> 
                                    <div class="plan-title"><?php echo $pack->name ?>
                                    </div>
                                    <div class="plan-pricer">
                                        <span class="plan-price">
                                            <span>
                                                <sup></sup><?php echo $pack->price ?> <sup> DT</sup>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="plan-features">
                                        <div class="plan-feat"><?php echo $pack->nbr_sms ?> SMS
                                        </div>                                                
                                        <ul class="list-unstyled">
                                            <li><i class="fa fa-check text-success"></i> <?php echo $pack->desc ?> </li>
                                        </ul>
                                    </div>
                                    <div class="plan-btn">
                                        <a class="btn btn-green btn-block acheter" id="<?php echo $pack->id_pack ?>" value="<?php echo $pack->price ?>" >Acheter</a>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-12 box"> 
                            <div class="plan-tile"> 
                                <div class="plan-title">Pack Personnalisé
                                </div>
                                <div class="plan-pricer">
                                    <span class="plan-price">
                                        <h5><strong>Nombre des SMS</strong></h5>
                                        <div id="slider">

                                        </div>
                                        <span>
                                            <sup></sup> <sup></sup>
                                        </span>
                                    </span>
                                </div>
                                <div class="plan-features">
                                    <div class="plan-feat"><h4 id="nbr_sms"></h4>
                                    </div>  
                                </div>
                                <div class="plan-btn">
                                    <a class="btn btn-green btn-block personnaliser" id="" value="" >Acheter</a>
                                </div>
                            </div>  
                        </div>
                    </div> 
                </div>
                <div class="col-md-4 panel" style=" padding-left: 37px; padding-right: 35px; padding-top: 12px; ">
                    <div class="row">
                        <label class="title-inside ">Alimenter votre compte <h2 class="text-right" id="price"><b> 0 </b><sup> DT</sup></h2></label>
                        <p class="discription">Vérifiez bien votre choix pour pouvoir continuer.</p><br>
                        <input type="hidden" name="id_pack" id='id_pack' value="0">
                    </div>
                         <center>
                            <h4>Nombre</h4>
                            <input type="number" name="number_pack" id="number_pack" class="form-control" value="1" required >
                                         
                        </center>
                    <br>
                    <div id="form_commande" style="color: #FF5959; font-size: 13px;">
                
                    </div>
                    <div class="row" style="text-align : center;">
                        <input type="button" class="btn btn-info " id='commander' name="Valider" value=" Commander ">
                    </div>                
        </div>
    </div>

</div>
</div>

