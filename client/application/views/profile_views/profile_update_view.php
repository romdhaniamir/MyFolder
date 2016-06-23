<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Profil <i class="fa fa-angle-double-right"></i> Modifier</h2>
        </div>
    </div>
</div>
<div class="row page-content page-update-profile">

    <div class="row first-home-row">
        <div class="container">
            <form method="post" class="login  ">

                <div class="row">
                    <div class="title-form">
                        <span>Modifier vos informations.</span> 
                    </div>

                    <div class="col-sm-6">

                        <div class="form-group">
                            <input type="text" class="form-control" name="f_name" value="<?php echo (trim(set_value('f_name')) == '') ? $user->f_name : set_value('f_name'); ?>" placeholder="Prènom">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="l_name" value="<?php echo (trim(set_value('l_name')) == '') ? $user->l_name : set_value('l_name'); ?>" placeholder="Nom">
                        </div>

                        <div class="form-group">
                            <select type="text" name="country" class="form-control" id="country-select"><option selected><?php echo $user->country ? $user->country : 'Tunisie'; ?></option></select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="state" class="form-control" value="<?php echo (trim(set_value('state')) == '') ? $user->state : set_value('state'); ?>" placeholder="Ville">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" name="company" class="form-control" value="<?php echo (trim(set_value('company')) == '') ? $user->company : set_value('company'); ?>" placeholder="Raison sociale">
                        </div>
                        <div class="form-group">
                            <input type="text" name="address" class="form-control" value="<?php echo (trim(set_value('address')) == '') ? $user->address : set_value('address'); ?>" placeholder="Adresse">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone_number" value="<?php echo (trim(set_value('phone_number')) == '') ? $user->phone_number : set_value('phone_number'); ?>" placeholder="Numéro téléphone">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="function" value="<?php echo (trim(set_value('function')) == '') ? $user->function : set_value('function'); ?>" placeholder="function">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div>
                            <input type="submit" class="btn btn-info" value="Enregistrer">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

