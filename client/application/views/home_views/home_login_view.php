<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="page-content ">
    <div class=" first-home-row">
        <div class="container">
            <div class="row">
            <div class="col-sm-6 ">

                <form method="post" class="login animated slideInUp ">
                    <div class="title-form">
                        <span>Connexion</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="mail" value="<?php echo set_value('mail'); ?>" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" value="" placeholder="Mot de passe">
                    </div>
                    <input type="hidden"  name="login">
                    <div >
                        <input type="submit"  class="btn btn-info" value="Connexion">

                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <form method="post" class="inscription animated slideInUp">
                    <div class="title-form">
                        <span>Nouveau ? Inscrivez-vous.</span> 
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="f_name" value="<?php echo set_value('f_name'); ?>" placeholder="Prènom">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="l_name" value="<?php echo set_value('l_name'); ?>" placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="mail" value="<?php echo set_value('mail'); ?>" placeholder="Adresse mail">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="company" value="<?php echo set_value('company'); ?>" placeholder="Raison sociale">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" value="" placeholder="Mot de passe">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password2" value="" placeholder="Confirmation du mot de passe">
                        <input type="hidden" name="inscription">
                    </div>
                    <div>
                        <input type="submit" class="btn btn-info" value="S'inscrire">
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

</div>
