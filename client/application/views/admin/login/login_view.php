<div class="row alert-top"><div class="container "><?php echo validation_errors(); ?></div></div>
<div class="page-content ">
    <div class=" first-home-row">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 center-block">

                    <form method="post" class="login animated slideInUp ">
                        <div class="title-form">
                            <span><i class="fa fa-user"></i> Administration connexion</span>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="mail" value="<?php echo set_value('mail'); ?>" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" value="" placeholder="Mot de passe">
                        </div>
                        <input type="hidden"  name="login">
                        <div >
                            <input type="submit"  class="btn btn-primary" value="Connexion">

                        </div>
                    </form>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

</div>
