<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Gestion des paques</h2>
        </div>
        <div class="col-xs-3">
        </div>
    </div>
</div>
<div class="page-content page-list-contact page-admin-users">

    <div class="row list-table-box">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#view">Paques disponible</a></li>
                <li><a data-toggle="tab" href="#add">Ajout</a></li>
            </ul>
            <div class="tab-content">
                <div id="view" class="tab-pane fade in active">
                    <?php
                    $this->table->set_heading(' Nom ', ' Descripion', 'Nombre des sms', 'Pays', 'Date début', 'Date fin', 'Prix','');
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($packs_table);
                    ?>
                </div>
                <div id="add" class="tab-pane fade">
                    <nav class="">
                        <br>
                        <form action="<?php echo base_url() . 'index.php/admin_packs/create_pack'; ?>" method="POST">
                            <div class="col-md-3">
                                <label for="name">Nom *</label>
                            </div>
                            <div class="col-md-7">
                                <input id="name" name="name" class="form-control" type="text" required></input>
                            </div>
                            <div class="col-md-3">
                                <label for="prix">Prix *</label>
                            </div>
                            <div class="col-md-7">
                                <input id="prix" name="price" class="form-control" type="number" required>
                            </div>
                            <div class="col-md-3">
                                <label for="pays">Nombre des sms *</label>
                            </div>
                            <div class="col-md-7">
                                <input id="nbr_sms" name="nbr_sms" class="form-control" type="number" required>
                            </div>
                            <div class="col-md-3">
                                <label for="pays">Pays </label>
                            </div>
                            <div class="col-md-7">
                                <input id="pays" name="pays" class="form-control" type="text">
                            </div>
                            <div class="col-md-3">
                                <label for="date_fin">Date de début </label>
                            </div>
                            <div class="col-md-7">
                                <input id="date_debut" name="date_debut" class="form-control date" type="text" >
                            </div>
                            <div class="col-md-3">
                                <label for="date_fin">Date d'expiration </label>
                            </div>
                            <div class="col-md-7">
                                <input id="date_fin" name="date_fin" class="form-control date" type="text">
                            </div>
                            <div class="col-md-3">
                                <label for="desc">Description </label>
                            </div>
                            <div class="col-md-7">
                                <textarea id="desc" name="desc" class="form-control"></textarea>
                            </div>
                            <center>
                                <input type="submit" class="btn btn-primary" value="Valider">
                            </center>
                        </form>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
