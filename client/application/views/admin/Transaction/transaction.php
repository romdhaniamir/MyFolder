<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-exchange"> </i> Transactions</h2>
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
                <li><a data-toggle="tab" href="#done"><i class="fa fa-check"></i>Terminé</a></li>
                <li><a data-toggle="tab" href="#Refused"><i class="fa fa-exclamation-circle"></i>Réfusé</a></li>
                <li><a data-toggle="tab" href="#canceled"><i class="fa fa-exclamation"></i>Annulé</a></li>
            </ul>
        </div>
    </div>
    <div class="row"> 
        <div class="container">
            <form class="row-count-campaign-table form-inline text-right" method="post">
                <label for="role">Raison social :</label>
                <input type="text" class="form-control" id="role" name="user_company" value="<?php echo $company ?>">
                <label for="mail">Mail :</label>
                <input type="text" class="form-control" id="mail" name="user_email" value="<?php echo $mail ?>">
                <input type="submit" class="form-control" id="submit" value="Rechercher">
            </form>
        </div>
    </div>

    <div class="row"> 
        <div class="container">
            <div class="tab-content">
                <div id="enregistre" class="tab-pane fade active in">
                    <?php
                    $this->table->set_heading('Raison social','Mail', 'Pack', 'Nombre', 'Prix', "Date");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($saved);
                    ?>
                </div>
                <div id="done" class="tab-pane fade">
                    <?php
                    $this->table->set_heading('Raison social','Mail', 'Pack', 'Nombre', 'Prix', "Date");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($success);
                    ?>
                </div>
                <div id="Refused" class="tab-pane fade">
                    <?php
                    $this->table->set_heading('Raison social','Mail', 'Pack', 'Nombre', 'Prix', "Date");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($refused);
                    ?>
                </div>
                <div id="canceled" class="tab-pane fade">
                    <?php
                    $this->table->set_heading('Raison social','Mail', 'Pack', 'Nombre', 'Prix', "Date");
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($canceled);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>