<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-7">
            <h2><i class="fa fa-envelope"></i> Campagne sms</h2>
        </div>
        <div class="col-xs-5 header-btn">
            <a class="btn btn-primary  " id="bt-next-step-2"> Etape suivante <i class="fa fa-chevron-right"></i> </a>
            <a class="btn btn-primary "  id="save-campaign-btn-2"  > <i class="fa fa-floppy-o"></i> Enregistrer et quitter  </a>
        </div>
    </div>
</div>
<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-content page-profile">
    <div class="container">
        <form class="form-step2" method="post">
            <div class="row">
                <div class="col-lg-3 ">
                    <div class="container-row">
                        <div class="row ">
                            <label class="title-inside ">Etapes</label>
                        </div>
                        <div class="row">
                            <div id="progressbar">
                                <ul>
                                    <li class="current active "><a class="pencil-hover" href="<?php echo base_url() . "index.php/sms/1/".$camp_id; ?>">Message </a>
                                        <?php if ($name): ?> <dl class="subdetail">
                                                <dt>Nom: </dt>
                                                <dd class="ng-binding"><?php echo $name ?></dd>
                                                <dt>Expéditeur du SMS:</dt>
                                                <dd class="ng-binding"><?php echo $sender ?></dd>
                                            </dl>
                                        <?php endif; ?>
                                    </li>
                                    <li class=" <?php if ($name) echo "active" ?>"><i class="fa fa-caret-right"></i><a class="pencil-hover" href="<?php echo base_url() . "index.php/sms/2/".$camp_id; ?>">Destinataires </a>
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

                    <div class="row">
                        <div class="container-row">
                            <div class="row free-row ">
                                <label class="title-inside ">Listes des contacts Campagne</label>
                            </div>
                            <p class="discription">Vérifiez bien que vous envoyez le test à un contact enregistré dans une liste avec un numéro de mobile (attribut SMS) et non blacklisté pour une campagne SMS.</p>
                            <div class="alert alert-info" name="sms" role="alert"><span class="fa fa-info-circle"></span> Un passage à la ligne est considéré comme un caractère.</div>
                            <div class="row "> 
                                <?php
                                $this->table->set_heading('<input type="checkbox" id="all_list" > <label for="all_list">Toutes</label>', '<span class="count-contacts">0</span> /  ' . $total_count_contact);
                                $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table-sms  ">'));
                                echo $this->table->generate($lists);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 ">
                    <div class="container-row">

                        <div class="row ">    
                            <div class="row ">
                                <label class="title-inside ">Nombre </label>
                            </div>
                            <p class="discription">Vérifiez bien que vous envoyez le test à un contact enregistré dans une liste avec un numéro de mobile (attribut SMS) et non blacklisté pour une campagne SMS.</p>
                            <div class="row ">
                                <input type="checkbox" class="" name="all-contacts" value="1" id="from-total" <?php echo $all; ?>/> <label for="from-total">Nombre/totale :</label>                     
                                <input class="form-control total-sms number" id="total-sms" name="count-contacts" value="<?php echo $all_count; ?>" readonly="">/<span class="list_count_total">0</span><br>
                            </div>

                        </div>
                    </div> 
                </div>
            </div>
            <div clas="row">
                <input type="hidden" name="save" id="save-camp" value="0">
                <input type="hidden" name="selected-count-contacts" class="selceted-count-contacts" value="0">
                <input type="submit" class="btn btn-info send-sms-1" name="submit-step2-sms" data-role="sere" data-id="ss" value="Etape suivante " >
            </div>
        </form>
    </div>
</div>























