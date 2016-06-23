<div class="row alert-top"><div class="container "></div></div>
<div class="row page-title">
    <div class="container  ">
        <div class="col-xs-9">
            <h2><i class="fa fa-users"></i> Gestion de Utilisateurs</h2>
        </div>
        <div class="col-xs-3">
            <a class="btn btn-primary" href="<?php echo base_url('index.php').'/admin'?>" > Retourner</a>
        </div>
    </div>
</div>
<div class="page-content page-list-contact page-admin-users">
<div class="row top-menu-icons">
        <div class="container">
            <form class="form-admin-users form-inline " method="post" action="<?php echo base_url('index.php').'/admin/historique'?>">
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
            $this->table->set_heading( ' Admin ', 'Raison Sociale', 'Nom', 'Email' ,'Nbr SMS', ' Date d\'ajout');
            $this->table->set_template(array('table_open' => '<table  cellpadding="2" cellspacing="1" class="list-table  ">'));
            echo $this->table->generate($historique_table);
            ?>
        </div>
    </div>
</div>
