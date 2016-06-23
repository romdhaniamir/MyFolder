<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="page-content ">
    <div class=" first-home-row">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 ">
                    <p class="home-large-text animated pulse">
                        Nous vous remercions pour votre inscription !
                    </p>
                    <span class="home-description">Vous devez connecter .</span>
                </div>
                <div class="col-sm-6">

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
            </div>
        </div>
    </div>
</div>
