<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-envelope"></i> Mes campagnes</h2>
        </div>
        <div class="col-xs-3">

        </div>
    </div>
</div>
<div class="row page-content page-campaigns">

    <div class="row top-menu-icons">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#enregistre"><i class="fa fa-floppy-o"></i>Enregisté</a></li>
                <li><a data-toggle="tab" href="#termine"><i class="fa fa-check"></i>Terminé</a></li>
                <li><a data-toggle="tab" href="#en_cours"><i class="fa fa-exchange"></i>En cours</a></li>
                <li><a data-toggle="tab" href="#echec"><i class="fa fa-question"></i>Echec</a></li>
            </ul>
        </div>
    </div>
    <div class="row"> 
        <div class="container">
            <form class="row-count-campaign-table" method="post">
                <label for="campaign">Nombre de lignes :</label>
                <select id="campaign" name="campaign_count">
                    <?php if (set_value('campaign_count')): ?>
                        <option><?php echo set_value('campaign_count'); ?></option>
                    <?php endif; ?>
                    <option>10</option>
                    <option>30</option>
                    <option>60</option>
                    <option>100</option>
                    <option>tous</option>
                </select>
                <input type="text" name="role-camp" value="<?php echo $role ?>" hidden="">
            </form>
        </div>
    </div>

    <div class="row"> 
        <div class="container">
            <div class="tab-content">
                <div id="enregistre" class="tab-pane fade active in">
                    <?php
                    $this->table->set_heading('<label for="all-contacts" class="lab-liste">Nom</label>', 'Nom Expéditeur', 'Date de création', 'Nombre des sms', "Date d'envoi", "");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($campaigns_saved);
                    ?>
                </div>
                <div id="termine" class="tab-pane fade">
                    <?php
                    $this->table->set_heading('<label for="all-contacts" class="lab-liste">Nom</label>', 'Nom Expéditeur', 'Nombre des sms envoyés', 'Nombre des sms réçus', "Date d'envoi","");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($campaigns_done);
                    ?>
                </div>
                <div id="en_cours" class="tab-pane fade">
                    <?php
                    $this->table->set_heading('<label for="all-contacts" class="lab-liste">Nom</label>', 'Nom Expéditeur', 'Nombre des sms envoyés', 'Nombre des sms réçus', "Date d'envoi");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($campaigns_progress);
                    ?>
                </div>
                <div id="echec" class="tab-pane fade">
                    <?php
                    $this->table->set_heading('<label for="all-contacts" class="lab-liste">Nom</label>', 'Nom Expéditeur', 'Date de création', 'Nombre des sms',"");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($campaigns_echec);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-list-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-times-circle"></i> Supprimer une campagne</h4>
            </div>

            <div class="modal-body">

                <p> Voulez vous vraiment supprimer cette campagne !</p>

            </div>
            <div class="modal-footer">

                <form method="post">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <input type="hidden" id="in-camp-id" name="delete" value=""/>
                    <button type="submit" class="btn btn-primary" id="delete-camp-bt" >Supprimer</button>
                </form>
            </div>

        </div>
    </div>
</div>


