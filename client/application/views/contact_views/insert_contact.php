<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?>  <?php echo $this->upload->display_errors('<div class="alert alert-danger">', '</div>'); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2>Ajouter de contacts dans la liste <i class="fa fa-angle-double-right"></i> <?php echo $name_list; ?></h2>
        </div>
        <div class="col-xs-3">

        </div>
    </div>
</div>

<div class="row page-content page-insert-contact">
    <div class="row">
        <div class="container">
            <div class="col-sm-7 ">
                <div class="container-row-min">
                    <form method="post" class="login ">
                        <div class="title-form">
                            <span>Ajouter un contact</span>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone_number" value="<?php echo set_value('phone_number'); ?>" placeholder="Numéro téléphone" pattern="[2459][0-9]{7}">
                        </div>
                        <div class="form-group">
                            <input type="mail" class="form-control" name="mail" value="<?php echo set_value('mail'); ?>" placeholder="Adresse email">
                        </div>
                        <div class="form-group">
                            <input type="text" name="f_name" class="form-control" value="<?php echo set_value('f_name'); ?>" placeholder="Nom">
                        </div>
                        <div class="form-group">
                            <input type="text" name="l_name" class="form-control" value="<?php echo set_value('l_name'); ?>" placeholder="Prénom">
                        </div>
                        <div class="form-group">
                            <select type="text" name="country" class="form-control" id="country-select"><option selected><?php echo set_value('country') ? set_value('country') : 'Tunisie'; ?></option></select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="address" class="form-control" value="<?php echo set_value('address'); ?>" placeholder="Adresse">
                        </div>
                        <div class="form-group">
                            <?php if (set_value('sex') == 'femme') { ?>
                                <input type="radio" name="sex" id="sexm"   value="homme" ><label for="sexm"> M.</label>                
                                <input type="radio" name="sex" id="sexf"  value="femme" checked><label for="sexf"> Mme.</label>
                            <?php } else { ?>
                                <input type="radio" name="sex" id="sexm"   value="homme" checked><label for="sexm"> M.</label>                
                                <input type="radio" name="sex" id="sexf"  value="femme" ><label for="sexf"> Mme.</label>
                            <?php } ?>
                        </div>
                        <input type="submit" class="btn btn-info" value="Ajouter ">

                    </form>

                </div>
            </div>
            <div class="col-sm-5 ">
                <div class="container-row-min">
                    <?php echo form_open_multipart('', $attributes = array('class' => 'login ')) ?>
                    <div class="title-form">
                        <span> Liste de contacts a partir d'un fichier excel.</span>
                    </div>
                    <div class="form-group row  descrip-excel-insert">
                        <div class="col-xs-2">
                            <i class="fa fa-file-excel-o"></i>
                        </div>
                        <div class="col-xs-10">
                            <p>Formats :</p>
                            <p>- (.xls) Excel 95/2003 </p>
                            <p>- (.xlsx) Excel 2007/2010 </p>
                            <p>- (.xml) Excel 2003</p>
                        </div>
                    </div>
                    <div class="form-group"><input  type="file" class="form-control input-file" name="file_excel" id="file-excel"> </div>

                    <input type="submit" class="btn btn-info" value="Enrégistrer">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

