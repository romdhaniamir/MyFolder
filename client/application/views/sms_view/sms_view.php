<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-7">
            <h2><i class="fa fa-envelope"></i> Campagne sms</h2>
        </div>
        <div class="col-xs-5 header-btn"> 
            <a class="btn btn-primary" id="bt-next-step-1"> Etape suivante <i class="fa fa-chevron-right"></i> </a>
            <a class="btn btn-primary "  id="save-campaign-btn"  > <i class="fa fa-floppy-o"></i> Enregistrer et quitter  </a>
            <a class="btn btn-primary "   href="<?php echo base_url() . "index.php/sms/0/0"; ?>"  > <i class="fa fa-envelope"></i> Nouveau </a>
        </div>
    </div>
</div>
<div class="row page-content page-profile">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 ">
                <div class="container-row">
                    <div class="row ">
                        <label class="title-inside ">Etapes</label>
                    </div>
                    <div class="row">
                        <div id="progressbar">
                            <ul>
                                <li class="current active "><i class="fa fa-caret-right"></i><a class="pencil-hover" href="<?php echo base_url() . "index.php/sms/1/".$camp_id; ?>">Message </a>
                                    <?php if ($name): ?> <dl class="subdetail">
                                            <dt>Nom: </dt>
                                            <dd class="ng-binding"><?php echo $name ?></dd>
                                            <dt>Expéditeur du SMS:</dt>
                                            <dd class="ng-binding"><?php echo $sender ?></dd>
                                        </dl>
                                    <?php endif; ?>
                                </li>
                                <li class=" <?php if ($name) echo "active" ?>"><a class="pencil-hover" href="<?php echo base_url() . "index.php/sms/2/".$camp_id; ?>">Destinataires </a>
                                    <?php if ($total_count_step): ?> 
                                        <dl class="subdetail">
                                            <dt>Nombre de contacts: </dt>
                                            <dd class="ng-binding"><?php echo $total_count_step ?></dd>
                                        </dl>
                                    <?php endif; ?>
                                </li>
                                <li class="<?php if ($total_count_step) echo "active" ?> last"><a class="pencil-hover" href="<?php echo base_url() . "index.php/sms/3/".$camp_id; ?>">Confirmation</a></li>
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-lg-5 "> 
                <form class="form-step1" method="post">
                    <div class="row">
                        <div class="container-row">
                            <div class="row ">
                                <label class="title-inside ">Campagne</label>
                            </div>
                            <div class="row free-row">
                                <label for="name-sender-select">Nom de la campagne :</label>
                                <input class="form-control" id="campaign" name="campaign" type="text" value="<?php echo $name ?>">
                            </div>
                            <div class="row free-row">
                                <label for="name-sender-select">Nom expéditeur :</label>
                                <select class="form-control" name="name-sender" id="name-sender-select"><option><?php echo $sender ?></option><?php echo $list_sender; ?></select>
                            </div>
                            <div class="row free-row">
                                <label for="sms-text">Votre SMS :</label>
                                <div class="alert alert-info" name="sms" role="alert"><span class="fa fa-info-circle"></span> Un passage à la ligne est considéré comme un caractère.</div>
                                <textarea  class="form-control" name="sms-text" id="sms-text"><?php echo $sms ?></textarea><br>
                                <div role="alert" class="alert alert-danger"id="error-sender" style="color: Red; display: none">* Caractéres spéciaux non autorisé</div>
                                <div id="error-sender-length" class="text-right"  style="color: blue;">0</div> 
                            </div>
                            <div class="row">
                                <input type="hidden" name="save" id="save-camp" value="0">
                                <input type="submit" class="btn btn-info send-sms-1" name="submit-step1-sms"  data-id="ss" value="Etape suivante " >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 ">
                <div class="container-row">
                    <div class="row ">
                        <label class="title-inside ">Envoyer un SMS de test</label>
                        <p class="discription">Vérifiez bien que vous envoyez le test à un contact enregistré dans une liste avec un numéro de mobile (attribut SMS) et non blacklisté pour une campagne SMS.</p>
                        <input type="text" name="num" id="num" class="form-control" pattern="[2459][0-9]{7}" ><br>
                        <div class="alert alert-info" role="alert" id="alert">
                            <span class="fa fa-info-circle"> </span> L'envoi d'un SMS de test sera déduit de vos crédits SMS.
                        </div>
                        <input type="submit" class="btn btn-info "  name="envoie-test" id="envoie-test"  value="Envoyer " >
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
