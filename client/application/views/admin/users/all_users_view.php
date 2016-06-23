<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Gestion de Utilisateurs</h2>
        </div>
        <div class="col-xs-3">
            <a class="btn btn-primary" href="<?php echo base_url('index.php').'/admin/historique'?>" ><i class="fa fa-eye"></i> Afficher l'historique des sms ajoutés</a>
        </div>
    </div>
</div>
<div class="page-content page-list-contact page-admin-users">

    <div class="row top-menu-icons">
        <div class="container">
            <form class="form-admin-users form-inline "  method="post" action="<?php echo base_url('index.php').'/admin/users'?>">
                <ul>
                    <li>
                        <label for="campaign">Nombre de lignes :</label>
                        <select id="campaign" name="users_count">
                            <?php if (set_value('users_count')): ?>
                                <option><?php echo set_value('users_count'); ?></option>
                            <?php endif; ?>
                            <option>Tous</option>
                            <option>10</option>
                            <option>30</option>
                            <option>60</option>
                            <option>100</option>
                            
                        </select>
                    </li>
                    <li>
                        <label for="user_company">Raison sociale :</label>
                        <input type="text" name="user_company" class="form-control" id="user_company" <?php if (set_value('user_company')): ?>
                                value="<?php echo set_value('user_company'); ?>"
                            <?php endif; ?>>
                    </li>
                    <li>
                        <label for="user_email">Email :</label>
                        <input type="text" name="user_email" class="form-control" id="user_email" <?php if (set_value('user_email')): ?>
                                value="<?php echo set_value('user_email'); ?>"
                            <?php endif; ?>>
                    </li>
                    <li>
                        <input type="submit" class="form-control" id="submit" value="Rechercher">
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="row list-table-box">
        <div class="container">
            <?php
            $this->table->set_heading(" Date d'inscription", ' Nom ', ' Prènom', 'Email', ' Raison sociale', ' Numéro téléphone', ' Accéder au profil','Nbr SMS','Ajouter des sms');
            $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
            echo $this->table->generate($users_table);
            ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="popup-move-all-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">Déplacer des contacts</h3>
            </div>
            <div class="modal-body">
                <div class="row center-block">
                    <h4 class="">Choisissez une liste   !</h4>
                    <select class="form-control select-lists-all" ></select><br>
                </div>
            </div>
            <div class="modal-footer">
                <a id="accepted" class="btn btn-info ">Déplacer</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

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
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-square"></i> Ajouter une liste des contacts</h4>
            </div>
            <form class=" form-add-list" method="post">
                <div class="modal-body">

                    <input type="text" class="form-control name-list " name="name_list_add" value="" placeholder="Nom de La liste">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>