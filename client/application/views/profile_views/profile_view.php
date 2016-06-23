<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-md-10">
            <h2><i class="fa fa-users"></i>Profil</h2>
        </div>
        <?php if($session_admin){?>
        <div class="col-md-2">
            <a class="btn btn-primary  " value="" data-toggle="modal" data-target="#add-name-sender"><i class="fa fa-plus"></i>  Ajouter un nom expéditeur</a>
        </div>
        <?php }?>
        
    </div>
</div>
<div class="row page-content page-profile">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 "> 
                <div class="container-row">
                    <div class="row "> 

                        <label class="title-inside">Information personnelle</label>
                        <table class="table-profile-info">
                            <tr>
                                <td>Nom</td><td><?php echo $user->f_name; ?></td>
                            </tr>
                            <tr>
                                <td>Prènom</td><td><?php echo $user->l_name; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td><td><?php echo $user->mail; ?></td>
                            </tr>
                            <tr>
                                <td>Raison sociale</td><td><?php echo $user->company; ?></td>
                            </tr>
                            <tr>
                                <td>Pays</td><td><?php echo $user->country; ?></td>
                            </tr>
                            <tr>
                                <td>Ville</td><td><?php echo $user->state; ?></td>
                            </tr>
                            <tr>
                                <td>Adresse</td><td><?php echo $user->address; ?></td>
                            </tr>
                            <tr>
                                <td>Numéro téléphone</td><td><?php echo $user->phone_number; ?></td>
                            </tr>
                            <tr>
                                <td>Fonction</td><td><?php echo $user->function; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-push-5 col-md-3">
                        <a class="btn btn-info " href="<?php echo base_url() ?>index.php/profile/update"  > <i class="fa fa-pencil-square-o"></i> Vérifier/modifier vos informations</a>
                    </div>
                </div> 
            </div>
            <div class="col-lg-8 ">
                <div class="container-row">
                    <div class="row ">
                        <label class="title-inside">Autres réglages<p class="right text-right"> SMS disponible : <span class=" sms_number " id="nbr_sms" ><?php echo $nbr_sms ; ?> </span> <a class="btn btn-danger ajouter_solde" href='<?php echo base_url() . 'index.php/achats'; ?>'><i class="fa fa-cart-plus"></i></a></p></label>
                    </div>
                    <div class=" actions row">
                        <div class="col-md-6">
                            <!--<center><a class="password-change btn btn-primary"><i class="fa fa-pencil-square-o" ></i> Changer mot de Passe</a></center>--><br>
                            <form method="post" class="form-password">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="this-password" value="" placeholder="Mot de passe actuel">
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" value="" placeholder="Mot de passe">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password2" value="" placeholder="Confirmation du mot de passe">
                                </div>
                                <div>
                                    <input type="submit" class="btn btn-info" name="update-passe" value="Enregistrer">
                                    <br>
                                </div>
                            </form>
                        </div><br>
                        <div class="col-md-6">
                            <div class="alert alert-info" role="alert" id="alert">
                                <span class="fa fa-info-circle"> </span> Informations générales<br>
                                ...<br>...<br>...<br>...<br>
                            </div>
                        </div>
                    </div><br>
                    <div class="row"> 
                        <div class="container-row">
                            <div class="row  free-row">
                                <label class="title-inside ">Expéditeur</label>
                            </div>
                            <div class="row">
                                <span id="error-sender" style="color: Red; display: none">* Caractères spéciaux non autorisé</span>
                                <?php
                                $this->table->set_heading('Nom expéditeur' ,'Actions');
                                $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table table  table-senders">'));
                                echo $this->table->generate($senders);
                                ?>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-name-sender" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-square"></i> Ajouter un nom expéditeur</h4>
            </div>
            <form class=" form-add-list" method="post">
                <div class="modal-body">

                    <input type="text" class="form-control  " name="sender_name" id="sender_name" value="" placeholder="Nom expéditeur">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" name="add-sender" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
