<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Gestion des Contacts  <i class="fa fa-angle-double-right"></i> <?php echo $name_list; ?></h2>
        </div>
        <div class="col-xs-3">
            <a class="btn btn-primary  " value="" data-toggle="modal" data-target="#add-list-modal"> <i class="fa fa-list"> </i>  Ajouter une liste des contacts</a>
        </div>
    </div>
</div>
<div class="row page-content page-contact">
        <div class="row">
               <div class="container">
            <div class="col-lg-4 "> 
                <div class="row top-menu-icons">
                    <a href="<?php echo base_url() . "index.php/lists_contacts/trash" ?> "><i class="fa fa-trash"></i>Corbeille </a>
                    <span id="trash-count"></span>
                </div>
                <div class="row list-table-box">
                    <?php
                    $this->table->set_heading('<label for="all-contacts" class="lab-liste"> <i class="fa fa-list"> </i> Listes des contacts</label> ', '', '', '');
                    $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
                    echo $this->table->generate($names_view);
                    ?>
                </div>
            </div>
            <div class="col-lg-8 ">
                <form method="post" class="login update-contact-form">
                    <div class="title-form">
                        <span>Mettre à jour</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone_number" value="<?php echo $contact->phone_number; ?>" placeholder="Numéro téléphone">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="mail" value="<?php echo $contact->mail; ?>" placeholder="Adresse mail">
                    </div>
                    <div class="form-group">
                        <input type="text" name="l_name" class="form-control" value="<?php echo $contact->l_name; ?>" placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <input type="text" name="f_name" class="form-control" value="<?php echo $contact->f_name; ?>" placeholder="Prénom">
                    </div>
                    <div class="form-group">
                        <input type="text" name="Adresse" class="form-control" value="<?php echo $contact->address; ?>" placeholder="Ville">
                    </div>
                    <div class="form-group">
                        <select type="text" name="country" class="form-control" id="country-select"><option selected><?php echo $contact->country ?></option></select>
                    </div>

                    <div class="form-group">
                        <?php if ($contact->sex == 'femme') { ?>
                            <input type="radio" name="sex" id="sexm"   value="homme" ><label for="sexm"> M.</label>                
                            <input type="radio" name="sex" id="sexf"  value="femme" checked><label for="sexf"> Mme.</label>
                        <?php } else { ?>
                            <input type="radio" name="sex" id="sexm"   value="homme" checked><label for="sexm"> M.</label>                
                            <input type="radio" name="sex" id="sexf"  value="femme" ><label for="sexf"> Mme.</label>
                        <?php } ?>
                    </div>
                    <input type="submit" class="btn btn-info" value="Enregistrer">
                </form>
            </div>
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